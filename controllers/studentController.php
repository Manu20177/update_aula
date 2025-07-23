<?php
	if($actionsRequired){
		require_once "../models/studentModel.php";
	}else{ 
		require_once "./models/studentModel.php";
	}

	class studentController extends studentModel{

		/*----------  Add Student Controller  ----------*/
		public function add_student_controller(){
			$name=self::clean_string($_POST['name']);
			$lastname=self::clean_string($_POST['lastname']);
			$gender=self::clean_string($_POST['gender']);
			$email=self::clean_string($_POST['email']);
			$cedula=self::clean_string($_POST['cedula']);
			$telefono=self::clean_string($_POST['telefono']);
			$tipousu=self::clean_string($_POST['tipousu']);
			$username=self::clean_string($_POST['username']);
			$password1=self::clean_string($_POST['password1']);
			$password2=self::clean_string($_POST['password2']);
			$nivel=self::clean_string($_POST['nivel']);
			$provincia=self::clean_string($_POST['provincia']);
			$canton=self::clean_string($_POST['canton']);
			$parroquia=self::clean_string($_POST['parroquia']);
			$actividad=self::clean_string($_POST['actividad']);
			$etnia=self::clean_string($_POST['etnia']);

			if($password1!="" || $password2!=""){
				if($password1==$password2){
					$query1=self::execute_single_query("SELECT Usuario FROM cuenta WHERE Usuario='$username'");
					if($query1->rowCount()<=0){
						$query2=self::execute_single_query("SELECT id FROM cuenta");
						$correlative=($query2->rowCount())+1;

						$code=self::generate_code("EC",7,$correlative);
						$password1=self::encryption($password1);

						$dataAccount=[
							"Privilegio"=>4,
							"Usuario"=>$username,
							"Clave"=>$password1,
							"Tipo"=>"Estudiante",
							"Genero"=>$gender,
							"Codigo"=>$code
						];

						$dataStudent=[
							"Codigo"=>$code,
							"Nombres"=>$name,
							"Apellidos"=>$lastname,
							"Email"=>$email,
							"Cedula"=>$cedula,
							"Telefono"=>$telefono,
							"Tipousu"=>$tipousu,
							"Nivel"=>$nivel,
							"Provincia"=>$provincia,
							"Canton"=>$canton,
							"Parroquia"=>$parroquia,
							"Actividad"=>$actividad,
							"Etnia"=>$etnia
							
						];

						if(self::save_account($dataAccount) && self::add_student_model($dataStudent)){
							$dataAlert=[
								"title"=>"¡Estudiante registrado!",
								"text"=>"El estudiante se registró con éxito en el sistema",
								"type"=>"success"
							];
							unset($_POST);
							return self::sweet_alert_single($dataAlert);
						}else{
							$dataAlert=[
								"title"=>"¡Ocurrió un error inesperado!",
								"text"=>"No hemos podido registrar el estudiante, por favor intente nuevamente",
								"type"=>"error"
							];
							return self::sweet_alert_single($dataAlert);
						}

					}else{
						$dataAlert=[
							"title"=>"¡Ocurrió un error inesperado!",
							"text"=>"El nombre de usuario que acaba de ingresar ya se encuentra registrado en el sistema, por favor elija otro",
							"type"=>"error"
						];
						return self::sweet_alert_single($dataAlert);
					}
				}else{
					$dataAlert=[
						"title"=>"¡Ocurrió un error inesperado!",
						"text"=>"Las contraseñas que acabas de ingresar no coinciden",
						"type"=>"error"
					];
					return self::sweet_alert_single($dataAlert);
				}
			}else{
				$dataAlert=[
					"title"=>"¡Ocurrió un error inesperado!",
					"text"=>"Debes de llenar los campos de las contraseñas para registrar el estudiante",
					"type"=>"error"
				];
				return self::sweet_alert_single($dataAlert);
			}
		}
		/*----------  Add Student Controller  ----------*/
		public function add_acountstudent_controller(){
			$name=self::clean_string($_POST['name']);
			$lastname=self::clean_string($_POST['lastname']);
			$gender=self::clean_string($_POST['gender']);
			$email=self::clean_string($_POST['email']);
			$cedula=self::clean_string($_POST['cedula']);
			$telefono=self::clean_string($_POST['telefono']);
			$tipousu=self::clean_string($_POST['tipousu']);
			$username=self::clean_string($_POST['username']);
			$password1=self::clean_string($_POST['password1']);
			$password2=self::clean_string($_POST['password2']);
			$nivel=self::clean_string($_POST['nivel']);
			$provincia=self::clean_string($_POST['provincia']);
			$canton=self::clean_string($_POST['canton']);
			$parroquia=self::clean_string($_POST['parroquia']);
			$actividad=self::clean_string($_POST['actividad']);
			$etnia=self::clean_string($_POST['etnia']);

			if($password1!="" || $password2!=""){
				if($password1==$password2){
					$query1=self::execute_single_query("SELECT Usuario FROM cuenta WHERE Usuario='$username'");
					if($query1->rowCount()<=0){
						$query2=self::execute_single_query("SELECT id FROM cuenta");
						$correlative=($query2->rowCount())+1;

						$code=self::generate_code("EC",7,$correlative);
						$password1=self::encryption($password1);

						$dataAccount=[
							"Privilegio"=>4,
							"Usuario"=>$username,
							"Clave"=>$password1,
							"Tipo"=>"Estudiante",
							"Genero"=>$gender,
							"Codigo"=>$code
						];

						$dataStudent=[
							"Codigo"=>$code,
							"Nombres"=>$name,
							"Apellidos"=>$lastname,
							"Email"=>$email,
							"Cedula"=>$cedula,
							"Telefono"=>$telefono,
							"Tipousu"=>$tipousu,
							"Nivel"=>$nivel,
							"Provincia"=>$provincia,
							"Canton"=>$canton,
							"Parroquia"=>$parroquia,
							"Actividad"=>$actividad,
							"Etnia"=>$etnia
							
						];

						if(self::save_account($dataAccount) && self::add_student_model($dataStudent)){
							$dataAlert=[
								"title"=>"¡Estudiante registrado!",
								"text"=>"El estudiante se registró con éxito en el sistema",
								"type"=>"success"
							];
							unset($_POST);
							$url="../login";
							return self::sweet_alert_url_reload($dataAlert,$url);
						}else{
							$dataAlert=[
								"title"=>"¡Ocurrió un error inesperado!",
								"text"=>"No hemos podido registrar el estudiante, por favor intente nuevamente",
								"type"=>"error"
							];
							return self::sweet_alert_single($dataAlert);
						}

					}else{
						$dataAlert=[
							"title"=>"¡Ocurrió un error inesperado!",
							"text"=>"El nombre de usuario que acaba de ingresar ya se encuentra registrado en el sistema, por favor elija otro",
							"type"=>"error"
						];
						return self::sweet_alert_single($dataAlert);
					}
				}else{
					$dataAlert=[
						"title"=>"¡Ocurrió un error inesperado!",
						"text"=>"Las contraseñas que acabas de ingresar no coinciden",
						"type"=>"error"
					];
					return self::sweet_alert_single($dataAlert);
				}
			}else{
				$dataAlert=[
					"title"=>"¡Ocurrió un error inesperado!",
					"text"=>"Debes de llenar los campos de las contraseñas para registrar el estudiante",
					"type"=>"error"
				];
				return self::sweet_alert_single($dataAlert);
			}
		}



		/*----------  Data Student Controller  ----------*/
		public function data_student_controller($Type,$Code){
			$Type=self::clean_string($Type);
			$Code=self::clean_string($Code);

			$data=[
				"Tipo"=>$Type,
				"Codigo"=>$Code
			];

			if($studentdata=self::data_student_model($data)){
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
		public function pagination_student_controller(){
	
			$Datos=self::execute_single_query("
				SELECT e.*,tu.tipo, p.id_provincia as cod_p, p.nombre as provincia, c.cod_canton as cod_c, c.nombre as canton, pa.cod_parroquia as cod_pa, pa.nombre as parroquia FROM estudiante e 
				LEFT JOIN tipo_usuario tu on tu.id_tipo=e.Tipo 
				LEFT JOIN provincias p on p.id_provincia=e.Provincia
				LEFT JOIN cantones c on c.id_canton=e.Canton
				LEFT JOIN parroquias pa on pa.id_parroquia=e.Parroquia
				ORDER BY Nombres ASC;
			");
			$Datos=$Datos->fetchAll();

			$table='
			<table id="tabla-global" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Cedula</th>
						<th class="text-center">Nombres</th>
						<th class="text-center">Apellidos</th>
						<th class="text-center">Email</th>
						<th class="text-center">Tipo</th>
						<!-- Columnas ocultas -->
						<th style="display:none;">Teléfono</th>
						<th style="display:none;">Nivel</th>
						<th style="display:none;">Cod. Provincia</th>
						<th style="display:none;">Provincia</th>
						<th style="display:none;">Cod. Canton</th>
						<th style="display:none;">Canton</th>
						<th style="display:none;">Cod. Parroquia</th>
						<th style="display:none;">Parroquia</th>
						<th style="display:none;">Actividad</th>
						<th style="display:none;">Etnia</th>
						<th class="text-center">A. Datos</th>
						<th class="text-center">A. Cuenta</th>
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
						<td>'.$rows['Cedula'].'</td>
						<td>'.$rows['Nombres'].'</td>
						<td>'.$rows['Apellidos'].'</td>
						<td>'.$rows['Email'].'</td>
						<td>'.$rows['tipo'].'</td>
						 <!-- Columnas ocultas -->
						<td style="display:none;">' . $rows['Telefono'] . '</td>
						<td style="display:none;">' . $rows['Nivel'] . '</td>
						<td style="display:none;">' . $rows['cod_p'] . '</td>
						<td style="display:none;">' . $rows['provincia'] . '</td>
						<td style="display:none;">' . $rows['cod_c'] . '</td>
						<td style="display:none;">' . $rows['canton'] . '</td>
						<td style="display:none;">' . $rows['cod_pa'] . '</td>
						<td style="display:none;">' . $rows['parroquia'] . '</td>
						<td style="display:none;">' . $rows['Actividad'] . '</td>
						<td style="display:none;">' . $rows['Etnia'] . '</td>
								
						<td>
							<a href="'.SERVERURL.'studentinfo/'.$rows['Codigo'].'/" class="btn btn-success btn-raised btn-xs">
								<i class="zmdi zmdi-refresh"></i>
							</a>
						</td>
						<td>
							<a href="'.SERVERURL.'account/'.$rows['Codigo'].'/" class="btn btn-success btn-raised btn-xs">
								<i class="zmdi zmdi-refresh"></i>
							</a>
						</td>
						<td>
							<a href="#!" class="btn btn-danger btn-raised btn-xs btnFormsAjax" data-action="delete" data-id="del-'.$rows['Codigo'].'">
								<i class="zmdi zmdi-delete"></i>
							</a>
							<form action="" id="del-'.$rows['Codigo'].'" method="POST" enctype="multipart/form-data">
								<input type="hidden" name="studentCode" value="'.$rows['Codigo'].'">
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
		public function delete_student_controller($code){
			$code=self::clean_string($code);

			if(self::delete_account($code) && self::delete_student_model($code)){
				$dataAlert=[
					"title"=>"¡Estudiante eliminado!",
					"text"=>"El estudiante ha sido eliminado del sistema satisfactoriamente",
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
		}


		/*----------  Update Student Controller  ----------*/
		public function update_student_controller(){
			$code=self::clean_string($_POST['code']);
			$name=self::clean_string($_POST['name']);
			$lastname=self::clean_string($_POST['lastname']);
			$email=self::clean_string($_POST['email']);
			$cedula=self::clean_string($_POST['cedula']);
			$telefono=self::clean_string($_POST['telefono']);
			$tipousu=self::clean_string($_POST['tipousu']);
			$nivel=self::clean_string($_POST['nivel']);
			$provincia=self::clean_string($_POST['provincia']);
			$canton=self::clean_string($_POST['canton']);
			$parroquia=self::clean_string($_POST['parroquia']);
			$actividad=self::clean_string($_POST['actividad']);
			$etnia=self::clean_string($_POST['etnia']);

			$data=[
				"Codigo"=>$code,
				"Nombres"=>$name,
				"Apellidos"=>$lastname,
				"Email"=>$email,
				"Cedula"=>$cedula,
				"Telefono"=>$telefono,
				"Tipousu"=>$tipousu,
				"Nivel"=>$nivel,
				"Provincia"=>$provincia,
				"Canton"=>$canton,
				"Parroquia"=>$parroquia,
				"Actividad"=>$actividad,
				"Etnia"=>$etnia
				
			];

			if(self::update_student_model($data)){
				$dataAlert=[
					"title"=>"¡Estudiante actualizado!",
					"text"=>"Los datos del estudiante fueron actualizados con éxito",
					"type"=>"success"
				];
				return self::sweet_alert_single($dataAlert);
			}else{
				$dataAlert=[
					"title"=>"¡Ocurrió un error inesperado!",
					"text"=>"No hemos podido actualizar los datos del estudiante, por favor intente nuevamente",
					"type"=>"error"
				];
				return self::sweet_alert_single($dataAlert);
			}
		}
				/*----------  AJAX Load Locations Controller  ----------*/
		public function load_locations_controller() {
			if (isset($_POST['action'])) {
				switch ($_POST['action']) {
					case 'load_provinces':
						$data = self::get_provinces_model();
						echo "<option value=''>Seleccione una provincia</option>";
						foreach ($data as $prov) {
							echo "<option value='{$prov['id_provincia']}'>{$prov['nombre']}</option>";
						}
						break;

					case 'load_cantons':
						$id_provincia = $_POST['id_provincia'];
						$data = self::get_cantons_by_province_model($id_provincia);
						echo "<option value=''>Seleccione un cantón</option>";
						foreach ($data as $canton) {
							echo "<option value='{$canton['id_canton']}'>{$canton['nombre']}</option>";
						}
						break;

					case 'load_parishes':
						$id_canton = $_POST['id_canton'];
						$data = self::get_parishes_by_canton_model($id_canton);
						echo "<option value=''>Seleccione una parroquia</option>";
						foreach ($data as $parroquia) {
							echo "<option value='{$parroquia['id_parroquia']}'>{$parroquia['nombre']}</option>";
						}
						break;
				}
			}
		}

	}