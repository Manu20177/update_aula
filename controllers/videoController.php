<?php
	if($actionsRequired){
		require_once "../models/videoModel.php";
	}else{ 
		require_once "./models/videoModel.php";
	}

	class videoController extends videoModel{

		/*----------  Funcion para ejecutar una consulta simple - Function to execute a simple query  ----------*/
		public function execute_single_query($query){
			$response=self::connect()->prepare($query);
			$response->execute();
			return $response;
		}
		/*----------  Add Video Controller  ----------*/
		public function add_video_controller(){
			$title=self::clean_string($_POST['title']);
			$teacher=self::clean_string($_POST['teacher']);
			$date=self::clean_string($_POST['date']);
			$horas=self::clean_string($_POST['horas']);
			$cursos_select=self::clean_string($_POST['cursos_select']);
			$video=$_POST['code'];
			$description=$_POST['description'];

			$AttMaxSize=5120;
			$AttDir="../attachments/class/";

			$ATC=0;
			$AttFinalName="";
			$AttFinalNameTMP="";
			foreach($_FILES["attachments"]['tmp_name'] as $key => $tmp_name){
				if($_FILES["attachments"]["name"][$key]){

					$AttType=$_FILES['attachments']['type'][$key];
					$AttSize=$_FILES['attachments']['size'][$key];

					if($AttType=="image/jpeg" || $AttType=="image/png" || $AttType=="application/msword" || $AttType=="application/vnd.ms-powerpoint" || $AttType=="application/pdf" || $AttType=="application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $AttType=="application/vnd.openxmlformats-officedocument.presentationml.presentation"){
						if(($AttSize/1024)<=$AttMaxSize){

							$AttName = str_ireplace(",", "", $_FILES["attachments"]["name"][$key]);
							$AttName = str_ireplace(" ", "_", $_FILES["attachments"]["name"][$key]);
							$finalDir=$AttDir.$AttName;

							if(is_file($finalDir)){
								if($AttFinalNameTMP!=""){
									$delTMP=explode(",", $AttFinalNameTMP);
									foreach ($delTMP as $delFile) {
										$filesAD=$AttDir.$delFile;
										chmod($filesAD, 0777);
										unlink($filesAD);
									}
								}
								$dataAlert=[
									"title"=>"¡Ocurrió un error inesperado!",
									"text"=>"Ya existe un archivo con el nombre <b>".$AttName."</b> registrado en el sistema por favor cambie el nombre del archivo adjunto antes de subirlo",
									"type"=>"error"
								];
								return self::sweet_alert_single($dataAlert);
								exit();
							}else{
								chmod($AttDir, 0777);

								if(move_uploaded_file($_FILES["attachments"]['tmp_name'][$key], $finalDir)){
									if($ATC==0){
										$AttFinalName.=$AttName;
										$AttFinalNameTMP.=$AttName;
										$ATC++;
									}else{
										$AttFinalName.=",".$AttName;
										$AttFinalNameTMP.=$AttName;
									}	
								}else{	
									$dataAlert=[
										"title"=>"¡Ocurrió un error inesperado!",
										"text"=>"No se pudo cargar uno o más de los archivos adjuntos seleccionados",
										"type"=>"error"
									];
									return self::sweet_alert_single($dataAlert);
									exit();
								}
							}
						}else{
							$dataAlert=[
								"title"=>"¡Ocurrió un error inesperado!",
								"text"=>"El tamaño de uno de los archivos supera el límite de peso máximo que son 5MB",
								"type"=>"error"
							];
							return self::sweet_alert_single($dataAlert);
							exit();
						}
					}else{
						$dataAlert=[
							"title"=>"¡Ocurrió un error inesperado!",
							"text"=>"El tipo de formato de uno de los archivo que acaba de seleccionar no esta permitido",
							"type"=>"error"
						];
						return self::sweet_alert_single($dataAlert);
						exit();
					}
				}
			}

			$query1=self::execute_single_query("SELECT Titulo FROM clase WHERE Titulo='$title'");

			if($query1->rowCount()>=1){
				$dataAlert=[
					"title"=>"¡Ocurrió un error inesperado!",
					"text"=>"El título de la clase ya se encuentra registrado por favor elija otro",
					"type"=>"error"
				];
				return self::sweet_alert_single($dataAlert);
			}else{
			    $val_posicion=self::execute_single_query("SELECT COUNT(*) FROM `curso_clase` WHERE id_curso='$cursos_select'");
				// Obtener el valor del COUNT(*)
				$count = $val_posicion->fetchColumn(); // fetch_row() devuelve un arreglo
				// Sumarle 1
				$posicion = $count + 1;
				$data=[
					"Video"=>$video,
					"Fecha"=>$date,
					"Titulo"=>$title,
					"Tutor"=>$teacher,
					"Curso"=>$cursos_select,
					"Descripcion"=>$description,
					"Posicion"=>$posicion,
					"Horas"=>$horas,
					"Adjuntos"=>$AttFinalName
				];
				if(self::add_video_model($data)){
					$dataAlert=[
						"title"=>"¡Clase registrada!",
						"text"=>"La clase se registró con éxito en el sistema",
						"type"=>"success"
					];
					return self::sweet_alert_reset($dataAlert);
				}else{
					if($AttFinalName!=""){
						$filesA=explode(",", $AttFinalName);
						foreach ($filesA as $files) {
							chmod($AttDir.$files, 0777);
							unlink($AttDir.$files);	
						}
					}

					$dataAlert=[
						"title"=>"¡Ocurrió un error inesperado!",
						"text"=>"No hemos podido registrar la clase, por favor intente nuevamente",
						"type"=>"error"
					];
					return self::sweet_alert_single($dataAlert);
				}
			}
		}


		/*----------  Pagination Video Controller  ----------*/
		public function pagination_video_controller(){

			$Datos=self::execute_single_query("
				SELECT * FROM clase WHERE estado_clase=4 ORDER BY Fecha DESC
			");
			$Datos=$Datos->fetchAll();

	
			$table='
			<table id="tabla-global" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Fecha</th>
						<th class="text-center">Titulo</th>
						<th class="text-center">Tutor</th>
						<th class="text-center">Ver</th>
						<th class="text-center">Actualizar</th>
						<th class="text-center">Eliminar</th>
					</tr>
				</thead>
				<tbody>
			';
			$cont=1;


			foreach($Datos as $rows){
					$table.='
					<tr>
						<td>'.$cont.'</td>
						<td>'.date("d/m/Y", strtotime($rows['Fecha'])).'</td>
						<td>'.$rows['Titulo'].'</td>
						<td>'.$rows['Tutor'].'</td>
						<td>
							<a href="'.SERVERURL.'classview/'.$rows['id'].'/" class="btn btn-info btn-raised btn-xs">
								<i class="zmdi zmdi-tv"></i>
							</a>
						</td>
						<td>
							<a href="'.SERVERURL.'classinfo/'.$rows['id'].'/" class="btn btn-success btn-raised btn-xs">
								<i class="zmdi zmdi-refresh"></i>
							</a>
						</td>
						<td>
							<form action="'.SERVERURL.'ajax/ajaxVideo.php" method="POST" enctype="multipart/form-data" autocomplete="off" data-form="DellVideo" class="ajaxDataForm">
								<input type="hidden" name="videoCode" value="'.$rows['id'].'">
								<button type="submit" class="btn btn-danger btn-raised btn-xs">
									<i class="zmdi zmdi-delete"></i>
								</button>
								<div class="full-box form-process"></div>
							</form>
						</td>
					</tr>
					';
					$cont++;
				}

			$table.='
				</tbody>
			</table>
			';

		
			return $table;
		}


		/*----------  Pagination Video Now Controller  ----------*/
		public function pagination_video_now_controller($Pagina,$Registros,$datenow){
			$Pagina=self::clean_string($Pagina);
			$Registros=self::clean_string($Registros);
			$id_usu=$_SESSION['userKey'];

			$Pagina = (isset($Pagina) && $Pagina>0) ? floor($Pagina) : 1;

			$Inicio = ($Pagina>0) ? (($Pagina * $Registros)-$Registros) : 0;

			$Datos=self::execute_single_query("
				SELECT * FROM clase c 
				LEFT JOIN curso_clase cc on cc.id_clase=c.id 
				LEFT JOIN curso cur on cur.id_curso=cc.id_curso
				LEFT JOIN curso_alumno ca on ca.id_curso=cur.id_curso
				WHERE c.Fecha='$datenow' && ca.estado_curso=4 && c.estado_clase=4 && ca.id_alumno='$id_usu' ORDER BY c.Fecha DESC LIMIT $Inicio,$Registros
			");
			$Datos=$Datos->fetchAll();

			$Total=self::execute_single_query("SELECT * FROM clase WHERE Fecha='$datenow'");
			$Total=$Total->rowCount();

			$Npaginas=ceil($Total/$Registros);

			$table='
			<table class="table text-center">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Fecha</th>
						<th class="text-center">Titulo</th>
						<th class="text-center">Tutor</th>
						<th class="text-center">Ver</th>
					</tr>
				</thead>
				<tbody>
			';

			if($Total>=1){
				$nt=$Inicio+1;
				foreach($Datos as $rows){
					$table.='
					<tr>
						<td>'.$nt.'</td>
						<td>'.date("d/m/Y", strtotime($rows['Fecha'])).'</td>
						<td>'.$rows['Titulo'].'</td>
						<td>'.$rows['Tutor'].'</td>
						<td>
							<a href="'.SERVERURL.'classview/'.$rows['id'].'/" class="btn btn-info btn-raised btn-xs">
								<i class="zmdi zmdi-tv"></i>
							</a>
						</td>
					</tr>
					';
					$nt++;
				}
			}else{
				$table.='
				<tr>
					<td colspan="5">No hay clases para hoy</td>
				</tr>
				';
			}

			$table.='
				</tbody>
			</table>
			';

			if($Total>=1){
				$table.='
					<nav class="text-center full-width">
						<ul class="pagination pagination-sm">
				';

				if($Pagina==1){
					$table.='<li class="disabled"><a>«</a></li>';
				}else{
					$table.='<li><a href="'.SERVERURL.'videonow/'.($Pagina-1).'/">«</a></li>';
				}

				for($i=1; $i <= $Npaginas; $i++){
					if($Pagina == $i){
						$table.='<li class="active"><a href="'.SERVERURL.'videonow/'.$i.'/">'.$i.'</a></li>';
					}else{
						$table.='<li><a href="'.SERVERURL.'videonow/'.$i.'/">'.$i.'</a></li>';
					}
				}

				if($Pagina==$Npaginas){
					$table.='<li class="disabled"><a>»</a></li>';
				}else{
					$table.='<li><a href="'.SERVERURL.'videonow/'.($Pagina+1).'/">»</a></li>';
				}

				$table.='
						</ul>
					</nav>
				';
			}

			return $table;
		}


		/*----------  Pagination Video Search Controller  ----------*/
		public function pagination_video_search_controller($Pagina,$Registros,$search){
			$Pagina=self::clean_string($Pagina);
			$Registros=self::clean_string($Registros);
			$search=self::clean_string($search);

			$Pagina = (isset($Pagina) && $Pagina>0) ? floor($Pagina) : 1;

			$Inicio = ($Pagina>0) ? (($Pagina * $Registros)-$Registros) : 0;

			$Datos=self::execute_single_query("
				SELECT * FROM clase WHERE Titulo LIKE '%$search%' OR Tutor LIKE '%$search%' ORDER BY id DESC LIMIT $Inicio,$Registros
			");
			$Datos=$Datos->fetchAll();

			$Total=self::execute_single_query("
				SELECT * FROM clase WHERE Titulo LIKE '%$search%' OR Tutor LIKE '%$search%'"
			);
			$Total=$Total->rowCount();

			$Npaginas=ceil($Total/$Registros);

			$table='
			<table class="table text-center">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Fecha</th>
						<th class="text-center">Titulo</th>
						<th class="text-center">Tutor</th>
						<th class="text-center">Ver</th>
					</tr>
				</thead>
				<tbody>
			';

			if($Total>=1){
				$nt=$Inicio+1;
				foreach($Datos as $rows){
					$table.='
					<tr>
						<td>'.$nt.'</td>
						<td>'.date("d/m/Y", strtotime($rows['Fecha'])).'</td>
						<td>'.$rows['Titulo'].'</td>
						<td>'.$rows['Tutor'].'</td>
						<td>
							<a href="'.SERVERURL.'classview/'.$rows['id'].'/" class="btn btn-info btn-raised btn-xs">
								<i class="zmdi zmdi-tv"></i>
							</a>
						</td>
					</tr>
					';
					$nt++;
				}
			}else{
				$table.='
				<tr>
					<td colspan="5">
						No hay clases registradas que coincidan con el término de búsqueda que acaba de ingresar
					</td>
				</tr>
				';
			}

			$table.='
				</tbody>
			</table>
			';

			if($Total>=1){
				$table.='
					<nav class="text-center full-width">
						<ul class="pagination pagination-sm">
				';

				if($Pagina==1){
					$table.='<li class="disabled"><a>«</a></li>';
				}else{
					$table.='<li><a href="'.SERVERURL.'search/'.($Pagina-1).'/">«</a></li>';
				}

				for($i=1; $i <= $Npaginas; $i++){
					if($Pagina == $i){
						$table.='<li class="active"><a href="'.SERVERURL.'search/'.$i.'/">'.$i.'</a></li>';
					}else{
						$table.='<li><a href="'.SERVERURL.'search/'.$i.'/">'.$i.'</a></li>';
					}
				}

				if($Pagina==$Npaginas){
					$table.='<li class="disabled"><a>»</a></li>';
				}else{
					$table.='<li><a href="'.SERVERURL.'search/'.($Pagina+1).'/">»</a></li>';
				}

				$table.='
						</ul>
					</nav>
				';
			}

			return $table;
		}


		/*----------  Pagination Video List Controller  ----------*/
		public function pagination_video_list_controller(){
			
			$id_usu=$_SESSION['userKey'];

			$Datos=self::execute_single_query("
				SELECT *, c.Titulo as Clase, cur.Titulo as Curso FROM clase c 
				LEFT JOIN curso_clase cc on cc.id_clase=c.id 
				LEFT JOIN curso cur on cur.id_curso=cc.id_curso 
				LEFT JOIN curso_alumno ca on ca.id_curso=cur.id_curso 
				WHERE cur.Estado!=5 && c.estado_clase=4 && ca.id_alumno='$id_usu' 
				ORDER BY c.Fecha DESC 
			");
			$Datos=$Datos->fetchAll();

			

			$table='
			<table id="tabla-global" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Fecha</th>
						<th class="text-center">Curso</th>
						<th class="text-center">Clase</th>
						<th class="text-center">Tutor</th>
						<th class="text-center">Ver</th>
					</tr>
				</thead>
				<tbody>
			';
			$cont=1;

			foreach($Datos as $rows){
					$table.='
					<tr>
						<td>'.$cont.'</td>
						<td>'.date("d/m/Y", strtotime($rows['Fecha'])).'</td>
						<td>'.$rows['Curso'].'</td>
						<td>'.$rows['Clase'].'</td>
						<td>'.$rows['Tutor'].'</td>
						<td>
							<a href="'.SERVERURL.'classview/'.$rows['id'].'/" class="btn btn-info btn-raised btn-xs">
								<i class="zmdi zmdi-tv"></i>
							</a>
						</td>
					</tr>
					';
					$cont++;
				}

			$table.='
				</tbody>
			</table>
			';

			

			return $table;
		}


		/*----------  Delete Video Controller  ----------*/
		public function delete_video_controller($code){
			$code=self::clean_string($code);

			$queryval=self::execute_single_query("SELECT * FROM `comentarios` WHERE id='$code'");
			$rowsval=$queryval->rowCount();
			if ($rowsval==0) {
				# code...
			

			
				$query1=self::execute_single_query("SELECT Adjuntos FROM clase WHERE id='$code'");
				$rows=$query1->fetch();

				$query2=self::execute_single_query("SELECT Adjunto FROM comentarios WHERE id='$code'");
				$rows2=$query2->fetch();

				if(self::delete_video_model($code) && self::execute_single_query("DELETE FROM comentarios WHERE id='$code'")){

					//Delete files from Video
					$filesA=explode(",", $rows['Adjuntos']);
					$AttDir="../attachments/class/";
					foreach ($filesA as $AttClass) {
						if($AttClass!=""){
							chmod($AttDir.$AttClass, 0777);
							unlink($AttDir.$AttClass);
						}
					}
					

					//Delete files from Comments
					$filesA2=explode(",", $rows2['Adjunto']);
					$AttDir2="../attachments/comments/";
					foreach ($filesA2 as $AttClass2) {
						if($AttClass2!=""){
							chmod($AttDir2.$AttClass2, 0777);
							unlink($AttDir2.$AttClass2);
						}
					}


					$dataAlert=[
						"title"=>"¡Clase eliminada!",
						"text"=>"La clase ha sido eliminada del sistema satisfactoriamente",
						"type"=>"success"
					];
					return self::sweet_alert($dataAlert);
				}else{
					$dataAlert=[
						"title"=>"¡Ocurrió un error inesperado!",
						"text"=>"No pudimos eliminar la clase por favor intente nuevamente",
						"type"=>"error"
					];
					return self::sweet_alert_single($dataAlert);
				}
			}else {
				if (self::desactiva_video_model($code)) {
					# code...
					$dataAlert=[
						"title"=>"¡Clase Inhabilitada!",
						"text"=>"La clase ha sido Inhabilitada del sistema satisfactoriamente",
						"type"=>"success"
					];
					return self::sweet_alert($dataAlert);
				}else{
					$dataAlert=[
						"title"=>"¡Ocurrió un error inesperado!",
						"text"=>"No pudimos Inhabilitar la clase por favor intente nuevamente",
						"type"=>"error"
					];
					return self::sweet_alert_single($dataAlert);
				}
			}
		}


		/*----------  Delete Video Attachment Controller  ----------*/
		public function delete_video_attachment_controller($code, $name, $urls){
			$code = self::clean_string($_POST['idAtt']);
			$name = $_POST['nameAtt'];

			$AttDir = "./attachments/class/";
			$filePath = $AttDir . $name;

			// Verificar que el nombre no esté vacío y que sea un archivo
			if (!empty($name) && is_file($filePath)) {
				chmod($filePath, 0777); // Asegura permisos antes de borrar

				if (unlink($filePath)) {
					// Obtener registros de la clase
					$query1 = self::execute_single_query("SELECT * FROM clase WHERE id='$code'");
					$rows = $query1->fetch();
					$filesA = explode(",", $rows['Adjuntos']);

					// Reconstruir la lista de adjuntos sin el archivo eliminado
					$attFinal = "";
					$catt = 0;
					foreach ($filesA as $AttClass) {
						if ($AttClass != $name) {
							$attFinal .= ($catt > 0 ? "," : "") . $AttClass;
							$catt++;
						}
					}

					// Actualizar registro
					if (self::execute_single_query("UPDATE clase SET Adjuntos='$attFinal' WHERE id='$code'")) {
						$dataAlert = [
							"title" => "¡Archivo eliminado!",
							"text" => "El archivo adjunto de la clase fue eliminado con éxito",
							"type" => "success"
						];
						return self::sweet_alert_url_reload($dataAlert, $urls);
					} else {
						$dataAlert = [
							"title" => "¡Ocurrió un error inesperado!",
							"text" => "No pudimos actualizar los datos de la clase. Intente nuevamente.",
							"type" => "error"
						];
						return self::sweet_alert_single($dataAlert);
					}
				} else {
					$dataAlert = [
						"title" => "¡Ocurrió un error inesperado!",
						"text" => "No pudimos eliminar el archivo. Verifique los permisos o intente nuevamente.",
						"type" => "error"
					];
					return self::sweet_alert_single($dataAlert);
				}
			} else {
				$dataAlert = [
					"title" => "Archivo inválido",
					"text" => "No se encontró el archivo especificado o el nombre es incorrecto.",
					"type" => "warning"
				];
				return self::sweet_alert_single($dataAlert);
			}
		}



		/*----------  Data Video Controller  ----------*/
		public function data_video_controller($Type,$Code){
			$Type=self::clean_string($Type);
			$Code=self::clean_string($Code);

			$data=[
				"Tipo"=>$Type,
				"id"=>$Code
			];

			if($videodata=self::data_video_model($data)){
				return $videodata;
			}else{
				$dataAlert=[
					"title"=>"¡Ocurrió un error inesperado!",
					"text"=>"No hemos podido seleccionar los datos de la clase",
					"type"=>"error"
				];
				return self::sweet_alert_single($dataAlert);
			}
		}


		/*----------  Update Video Controller  ----------*/
		public function update_video_controller(){
			$code=self::clean_string($_POST['upid']);
			$title=self::clean_string($_POST['title']);
			$teacher=self::clean_string($_POST['teacher']);
			$date=self::clean_string($_POST['date']);
			$cursos_select=self::clean_string($_POST['cursos_select']);
			$horas=self::clean_string($_POST['horas']);
			$video=$_POST['upcode'];
			$description=$_POST['description'];

			$AttMaxSize=5120;
			$AttDir="../attachments/class/";

			$ATC=0;
			$AttFinalName="";
			$AttFinalNameTMP="";

			foreach($_FILES["attachments"]['tmp_name'] as $key => $tmp_name){
				if($_FILES["attachments"]["name"][$key]){

					$AttType=$_FILES['attachments']['type'][$key];
					$AttSize=$_FILES['attachments']['size'][$key];

					if($AttType=="image/jpeg" || $AttType=="image/png" || $AttType=="application/msword" || $AttType=="application/vnd.ms-powerpoint" || $AttType=="application/pdf" || $AttType=="application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $AttType=="application/vnd.openxmlformats-officedocument.presentationml.presentation"){
						if(($AttSize/1024)<=$AttMaxSize){

							$AttName = str_ireplace(",", "", $_FILES["attachments"]["name"][$key]);
							$AttName = str_ireplace(" ", "_", $_FILES["attachments"]["name"][$key]);
							$finalDir=$AttDir.$AttName;

							if(is_file($finalDir)){
								if($AttFinalNameTMP!=""){
									$delTMP=explode(",", $AttFinalNameTMP);
									foreach ($delTMP as $delFile) {
										$filesAD=$AttDir.$delFile;
										chmod($filesAD, 0777);
										unlink($filesAD);
									}
								}
								
								$dataAlert1=[
									"title"=>"¡Ocurrió un error inesperado!",
									"text"=>"Ya existe un archivo con el nombre <b>".$AttName."</b> registrado en el sistema por favor cambie el nombre del archivo adjunto antes de subirlo",
									"type"=>"error"
								];
								return self::sweet_alert_single($dataAlert1);
								exit();
							}else{
								chmod($AttDir, 0777);

								if(move_uploaded_file($_FILES["attachments"]['tmp_name'][$key], $finalDir)){
									if($ATC==0){
										$AttFinalName.=$AttName;
										$AttFinalNameTMP.=$AttName;
										$ATC++;
									}else{
										$AttFinalName.=",".$AttName;
										$AttFinalNameTMP.=$AttName;
									}	
								}else{	
									$dataAlert=[
										"title"=>"¡Ocurrió un error inesperado!",
										"text"=>"No se pudo cargar uno o más de los archivos adjuntos seleccionados",
										"type"=>"error"
									];
									return self::sweet_alert_single($dataAlert);
									exit();
								}
							}
						}else{
							$dataAlert=[
								"title"=>"¡Ocurrió un error inesperado!",
								"text"=>"El tamaño de uno de los archivos supera el límite de peso máximo que son 5MB",
								"type"=>"error"
							];
							return self::sweet_alert_single($dataAlert);
							exit();
						}
					}else{
						$dataAlert=[
							"title"=>"¡Ocurrió un error inesperado!",
							"text"=>"El tipo de formato de uno de los archivo que acaba de seleccionar no esta permitido",
							"type"=>"error"
						];
						return self::sweet_alert_single($dataAlert);
						exit();
					}
				}
			}


			$query1=self::execute_single_query("SELECT Adjuntos FROM clase WHERE id='$code'");
			$rows1=$query1->fetch();

			// if($rows1['Adjuntos']!=""){
			// 	$finalAtts=$rows1['Adjuntos'].",".$AttFinalName;
			// }else{
			// 	$finalAtts=$AttFinalName;
			// }
			
			if (!empty($AttFinalName)) {
				// Si se sube una nueva imagen, se usa la nueva
				$finalAtts=$rows1['Adjuntos'].",".$AttFinalName;
				

			} else {
				// Si no se sube nada, se mantiene la anterior
				$finalAtts = $rows1['Adjuntos'];

			}
	
		
			$data=[
				"id"=>$code,
				"Titulo"=>$title,
				"Fecha"=>$date,
				"Video"=>$video,
				"Tutor"=>$teacher,
				"Descripcion"=>$description,
				"Curso"=>$cursos_select,
				"Horas"=>$horas,
				"Adjuntos"=>$finalAtts
			];

			if(self::update_video_model($data)){
				$dataAlert=[
					"title"=>"¡Clase actualizada!",
					"text"=>"Los datos de la clase fueron actualizados con éxito",
					"type"=>"success"
				];
				return self::sweet_alert($dataAlert);
			}else{
				$dataAlert=[
					"title"=>"¡Ocurrió un error inesperado!",
					"text"=>"No hemos podido actualizar los datos de la clase, por favor intente nuevamente",
					"type"=>"error"
				];
				return self::sweet_alert_single($dataAlert);
			}
		}

	}