<?php
require_once __DIR__ . '/../core/configGeneral.php';

	if($actionsRequired){
		require_once "../models/cursoclaveModel.php";
	}else{ 
		require_once "./models/cursoclaveModel.php";
	}

	class cursoclaveController extends cursoclaveModel{
	    public function clave_copy($idcurso) {
        // Consultar datos del curso
        $query = self::execute_single_query("SELECT * FROM `curso` WHERE id_curso = '$idcurso'");
        
        if ($query && $fila = $query->fetch(PDO::FETCH_ASSOC)) {
            // Datos del curso
            $titulo    = $fila['Titulo'];
            $portada   = $fila['Portada'];
            $fecha     = $fila['Fecha'];
            $id_alumno = $_SESSION['userKey'] ?? '';
    
            // Guardar en sesión
            $_SESSION["curso_seleccionado"] = [
                "titulo"    => $titulo,
                "fecha"     => $fecha,
                "portada"   => $portada,
                "cod"       => $idcurso,
                "id_alumno" => $id_alumno
            ];
    
        } else {
            // Curso no encontrado
            echo '
                <script>
                    alert("Curso no encontrado");
                    window.location.href = "' . SERVERURL . 'index/";
                </script>
            ';
            exit;
        }
    
        // Redirigir a la misma página, ya con los datos en sesión
        echo '
            <script>
                window.location.href = "' . SERVERURL . 'cursoclave/' . $idcurso . '";
            </script>
        ';
        exit;
    }

		/*----------  Add Inscripcion Controller  ----------*/
		public function add_inscripcion_controller(){
			$clave=self::clean_string($_POST['clave']);
			$cursoclave=self::clean_string($_POST['cursoclave']);
			$id_alumno=self::clean_string($_POST['id_alumno']);

			if($clave!=$cursoclave){

				$dataAlert=[
						"title"=>"¡Clave de Inscripcion Incorrecta!",
						"text"=>"No pudo inscribirse al Curso",
						"type"=>"danger"
					];
					return self::sweet_alert_reset($dataAlert);

			}else{
				$data=[
					"id_curso"=>$cursoclave,
					"id_alumno"=>$id_alumno
				];
				$Datos=self::execute_single_query("SELECT * FROM curso WHERE id_curso='$cursoclave'");
				$curso = $Datos->fetch(PDO::FETCH_ASSOC);


					

				if(self::add_incripcion_model($data)){
					$dataAlert=[
						"title"=>"Incripcion registrada!",
						"text"=>"Usted se ha Incrito al Curso con éxito",
						"type"=>"success"

					];
				
					self::sweet_alert($dataAlert);
					
					echo '
						<form id="redirectForm" method="POST" action="'.SERVERURL.'verificarcurso" style="display:none;">
							<input type="hidden" name="cod" value="'.$curso['id_curso'].'">
							<input type="hidden" name="titulo" value="'.$curso['Titulo'].'">
							<input type="hidden" name="portada" value="attachments/class_portada/'.$curso['Portada'].'">
							<input type="hidden" name="id_alumno" value="'.$id_alumno.'">
							<input type="hidden" name="fecha" value="'.date("Y-m-d").'">
						</form>
						<script>document.getElementById("redirectForm").submit();</script>
						';
				}else{
					
								
					$dataAlert=[
						"title"=>"¡Ocurrió un error inesperado!",
						"text"=>"No hemos podido registrar el Curso, por favor intente nuevamente",
						"type"=>"error"
					];
					return self::sweet_alert_single($dataAlert);
				}
			}
		}


		public function curso_acceso(){


			
			$cards = '<div class="row">';

			// Ruta base donde se almacenan las portadas
			$rutaPortadas = '/aula/attachments/class_portada/';

			// Verificamos si hay portada y si el archivo existe físicamente
			$portada = $_POST['portada'];
			$titulo = $_POST['titulo'];
			$fecha = $_POST['fecha'];
			$cod = $_POST['cod'];

			
			$cards .= '
			<div class="col-md-4 mb-4">
				<form method="POST" action="' . SERVERURL . 'cursoclave">
					<div class="card h-100 shadow-sm cursor-pointer card-click" onclick="this.closest(\'form\').submit();">
						<div class="card-body">
							<img src="' . $portada . '" class="card-img-top" alt="Portada del curso">
						</div>
						<div class="card-footer text-center">
							<h5 class="card-title">Curso ' . $portada . ': ' . $fecha . '</h5>
							<p class="card-text"><strong>Fecha:</strong> ' . $cod . '</p>
						</div>
						
					</div>
				</form>
			</div>';

				

			$cards .= '</div>';

			return $cards;
		}

	
	}