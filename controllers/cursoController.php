<?php
	if($actionsRequired){
		require_once "../models/cursoModel.php";
	}else{ 
		require_once "./models/cursoModel.php";
	}

	class cursoController extends cursoModel{

        
		/*----------  Add Curso Controller  ----------*/
		public function add_curso_controller(){
			$title=self::clean_string($_POST['title']);
			$date=self::clean_string($_POST['date']);
			$norma=self::clean_string($_POST['norma']);

			$AttMaxSizeP=5120;
			$PortadaDir="../attachments/class_portada/";
			$PATC=0;
			$PAttFinalName="";
			$PAttFinalNameTMP="";

			foreach($_FILES["portada"]['tmp_name'] as $key => $tmp_name1){
				if($_FILES["portada"]["name"][$key]){

					$AttTypeP=$_FILES['portada']['type'][$key];
					$AttSizeP=$_FILES['portada']['size'][$key];

					if($AttTypeP=="image/jpg" || $AttTypeP=="image/jpeg" || $AttTypeP=="image/png" ){
						if(($AttSizeP/1024)<=$AttMaxSizeP){

							$AttNameP = str_ireplace(",", "", $_FILES["portada"]["name"][$key]);
							$AttNameP = str_ireplace(" ", "_", $_FILES["portada"]["name"][$key]);
							$finalDirP=$PortadaDir.$AttNameP;

							if(is_file($finalDirP)){
								if($PAttFinalNameTMP!=""){
									$delTMPP=explode(",", $PAttFinalNameTMP);
									foreach ($delTMPP as $delFileP) {
										$filesADP=$PortadaDir.$delFileP;
										chmod($filesADP, 0777);
										unlink($filesADP);
									}
								}
								$dataAlertP=[
									"title"=>"¡Ocurrió un error inesperado!",
									"text"=>"Ya existe un archivo con el nombre <b>".$AttNameP."</b> registrado en el sistema por favor cambie el nombre del archivo adjunto antes de subirlo",
									"type"=>"error"
								];
								return self::sweet_alert_single($dataAlertP);
								exit();
							}else{
								chmod($PortadaDir, 0777);

								if(move_uploaded_file($_FILES["portada"]['tmp_name'][$key], $finalDirP)){
									if($PATC==0){
										$PAttFinalName.=$AttNameP;
										$PAttFinalNameTMP.=$AttNameP;
										$PATC++;
									}else{
										$PAttFinalName.=",".$AttNameP;
										$PAttFinalNameTMP.=$AttNameP;
									}	
								}else{	
									$dataAlertP=[
										"title"=>"¡Ocurrió un error inesperado!",
										"text"=>"No se pudo cargar uno o más de los archivos adjuntos seleccionados",
										"type"=>"error"
									];
									return self::sweet_alert_single($dataAlertP);
									exit();
								}
							}
						}else{
							$dataAlertP=[
								"title"=>"¡Ocurrió un error inesperado!",
								"text"=>"El tamaño de uno de los archivos supera el límite de peso máximo que son 5MB",
								"type"=>"error"
							];
							return self::sweet_alert_single($dataAlertP);
							exit();
						}
					}else{
						$dataAlertP=[
							"title"=>"¡Ocurrió un error inesperado!",
							"text"=>"El tipo de formato de uno de los archivo que acaba de seleccionar no esta permitido",
							"type"=>"error"
						];
						return self::sweet_alert_single($dataAlertP);
						exit();
					}
				}
			}

			$query1=self::execute_single_query("SELECT Titulo FROM curso WHERE Titulo='$title'");

			if($query1->rowCount()>=1){
				$dataAlert=[
					"title"=>"¡Ocurrió un error inesperado!",
					"text"=>"El título del Curso ya se encuentra registrado por favor elija otro",
					"type"=>"error"
				];
				return self::sweet_alert_single($dataAlert);
			}else{
				$data=[
					"Fecha"=>$date,
					"Titulo"=>$title,
					"Portada"=>$PAttFinalName,
					"Norma"=>$norma
				];
				if(self::add_curso_model($data)){
					$dataAlert=[
						"title"=>"¡Curso registrado!",
						"text"=>"El curso se registró con éxito en el sistema",
						"type"=>"success"
					];
					return self::sweet_alert_reset($dataAlert);
				}else{
					
					if($PAttFinalName!=""){
						$filesAP=explode(",", $PAttFinalName);
						foreach ($filesAP as $files) {
							chmod($PortadaDir.$files, 0777);
							unlink($PortadaDir.$files);	
						}
					}
					
					$dataAlert=[
						"title"=>"¡Ocurrió un error inesperado!",
						"text"=>"No hemos podido registrar el Curso, por favor intente nuevamente",
						"type"=>"error"
					];
					return self::sweet_alert_single($dataAlert);
				}
			}
		}

		

		/*----------  Pagination Curso Controller  ----------*/
    	public function pagination_curso_controller(){
    
            $Datos = self::execute_single_query("
                SELECT * FROM curso c 
                LEFT JOIN estado_curso ec ON ec.id_estado = c.Estado 
                LEFT JOIN tipo_curso tc ON tc.id_tipoc = c.Norma 
                ORDER BY Fecha DESC;
            ");
            $Datos = $Datos->fetchAll();
        
            // Script de JS para copiar al portapapeles
            $scriptJS = <<<HTML
            <script>
            function copiarLink(idCurso) {
                const urlBase = "https://aulaccf.creditofamiliar.com.ec/cursoclave/";
                const enlace = urlBase + idCurso;
                navigator.clipboard.writeText(enlace).then(() => {
                    alert("✅ Enlace copiado: " + enlace);
                }).catch(err => {
                    alert("❌ Error al copiar el enlace.");
                });
            }
            </script>
            HTML;
        
            $table='
            <!-- Campo de filtro por Norma -->
            <table id="tabla-global" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Fecha</th>
                        <th class="text-center">Titulo</th>
                        <th class="text-center">Clave de Acceso</th>
                        <th class="text-center">Norma</th>
                        <th class="text-center">Ver</th>
                        <th class="text-center">Actualizar</th>
                        <th class="text-center">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
            ';
            $cont = 1;
        
            foreach($Datos as $rows){
                $table.='
                <tr>
                    <td>'.$cont.'</td>
                    <td>'.date("d/m/Y", strtotime($rows['Fecha'])).'</td>
                    <td>'.$rows['Titulo'].'</td>
                    <td class="text-center">
                        '.$rows['id_curso'].'
                       <button class="btn btn-link" onclick="copiarLink(\''.$rows['id_curso'].'\')" title="Copiar enlace">
                            <i class="zmdi zmdi-copy" style="font-size: 20px;"></i>
                        </button>
                    </td>
                    <td>'.$rows['norma'].'</td>
                    <td>
                        <a href="'.SERVERURL.'cursoview/'.$rows['id_curso'].'/" class="btn btn-info btn-raised btn-xs">
                            <i class="zmdi zmdi-tv"></i>
                        </a>
                    </td>
                    <td>
                        <a href="'.SERVERURL.'cursoinfo/'.$rows['id_curso'].'/" class="btn btn-success btn-raised btn-xs">
                            <i class="zmdi zmdi-refresh"></i>
                        </a>
                    </td>
                    <td>
                        <form action="'.SERVERURL.'ajax/ajaxCurso.php" method="POST" enctype="multipart/form-data" autocomplete="off" data-form="DellCurso" class="ajaxDataForm">
                            <input type="hidden" name="cursoCode" value="'.$rows['id_curso'].'">
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
        
            // Añadimos el script al final
            return $table . $scriptJS;
        }

	public function pagination_curso_card_controller($Pagina, $Registros, $Busqueda = ""){
			$Pagina = self::clean_string($Pagina);
			$Registros = self::clean_string($Registros);
			$Busqueda = self::clean_string($Busqueda);

			$Pagina = ($Pagina > 0) ? floor($Pagina) : 1;
			$Inicio = ($Pagina > 0) ? (($Pagina * $Registros) - $Registros) : 0;

			$CondicionBusqueda = "";
			if (!empty($Busqueda)) {
				$CondicionBusqueda = " AND (c.Titulo LIKE '%$Busqueda%' OR c.Fecha LIKE '%$Busqueda%') ";
			}

			// Consulta principal
			$Datos = self::execute_single_query("
				SELECT DISTINCT c.*,tc.norma FROM curso c 
				LEFT JOIN curso_clase cc ON cc.id_curso = c.id_curso 
				LEFT JOIN tipo_curso tc on tc.id_tipoc=c.Norma WHERE 1=1
				$CondicionBusqueda
				ORDER BY c.Fecha DESC
			");
			$Datos = $Datos->fetchAll();
			$id_alumno = $_SESSION['userKey'];

			// Conteo total de registros
			$TotalQuery = "
				SELECT COUNT(*) as Total FROM curso c 
				LEFT JOIN curso_clase cc ON cc.id_curso = c.id_curso 
				WHERE 1=1
				$CondicionBusqueda
			";
			$TotalResult = self::execute_single_query($TotalQuery);
			$TotalRow = $TotalResult->fetch(PDO::FETCH_ASSOC);
			$Total = $TotalRow['Total'];
			$Npaginas = ceil($Total / $Registros);

			// Generar tarjetas
			$cards = '<div class="row">';
			$contador = $Inicio + 1;

			foreach ($Datos as $rows) {
				$rutaPortadas = '/aula/attachments/class_portada/';
				$nombreArchivo = $rows['Portada'];
				$imagenMostrar = !empty($nombreArchivo) ? $rutaPortadas . $nombreArchivo : $rutaPortadas . 'sin_portada.jpg';

				$idcursodis = $rows["id_curso"];
				$curso_ins = self::execute_single_query("SELECT * FROM curso_alumno WHERE id_curso='$idcursodis' AND id_alumno='$id_alumno'");
				$estado = "No Inscrito";
				$color = "red";

				if ($curso_ins->rowCount() > 0) {
					$Datoscuro = $curso_ins->fetchAll();
					foreach ($Datoscuro as $rows1) {
						$estado = $rows1['estado_curso'] == 1 ? "COMPLETADO" : "Inscrito";
					}
					$color = "green";
				}
				// <h5 class="card-title">Curso ' . $contador . ': ' . $rows["Titulo"] . '</h5>
				// <input type="hidden" name="titulo" value="Curso ' . $contador . ': ' . $rows["Titulo"] . '">



				$cards .= '
				<div class="col-md-4 mb-4">
					<form method="POST" action="' . SERVERURL . 'verificarcurso">
						<div class="card h-100 shadow-sm cursor-pointer card-click" onclick="this.closest(\'form\').submit();">
							<div class="card-body">
								<img src="' . $imagenMostrar . '" class="card-img-top" alt="Portada del curso">
							</div>
							<div class="card-footer text-center">
								<h5 class="card-title">Curso: ' . $rows["Titulo"] . '</h5>
								<p class="card-text"><strong>Fecha:</strong> ' . $rows["Fecha"] . '</p>
								<p class="card-text"><strong>Norma:</strong> ' . $rows["norma"] . '</p>
								<span style="color:' . $color . '">' . $estado . '</span>
							</div>
							<input type="hidden" name="portada" value="' . $imagenMostrar . '">
							<input type="hidden" name="titulo" value="Curso: ' . $rows["Titulo"] . '">
							<input type="hidden" name="fecha" value="' . $rows["Fecha"] . '">
							<input type="hidden" name="id_alumno" value="' . $id_alumno . '">
							<input type="hidden" name="cod" value="' . $idcursodis . '">
						</div>
					</form>
				</div>';

				$contador++;
			}

			$cards .= '</div>';

			return $cards;
		}



		/*----------  Pagination Curso Search Controller  ----------*/
		public function pagination_curso_search_controller($Pagina,$Registros,$search){
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


		/*----------  Pagination Curso List Controller  ----------*/
		public function pagination_curso_list_controller($Pagina,$Registros){
			$Pagina=self::clean_string($Pagina);
			$Registros=self::clean_string($Registros);

			$Pagina = (isset($Pagina) && $Pagina>0) ? floor($Pagina) : 1;

			$Inicio = ($Pagina>0) ? (($Pagina * $Registros)-$Registros) : 0;

			$Datos=self::execute_single_query("
				SELECT * FROM clase ORDER BY Fecha DESC LIMIT $Inicio,$Registros
			");
			$Datos=$Datos->fetchAll();

			$Total=self::execute_single_query("SELECT * FROM clase");
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
					$table.='<li><a href="'.SERVERURL.'videolist/'.($Pagina-1).'/">«</a></li>';
				}

				for($i=1; $i <= $Npaginas; $i++){
					if($Pagina == $i){
						$table.='<li class="active"><a href="'.SERVERURL.'videolist/'.$i.'/">'.$i.'</a></li>';
					}else{
						$table.='<li><a href="'.SERVERURL.'videolist/'.$i.'/">'.$i.'</a></li>';
					}
				}

				if($Pagina==$Npaginas){
					$table.='<li class="disabled"><a>»</a></li>';
				}else{
					$table.='<li><a href="'.SERVERURL.'videolist/'.($Pagina+1).'/">»</a></li>';
				}

				$table.='
						</ul>
					</nav>
				';
			}

			return $table;
		}


		/*----------  Delete Curso Controller  ----------*/
		public function delete_curso_controller($code){
			$code=self::clean_string($code);

			$queryval=self::execute_single_query("SELECT * FROM `curso_clase` WHERE `id_curso`='$code'");
			$rowsval=$queryval->rowCount();
			if ($rowsval==0) {
				# code...
			
				$query1=self::execute_single_query("SELECT Portada FROM curso WHERE id_curso='$code'");
				$rows=$query1->fetch();
			

				if(self::delete_curso_model($code)){

					//Delete files from Portada
					$filesAp=explode(",", $rows['Portada']);
					$AttDirp="../attachments/class_portada/";
					foreach ($filesAp as $AttClassp) {
						if($AttClassp!=""){
							chmod($AttDirp.$AttClassp, 0777);
							unlink($AttDirp.$AttClassp);
						}
					}


					$dataAlert=[
						"title"=>"Curso eliminado!",
						"text"=>"El Curso ha sido eliminado del sistema satisfactoriamente",
						"type"=>"success"
					];
					return self::sweet_alert($dataAlert);
				}else{
					$dataAlert=[
						"title"=>"¡Ocurrió un error inesperado!",
						"text"=>"No pudimos eliminar el curso por favor intente nuevamente",
						"type"=>"error"
					];
					return self::sweet_alert_single($dataAlert);
				}
			}else {
				if (self::desactiva_video_model($code)) {
					# code...
					$dataAlert=[
						"title"=>"Curso Inhabilitado!",
						"text"=>"El Curso ha sido Inhabilitado del sistema satisfactoriamente",
						"type"=>"success"
					];
					return self::sweet_alert($dataAlert);
				}else{
					$dataAlert=[
						"title"=>"¡Ocurrió un error inesperado!",
						"text"=>"No pudimos Inhabilitar el Curso por favor intente nuevamente",
						"type"=>"error"
					];
					return self::sweet_alert_single($dataAlert);
				}
			}
		}


		/*----------  Data Curso Controller  ----------*/
		public function data_curso_controller($Type,$Code){
			$Type=self::clean_string($Type);
			$Code=self::clean_string($Code);

			$data=[
				"Tipo"=>$Type,
				"id"=>$Code
			];

			if($videodata=self::data_curso_model($data)){
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


		/*----------  Update Curso Controller  ----------*/
		public function update_curso_controller(){
			$code=self::clean_string($_POST['upid']);
			$title=self::clean_string($_POST['title']);
			$date=self::clean_string($_POST['date']);
			$estado=self::clean_string($_POST['estado']);
			$norma=self::clean_string($_POST['norma']);

		
			$AttMaxSizep=5120;
			$AttDirp="../attachments/class_portada/";

			$ATCp=0;
			$AttFinalNamep="";
			$AttFinalNameTMPp="";
			foreach($_FILES["portada1"]['tmp_name'] as $key => $tmp_name){
				if($_FILES["portada1"]["name"][$key]){

					$AttTypep=$_FILES['portada1']['type'][$key];
					$AttSizep=$_FILES['portada1']['size'][$key];

					if($AttTypep=="image/jpeg" || $AttTypep=="image/jpg" || $AttTypep=="image/png" ){
						if(($AttSizep/1024)<=$AttMaxSizep){

							$AttNamep = str_ireplace(",", "", $_FILES["portada1"]["name"][$key]);
							$AttNamep = str_ireplace(" ", "_", $_FILES["portada1"]["name"][$key]);
							$finalDirp=$AttDirp.$AttNamep;

							if(is_file($finalDirp)){
								if($AttFinalNameTMPp!=""){
									$delTMPp=explode(",", $AttFinalNameTMPp);
									foreach ($delTMPp as $delFilep) {
										$filesADp=$AttDirp.$delFilep;
										chmod($filesADp, 0777);
										unlink($filesADp);
									}
									
								}
								
								$dataAlertp=[
									"title"=>"¡Ocurrió un error inesperado!",
									"text"=>"Ya existe un archivo con el nombre <b>".$AttNamep."</b> registrado en el sistema por favor cambie el nombre del archivo adjunto antes de subirlo",
									"type"=>"error"
								];
								return self::sweet_alert_single($dataAlertp);
								exit();
							}else{
								chmod($AttDirp, 0777);

								if(move_uploaded_file($_FILES["portada1"]['tmp_name'][$key], $finalDirp)){
									if($ATCp==0){
										$AttFinalNamep.=$AttNamep;
										$AttFinalNameTMPp.=$AttNamep;
										$ATCp++;
									}else{
										$AttFinalNamep.=",".$AttNamep;
										$AttFinalNameTMPp.=$AttNamep;
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

			
			$query1p=self::execute_single_query("SELECT Portada FROM curso WHERE id_curso='$code'");
			$rows1p=$query1p->fetch();

			
			if (!empty($AttFinalNamep)) {
				// Si se sube una nueva imagen, se usa la nueva
				$finalAttsp = $AttFinalNamep;
				//Delete files from curso
				$filesA1=explode(",", $rows1p['Portada']);
				$AttDir1="../attachments/class_portada/";
				foreach ($filesA1 as $AttClass1) {
					if($AttClass1!=""){
						chmod($AttDirp.$AttClass1, 0777);
						unlink($AttDirp.$AttClass1);
					}
				}

			} else {
				// Si no se sube nada, se mantiene la anterior
				$finalAttsp = $rows1p['Portada'];
			}

			$data=[
				"id"=>$code,
				"Titulo"=>$title,
				"Fecha"=>$date,
				"Estado"=>$estado,
				"Portada"=>$finalAttsp,
				"Norma"=>$norma
			];

			if(self::update_curso_model($data)){
				$dataAlert=[
					"title"=>"Curso actualizada!",
					"text"=>"Los datos del Curso fueron actualizados con éxito",
					"type"=>"success"
				];
				return self::sweet_alert($dataAlert);
			}else{
				$dataAlert=[
					"title"=>"¡Ocurrió un error inesperado!",
					"text"=>"No hemos podido actualizar los datos del Curso, por favor intente nuevamente",
					"type"=>"error"
				];
				return self::sweet_alert_single($dataAlert);
			}
		}


	}
	?>
