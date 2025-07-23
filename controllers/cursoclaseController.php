<?php
	if($actionsRequired){
		require_once "../models/cursoclaseModel.php";
	}else{ 
		require_once "./models/cursoclaseModel.php";
	}

	class cursoclaseController extends cursoclaseModel{

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
				if(self::add_incripcion_model($data)){
					$dataAlert=[
						"title"=>"Incripcion registrada!",
						"text"=>"Usted se ha Incrito al Curso con éxito",
						"type"=>"success"
					];
					return self::sweet_alert_reset($dataAlert);
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

		public function obtenerclases_controller($id_alumno,$code){
			$query = self::connect()->prepare("SELECT ca.*,cc.*,c.*,jc.*,j.tipo FROM `curso_alumno` ca LEFT JOIN curso_clase cc on cc.id_curso=ca.id_curso LEFT JOIN clase c on c.id=cc.id_clase LEFT JOIN juego_clase jc on jc.id_clase=c.id 
			LEFT JOIN juegos j on j.id=jc.id_juego WHERE ca.id_curso= :id_curso && ca.id_alumno= :id_alumno && c.estado_clase=4 ORDER BY `cc`.`orden` ASC");
			$query->bindParam(":id_alumno", $id_alumno);
			$query->bindParam(":id_curso", $code);
			$query->execute();
			return $query;
		}

	
		public function obtenerCantEvaluaciones($id_clase){
			$query = self::connect()->prepare("SELECT *  FROM `preguntas_rapidas` WHERE id_clase=:id_clase LIMIT 1;");
			$query->bindParam(":id_clase", $id_clase);
			$query->execute();
			return $query->rowCount();
		}
		public function obtenerCantJuegos($id_juego){
			$query = self::connect()->prepare("SELECT *  FROM `juego_clase` jc LEFT JOIN juegos j on j.id=jc.id_juego WHERE id_juego=:id_juego AND j.estado!=5 LIMIT 1;");
			$query->bindParam(":id_juego", $id_juego);
			$query->execute();
			return $query->rowCount();
		}
		
		public function check_juego_resuelto_controller($id_usuario, $id_juego) {
            return self::check_juego_resuelto_model($id_usuario, $id_juego);
        }


		public function obtenerCantEncuestas($id_juego){
			$query = self::connect()->prepare("SELECT *  FROM `juego_clase` jc LEFT JOIN juegos j on j.id=jc.id_juego WHERE id_juego=:id_juego AND j.estado!=5 LIMIT 1;");
			$query->bindParam(":id_juego", $id_juego);
			$query->execute();
			return $query->rowCount();
		}

		public function completar_curso($id_alumno,$id_curso){
			$query = self::connect()->prepare("UPDATE `curso_alumno` SET `estado_curso` = '1', `fecha_termino`=NOW()
			WHERE id_alumno=:id_alumno && id_curso=:id_curso;");
			$query->bindParam(":id_curso", $id_curso);
			$query->bindParam(":id_alumno", $id_alumno);
			$query->execute();
			return $query;
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
				<form method="POST" action="' . SERVERURL . 'verificarcurso">
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

	
		  /*----------  Verificar si el usuario ya responde la evaluación  ----------*/
        public function check_evaluacion_respondida_controller($id_usuario, $id_clase) {
            return self::check_respuesta_evaluacion_model($id_usuario, $id_clase);
        }

		  /*----------  Verificar si el usuario ya responde la evaluación  ----------*/
        public function check_encuesta_respondida_controller($id_usuario, $id_clase) {
            return self::check_respuesta_encuesta_model($id_usuario, $id_clase);
        }

	}