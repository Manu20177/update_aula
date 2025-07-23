<?php
	if($actionsRequired){
		require_once "../models/encuestaentrevistaModel.php";
	}else{ 
		require_once "./models/encuestaentrevistaModel.php";
	}

	class encuestaentrevistaController extends encuestaentrevistaModel{

		/*----------  Add Curso Controller  ----------*/
		public function add_encuesta_controller() {
			// Limpiamos la clase seleccionada
			$clase_select=self::clean_string($_POST['clase_select']);


			// Directorio donde se guardarán los archivos CSV
			$csvDir = "../archivos/encuesta/";
			
			// Variables para el archivo CSV
			$csvFinalName = "";
			$csvFinalNameTMP = "";

			// Validación del archivo CSV
			if (!empty($_FILES["csv_file"]["name"])) {
				$csvTmpName = $_FILES["csv_file"]["tmp_name"];
				$csvName = $_FILES["csv_file"]["name"];
				$csvType = $_FILES["csv_file"]["type"];
				$csvSize = $_FILES["csv_file"]["size"];

				// Tipos permitidos
				$allowedTypes = ['text/csv', 'application/vnd.ms-excel', 'text/plain'];

				// Tamaño máximo (5MB)
				$maxSize = 5 * 1024 * 1024;

				if (!in_array($csvType, $allowedTypes)) {
					$dataAlert = [
						"title" => "¡Formato no permitido!",
						"text" => "El archivo debe ser un CSV válido",
						"type" => "error"
					];
					return self::sweet_alert_single($dataAlert);
				}

				if ($csvSize > $maxSize) {
					$dataAlert = [
						"title" => "¡Tamaño excedido!",
						"text" => "El archivo no debe superar los 5MB",
						"type" => "error"
					];
					return self::sweet_alert_single($dataAlert);
				}

				
				// Verificar si ya existe
				// Limpiar nombre del archivo
				$csvName = str_ireplace(" ", "_", $csvName);
				$csvName = pathinfo($csvName, PATHINFO_FILENAME); // Quitar extensión
				$extension = ".csv";
				$newCsvName = $csvName . $extension;
				$finalCsvPath = $csvDir . $newCsvName;
				$counter = 1;

				// Verificar si ya existe y generar nuevo nombre si es necesario
				while (file_exists($finalCsvPath)) {
					$newCsvName = $csvName . "_" . $counter . $extension;
					$finalCsvPath = $csvDir . $newCsvName;
					$counter++;
				}

								// Mover archivo al directorio
				if (!move_uploaded_file($csvTmpName, $finalCsvPath)) {
					$dataAlert = [
						"title" => "¡Error al cargar el archivo!",
						"text" => "No se pudo guardar el archivo CSV",
						"type" => "error"
					];
					return self::sweet_alert_single($dataAlert);
				}

				// Guardar nombre del archivo para guardarlo en BD
				$csvFinalName = $newCsvName;
				$csvFinalNameTMP = $newCsvName;

				// Abrir y leer el CSV
				$file = fopen($finalCsvPath, "r");

				// Leer la primera línea y eliminar BOM si existe
				$header = fgetcsv($file);
				$header = array_map(function($value) {
					// Elimina BOM UTF-8 si está presente
					$value = preg_replace('/^\xEF\xBB\xBF/', '', $value);
					return trim(mb_convert_encoding($value, 'UTF-8', 'auto'));
				}, $header);

				// Normalizar encabezados: minúsculas y sin acentos
				$header_normalized = array_map(function($item) {
					return mb_strtolower(strtr($item, [
						'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
						'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u',
						'ñ' => 'n', 'Ñ' => 'n'
					]));
				}, $header);

				$expected_header = ['pregunta', 'tipo', 'opcion1', 'opcion2', 'opcion3'];

				if ($header_normalized !== $expected_header) {
					fclose($file);
					unlink($finalCsvPath); // Eliminar archivo incorrecto
					$dataAlert = [
						"title" => "¡Formato del CSV inválido!",
						"text" => "La estructura del CSV no es la esperada. Debe tener las columnas: Pregunta, Tipo, Opción1, Opción2, Opción3",
						"type" => "error"
					];
					return self::sweet_alert_single($dataAlert);
				}
				// Leer filas
				while (($row = fgetcsv($file)) !== FALSE) {
					$pregunta = self::clean_string($row[0]);
					$tipo = self::clean_string($row[1]);
					$opcion1 = self::clean_string($row[2]);
					$opcion2 = self::clean_string($row[3]);
					$opcion3 = self::clean_string($row[4]);

					// Aquí puedes insertar cada fila en la base de datos
					// Ejemplo:
					self::save_encuesta_model([
					    "id_clase" => $clase_select,
					    "pregunta" => $pregunta,
					    "tipo" => $tipo,
					    "opcion1" => $opcion1,
					    "opcion2" => $opcion2,
					    "opcion3" => $opcion3
					]);
					
				}
				$dataAlert = [
					"title" => "¡Encuesta registrada!",
					"text" => "La encuesta se registró con éxito en el sistema",
					"type" => "success"
				];

				fclose($file);
				$url="encuestaadd/";
				return self::sweet_alert_url_reload($dataAlert,$url);


			} else {
				$dataAlert = [
					"title" => "¡Falta el archivo CSV!",
					"text" => "Debe cargar un archivo CSV con las preguntas",
					"type" => "error"
				];
				return self::sweet_alert_single($dataAlert);
			}

		
			
		}

		public function add_evaluacion_controller() {
			// Limpiamos la clase seleccionada
			$clase_select=self::clean_string($_POST['clase_select']);


			// Directorio donde se guardarán los archivos CSV
			$csvDir = "../archivos/evaluacion/";
			
			// Variables para el archivo CSV
			$csvFinalName = "";
			$csvFinalNameTMP = "";

			// Validación del archivo CSV
			if (!empty($_FILES["csv_file"]["name"])) {
				$csvTmpName = $_FILES["csv_file"]["tmp_name"];
				$csvName = $_FILES["csv_file"]["name"];
				$csvType = $_FILES["csv_file"]["type"];
				$csvSize = $_FILES["csv_file"]["size"];

				// Tipos permitidos
				$allowedTypes = ['text/csv', 'application/vnd.ms-excel', 'text/plain'];

				// Tamaño máximo (5MB)
				$maxSize = 5 * 1024 * 1024;

				if (!in_array($csvType, $allowedTypes)) {
					$dataAlert = [
						"title" => "¡Formato no permitido!",
						"text" => "El archivo debe ser un CSV válido",
						"type" => "error"
					];
					return self::sweet_alert_single($dataAlert);
				}

				if ($csvSize > $maxSize) {
					$dataAlert = [
						"title" => "¡Tamaño excedido!",
						"text" => "El archivo no debe superar los 5MB",
						"type" => "error"
					];
					return self::sweet_alert_single($dataAlert);
				}

				
				// Verificar si ya existe
				// Limpiar nombre del archivo
				$csvName = str_ireplace(" ", "_", $csvName);
				$csvName = pathinfo($csvName, PATHINFO_FILENAME); // Quitar extensión
				$extension = ".csv";
				$newCsvName = $csvName . $extension;
				$finalCsvPath = $csvDir . $newCsvName;
				$counter = 1;

				// Verificar si ya existe y generar nuevo nombre si es necesario
				while (file_exists($finalCsvPath)) {
					$newCsvName = $csvName . "_" . $counter . $extension;
					$finalCsvPath = $csvDir . $newCsvName;
					$counter++;
				}

								// Mover archivo al directorio
				if (!move_uploaded_file($csvTmpName, $finalCsvPath)) {
					$dataAlert = [
						"title" => "¡Error al cargar el archivo!",
						"text" => "No se pudo guardar el archivo CSV",
						"type" => "error"
					];
					return self::sweet_alert_single($dataAlert);
				}

				// Guardar nombre del archivo para guardarlo en BD
				$csvFinalName = $newCsvName;
				$csvFinalNameTMP = $newCsvName;

				// Abrir y leer el CSV
				$file = fopen($finalCsvPath, "r");

				// Leer la primera línea y eliminar BOM si existe
				$header = fgetcsv($file);
				$header = array_map(function($value) {
					// Elimina BOM UTF-8 si está presente
					$value = preg_replace('/^\xEF\xBB\xBF/', '', $value);
					return trim(mb_convert_encoding($value, 'UTF-8', 'auto'));
				}, $header);

				// Normalizar encabezados: minúsculas y sin acentos
				$header_normalized = array_map(function($item) {
					return mb_strtolower(strtr($item, [
						'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
						'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u',
						'ñ' => 'n', 'Ñ' => 'n'
					]));
				}, $header);

				$expected_header = ['pregunta', 'tipo', 'opcion1', 'opcion2', 'opcion3', 'respuesta'];

				if ($header_normalized !== $expected_header) {
					fclose($file);
					unlink($finalCsvPath); // Eliminar archivo incorrecto
					$dataAlert = [
						"title" => "¡Formato del CSV inválido!",
						"text" => "La estructura del CSV no es la esperada. Debe tener las columnas: Pregunta, Tipo, Opción1, Opción2, Opción3, Respuesta",
						"type" => "error"
					];
					return self::sweet_alert_single($dataAlert);
				}
				// Leer filas
				while (($row = fgetcsv($file)) !== FALSE) {
					$pregunta = self::clean_string($row[0]);
					$tipo = self::clean_string($row[1]);
					$opcion1 = self::clean_string($row[2]);
					$opcion2 = self::clean_string($row[3]);
					$opcion3 = self::clean_string($row[4]);
					$respuesta = self::clean_string($row[5]);

					// Aquí puedes insertar cada fila en la base de datos
					// Ejemplo:
					self::save_evaluacion_model([
					    "id_clase" => $clase_select,
					    "pregunta" => $pregunta,
					    "tipo" => $tipo,
					    "opcion1" => $opcion1,
					    "opcion2" => $opcion2,
					    "opcion3" => $opcion3,
					    "respuesta" => $respuesta
					]);
					
				}
				$dataAlert = [
					"title" => "Evaluacion registrada!",
					"text" => "La evaluacion se registró con éxito en el sistema",
					"type" => "success"
				];

				fclose($file);
				$url="evaluacionadd/";
				return self::sweet_alert_url_reload($dataAlert,$url);


			} else {
				$dataAlert = [
					"title" => "¡Falta el archivo CSV!",
					"text" => "Debe cargar un archivo CSV con las preguntas",
					"type" => "error"
				];
				return self::sweet_alert_single($dataAlert);
			}

		
			
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



		/*----------  Update Curso Controller  ----------*/
		public function update_curso_controller(){
			$code=self::clean_string($_POST['upid']);
			$title=self::clean_string($_POST['title']);
			$date=self::clean_string($_POST['date']);
			$estado=self::clean_string($_POST['estado']);

		
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
				"Portada"=>$finalAttsp
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