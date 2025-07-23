<?php
	if($actionsRequired){
		require_once "../models/normasModel.php";
	}else{ 
		require_once "./models/normasModel.php";
	}

	class normasController extends normasModel{

		/*----------  Add Norma Controller  ----------*/
		public function add_norma_controller(){
			$titulo=self::clean_string($_POST['titulo']);
			$descripcion=self::clean_string($_POST['descripcion']);
			
			$dataNorma=[
				"Titulo"=>$titulo,
				"Descripcion"=>$descripcion
				
				
			];

			if(self::add_norma_model($dataNorma)){
				$dataAlert=[
					"title"=>"Norma registrada!",
					"text"=>"La Norma se registró con éxito en el sistema",
					"type"=>"success"
				];
				unset($_POST);
				return self::sweet_alert_single($dataAlert);
			}else{
				$dataAlert=[
					"title"=>"¡Ocurrió un error inesperado!",
					"text"=>"No hemos podido registrar la Norma, por favor intente nuevamente",
					"type"=>"error"
				];
				return self::sweet_alert_single($dataAlert);
			}

		}
	


		/*----------  Data Norma Controller  ----------*/
		public function data_norma_controller($Type,$Code){
			$Type=self::clean_string($Type);
			$Code=self::clean_string($Code);

			$data=[
				"Tipo"=>$Type,
				"Codigo"=>$Code
			];

			if($studentdata=self::data_norma_model($data)){
				return $studentdata;
			}else{
				$dataAlert=[
					"title"=>"¡Ocurrió un error inesperado!",
					"text"=>"No hemos podido seleccionar los datos del estudiante",
					"type"=>"error"
				];
				return self::sweet_alert_single($dataAlert);
			}

		}

		/*----------  Data Student Controller  ----------*/
		public function validarUsuario($student){
			$student=self::clean_string($student);
		
			$Total=self::execute_single_query("SELECT * FROM cuenta WHERE Usuario = '$student'");
			$Total=$Total->rowCount();

			return $Total > 0;

		}



		/*----------  Pagination Student Controller  ----------*/
		public function pagination_normas_controller(){
	
			$Datos=self::execute_single_query("
				SELECT * FROM tipo_curso ORDER BY norma ASC
			");
			$Datos=$Datos->fetchAll();

			$table='
			<table id="tabla-global" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Titulo</th>
						<th class="text-center">Descripcion</th>
						<th class="text-center">A. Datos</th>
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
						<td>'.$rows['norma'].'</td>
						<td>'.$rows['descripcion'].'</td>
						<td>
							<a href="'.SERVERURL.'normasinfo/'.$rows['id_tipoc'].'/" class="btn btn-success btn-raised btn-xs">
								<i class="zmdi zmdi-refresh"></i>
							</a>
						</td>
						
						<td>
							<a href="#!" class="btn btn-danger btn-raised btn-xs btnFormsAjax" data-action="delete" data-id="del-'.$rows['id_tipoc'].'">
								<i class="zmdi zmdi-delete"></i>
							</a>
							<form action="" id="del-'.$rows['id_tipoc'].'" method="POST" enctype="multipart/form-data">
								<input type="hidden" name="normaCode" value="'.$rows['id_tipoc'].'">
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


		/*----------  Delete Student Controller  ----------*/
		public function delete_norma_controller($code){
			$code=self::clean_string($code);

			
			if(self::valida_norma($code)==0){
				if(self::delete_norma_model($code)){
				$dataAlert=[
					"title"=>"Norma eliminada!",
					"text"=>"La Norma ha sido eliminado del sistema satisfactoriamente",
					"type"=>"success"
				];
					return self::sweet_alert_single($dataAlert);
				}else{
					$dataAlert=[
						"title"=>"¡Ocurrió un error inesperado!",
						"text"=>"No pudimos eliminar el estudiante por favor intente nuevamente",
						"type"=>"error"
					];
					return self::sweet_alert_single($dataAlert);
				}
			}else{
				$dataAlert=[
					"title"=>"¡No se pudo Eliminar la Norma!",
					"text"=>"No pudimos eliminar la norma, ya fue asignada a uno o varios cursos",
					"type"=>"error"
				];
				return self::sweet_alert_single($dataAlert);
			}
		}


		/*----------  Update Student Controller  ----------*/
		public function update_norma_controller(){
			$titulo=self::clean_string($_POST['titulo']);
			$descripcion=self::clean_string($_POST['descripcion']);
			$Codigo=self::clean_string($_POST['code']);
	
			$data=[
				"Titulo"=>$titulo,
				"Descripcion"=>$descripcion,				
				"Codigo"=>$Codigo

				
			];

			if(self::update_norma_model($data)){
				$dataAlert=[
					"title"=>"Norma actualizada!",
					"text"=>"Los datos de la Norma fueron actualizados con éxito",
					"type"=>"success"
				];
				return self::sweet_alert_single($dataAlert);
			}else{
				$dataAlert=[
					"title"=>"¡Ocurrió un error inesperado!",
					"text"=>"No hemos podido actualizar los datos de la Norma, por favor intente nuevamente",
					"type"=>"error"
				];
				return self::sweet_alert_single($dataAlert);
			}
		}

	}