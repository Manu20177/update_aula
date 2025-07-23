<?php
	if($actionsRequired){
		require_once "../models/juegosModel.php";
	}else{ 
		require_once "./models/juegosModel.php";
	}

	class juegosController extends juegosModel{
		public function add_juego_controller() {
			$clase_select     = self::clean_string($_POST['clase_select']);
			$titulo_juego     = self::clean_string($_POST['titulo_juego']);
			$descripcion_juego = self::clean_string($_POST['descripcion_juego']);
			$tipo_juego       = self::clean_string($_POST['tipo_juego']);
			$id_juego         = self::clean_string($_POST['id_juego']);

			$csvDir = "../archivos/juegos/";
			$csvFinalName = "";

			if (!empty($_FILES["csv_file"]["name"])) {
				$csvTmpName = $_FILES["csv_file"]["tmp_name"];
				$csvName    = $_FILES["csv_file"]["name"];
				$csvType    = $_FILES["csv_file"]["type"];
				$csvSize    = $_FILES["csv_file"]["size"];

				$allowedTypes = ['text/csv', 'application/vnd.ms-excel', 'text/plain'];
				$maxSize = 5 * 1024 * 1024;

				if (!in_array($csvType, $allowedTypes)) {
					return self::sweet_alert_single([
						"title" => "¡Formato no permitido!",
						"text" => "El archivo debe ser un CSV válido",
						"type" => "error"
					]);
				}

				if ($csvSize > $maxSize) {
					return self::sweet_alert_single([
						"title" => "¡Tamaño excedido!",
						"text" => "El archivo no debe superar los 5MB",
						"type" => "error"
					]);
				}

				// Guardar con nombre único
				$csvName = str_ireplace(" ", "_", pathinfo($csvName, PATHINFO_FILENAME));
				$extension = ".csv";
				$newCsvName = $csvName . $extension;
				$finalCsvPath = $csvDir . $newCsvName;
				$counter = 1;
				while (file_exists($finalCsvPath)) {
					$newCsvName = $csvName . "_" . $counter . $extension;
					$finalCsvPath = $csvDir . $newCsvName;
					$counter++;
				}

				if (!move_uploaded_file($csvTmpName, $finalCsvPath)) {
					return self::sweet_alert_single([
						"title" => "¡Error al cargar el archivo!",
						"text" => "No se pudo guardar el archivo CSV",
						"type" => "error"
					]);
				}

				// Guardar datos generales del juego
				$saveJuego = self::save_juego_model([
					"id_juego"     => $id_juego,
					"titulo"       => $titulo_juego,
					"descripcion"  => $descripcion_juego,
					"tipo"         => $tipo_juego,
					"id_clase"     => $clase_select,
					"archivo_csv"  => $newCsvName
				]);

				if (!$saveJuego) {
					return self::sweet_alert_single([
						"title" => "¡Error al guardar el juego!",
						"text" => "No se pudo registrar la información general",
						"type" => "error"
					]);
				}

				// Leer CSV
				$file = fopen($finalCsvPath, "r");
				if (!$file) {
					return self::sweet_alert_single([
						"title" => "¡Error al abrir el archivo!",
						"text" => "No se pudo leer el archivo CSV.",
						"type" => "error"
					]);
				}

				// Leer y limpiar encabezado
				$header = fgetcsv($file);
				if (isset($header[0])) {
					// Eliminar BOM si existe
					$header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
				}

				// Convertir a UTF-8 y limpiar
				$header = array_map(function($value) {
					return trim(mb_convert_encoding($value, 'UTF-8', 'auto'));
				}, $header);

				$header_normalized = array_map(function($item) {
					return mb_strtolower(strtr($item, [
						'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
						'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u',
						'ñ' => 'n', 'Ñ' => 'n'
					]));
				}, $header);

				$expected_header = ['palabra', 'pista'];
				if ($header_normalized !== $expected_header) {
					fclose($file);
					unlink($finalCsvPath);
					return self::sweet_alert_single([
						"title" => "¡Formato del CSV inválido!",
						"text" => "El archivo debe tener las columnas: Palabra, Pista",
						"type" => "error"
					]);
				}

				// Procesar las palabras
				while (($row = fgetcsv($file)) !== false) {
					$palabra = self::clean_string($row[0]);
					$pista   = self::clean_string($row[1]);

					if ($palabra !== "" && $pista !== "") {
						self::save_palabra_juego_model([
							"id_juego" => $id_juego,
							"palabra"  => $palabra,
							"pista"    => $pista
						]);
					}
				}

				fclose($file);

				return self::sweet_alert_url_reload([
					"title" => "¡Juego registrado!",
					"text"  => "El juego y sus palabras se registraron correctamente.",
					"type"  => "success"
				], "juegoadd/");

			} else {
				return self::sweet_alert_single([
					"title" => "¡Archivo faltante!",
					"text"  => "Debes cargar un archivo CSV con las palabras del juego",
					"type"  => "error"
				]);
			}
		}
		public function pagination_juego_controller(){



			$Datos=self::execute_single_query("
			SELECT j.id as id_juego,j.*,c.*,tj.titulo as tipo,tj.id_tipoj FROM `juegos` j LEFT JOIN juego_clase jc on jc.id_juego=j.id LEFT JOIN clase c on c.id=jc.id_clase LEFT JOIN tipo_juego tj on tj.id_tipoj=j.tipo WHERE jc.id_clase!='' and j.estado!=5");
			$Datos=$Datos->fetchAll();


			$table='
			<!-- Campo de filtro por Norma -->

			<table id="tabla-global" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Fecha</th>
						<th class="text-center">Titulo</th>
						<th class="text-center">Descripción</th>
						<th class="text-center">Tipo</th>
						<th class="text-center">Clase Asignada</th>
						<th class="text-center">Ver</th>
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
						<td>'.date("d/m/Y", strtotime($rows['fecha_creacion'])).'</td>
						<td>'.$rows['titulo'].'</td>
						<td>'.$rows['descripcion'].'</td>
						<td>'.$rows['tipo'].'</td>
						<td>'.$rows['Titulo'].'</td>
						<td>
							<a href="'.SERVERURL.'juegosview/'.$rows['id_juego'].'/'.$rows['id_tipoj'].'/" class="btn btn-info btn-raised btn-xs">
								<i class="zmdi zmdi-tv"></i>
							</a>
						</td>
						
						<td>
							<form action="'.SERVERURL.'ajax/ajaxJuegoadd.php" method="POST" enctype="multipart/form-data" autocomplete="off" data-form="" class="ajaxDataForm">
								<input type="hidden" name="id_juego" value="'.$rows['id_juego'].'">
								<button type="submit" class="btn btn-danger btn-raised btn-xs">
									<i class="zmdi zmdi-delete"></i>
								</button>
								<div class="full-box form-process"></div>
							</form>
						</td>
					</tr>
					';
					$cont+=1;
				}

			$table.='
				</tbody>
			</table>
			';

			

			return $table;
		}

		 /*----------  Verificar si el usuario ya responde la evaluación  ----------*/
        public function check_juego_respondido_controller($id_usuario, $id_juego) {
            return self::check_respuesta_usuario_model($id_usuario, $id_juego);
        }

		public function registrar_juego_completado_controller($id_alumno, $id_juego) {
			return self::registrar_juego_completado_model($id_alumno, $id_juego);
		}






		public function delete_juego_controller($code){
			$code=self::clean_string($code);

			$queryval=self::execute_single_query("SELECT * FROM `juego_alumno` where id_juego='$code'");
			$rowsval=$queryval->rowCount();
			if ($rowsval==0) {
				# code...
			

				if(self::delete_juego_model($code)){


					$dataAlert=[
						"title"=>"Juego eliminado!",
						"text"=>"El Juego ha sido eliminado del sistema satisfactoriamente",
						"type"=>"success"
					];
					return self::sweet_alert($dataAlert);
				}else{
					$dataAlert=[
						"title"=>"¡Ocurrió un error inesperado!",
						"text"=>"No pudimos eliminar el juego por favor intente nuevamente",
						"type"=>"error"
					];
					return self::sweet_alert_single($dataAlert);
				}
			}else {
				if (self::desactiva_juego_model($code)) {
					# code...
					$dataAlert=[
						"title"=>"Juego Inhabilitado!",
						"text"=>"El Juego ha sido Inhabilitado del sistema satisfactoriamente",
						"type"=>"success"
					];
					return self::sweet_alert($dataAlert);
				}else{
					$dataAlert=[
						"title"=>"¡Ocurrió un error inesperado!",
						"text"=>"No pudimos Inhabilitar el Juego por favor intente nuevamente",
						"type"=>"error"
					];
					return self::sweet_alert_single($dataAlert);
				}
			}
		}



			/*----------  Obtener Juego por ID  ----------*/
		public function obtenerJuegoPorId($id){
			$id = self::clean_string($id);

			$query = self::execute_single_query("SELECT j.id as id_juego,j.*,c.*,tj.titulo as tipo,tj.id_tipoj FROM `juegos` j LEFT JOIN juego_clase jc on jc.id_juego=j.id LEFT JOIN clase c on c.id=jc.id_clase LEFT JOIN tipo_juego tj on tj.id_tipoj=j.tipo WHERE j.id= '$id'");


			if($query && $query->rowCount() > 0){
				return $query->fetch();
			} else {
				return false;
			}
		}

		/*----------  Obtener Palabras del Juego  ----------*/
		public function obtenerPalabrasPorJuego($juego_id){
			$juego_id = self::clean_string($juego_id);

			$query = self::execute_single_query("SELECT palabra FROM palabras WHERE juego_id = '$juego_id'");

			if($query && $query->rowCount() > 0){
				return $query->fetchAll(PDO::FETCH_COLUMN);
			} else {
				return [];
			}
		}


	}