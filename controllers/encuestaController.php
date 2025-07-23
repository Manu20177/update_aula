<?php
require_once __DIR__ . '/../core/configGeneral.php';

    if($actionsRequired){
        require_once "../models/encuestaModel.php";
    }else{ 
        require_once "./models/encuestaModel.php";
    }

    class encuestaController extends encuestaModel {

        /*----------  Cargar preguntas por clase  ----------*/
        public function cargar_preguntas_controller($id_clase) {
            $id_clase = self::clean_string($id_clase);
            $resultado = self::get_preguntas_by_clase_model($id_clase);
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        }

        /*----------  Cargar preguntas por clase  ----------*/
        public function cargar_preguntas_evaluacion_controller($id_clase) {
            $id_clase = self::clean_string($id_clase);
            $resultado = self::get_preguntas_by_evaluacion_model($id_clase);
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        }


        /*----------  Verificar si el usuario ya responde la evaluación  ----------*/
        public function check_encuesta_respondida_controller($id_usuario, $id_clase) {
            return self::check_respuesta_usuario_model($id_usuario, $id_clase);
        }

        /*----------  Guardar respuestas del alumno  ----------*/
       /*----------  Guardar respuestas del alumno y calificar  ----------*/
        public function guardar_respuestas_controller($id_usuario, $id_clase, $preguntas) {
            try {
                // 1. Obtener todas las preguntas de la clase
                $preguntas_db = self::get_preguntas_by_clase_model($id_clase)->fetchAll(PDO::FETCH_ASSOC);

                if (empty($preguntas_db)) {
                                         
                
                    $dataAlert=[
                            "title" => "Error",
                            "text" => "No hay preguntas registradas para esta clase.",
                            "type" => "error"
                        ];
                    return self::sweet_alert_single($dataAlert);
                }


                // 2. Guardar respuestas y verificar aciertos
                foreach ($preguntas_db as $p) {
                    $id_pregunta = $p['id_pregunta'];

                    // Verificar si el usuario respondió esta pregunta
                    if (!isset($preguntas[$id_pregunta])) {
                        continue; // Saltar si no responde
                    }

                    $respuesta_usuario = $preguntas[$id_pregunta];

                    // Guardar respuesta
                    $datos_respuesta = [
                        'id_usuario' => $id_usuario,
                        'id_pregunta' => $id_pregunta,
                        'respuesta_usuario' => $respuesta_usuario
                    ];
                    self::save_respuesta_model($datos_respuesta);

                }

            
                // 4. Registrar finalización y nota
                $datos_nota = [
                    'id_usuario' => $id_usuario,
                    'id_clase' => $id_clase,
                    'estado' => 1 // Completado
                ];

                

            	if(self::registrar_finalizacion_model($datos_nota)){

               
                $Datos1=self::execute_single_query("SELECT * FROM `curso_alumno` ca 
                                    LEFT JOIN curso_clase cc on cc.id_curso=ca.id_curso
                                    WHERE ca.id_alumno='$id_usuario' && cc.id_clase='$id_clase'");
				$id_datos = $Datos1->fetch(PDO::FETCH_ASSOC);
                $id_curso=$id_datos['id_curso'];
                $Datos=self::execute_single_query("SELECT * FROM curso WHERE id_curso='$id_curso'");
				$curso = $Datos->fetch(PDO::FETCH_ASSOC);

                 $dataAlert=[
						"title" => "¡Éxito!",
                        "text" => "Tus respuestas han sido enviadas correctamente.",
                        "type" => "success"
					];
                    

                echo self::sweet_alert($dataAlert);

                echo '
                    <form id="redirectForm" method="POST" action="'.SERVERURL.'cursoclases" style="display:none;">
                        <input type="hidden" name="cod" value="'.$curso['id_curso'].'">
                        <input type="hidden" name="titulo" value="'.$curso['Titulo'].'">
                        <input type="hidden" name="portada" value="attachments/class_portada/'.$curso['Portada'].'">
                        <input type="hidden" name="id_alumno" value="'.$id_usuario.'">
                        <input type="hidden" name="fecha" value="'.date("Y-m-d").'">
                    </form>
                    <script>
                        setTimeout(function() {
                            document.getElementById("redirectForm").submit();
                        }, 3000); // 3000 milisegundos = 3 segundos
                    </script>                    ';					

                }else{
					
								
					$dataAlert=[
						"title"=>"¡Ocurrió un error inesperado!",
						"text"=>"No hemos podido enviar la evaluacion, por favor intente nuevamente",
						"type"=>"error"
					];
					return self::sweet_alert_single($dataAlert);
				}

            } catch (Exception $e) {
                
              
                $dataAlert=[
						"title" => "Error",
                        "text" => "Hubo un problema al guardar tus respuestas. Inténtalo nuevamente. '$e'",
                        "type" => "error"
					];
                return self::sweet_alert_single($dataAlert);

            }
        }

        public function eliminar_pregunta_controller($id_pregunta) {
            $query = "DELETE FROM encuesta_rapida WHERE id_pregunta = '$id_pregunta'";
            return self::execute_single_query($query);
        }

        public function eliminar_encuesta_completa_controller($id_clase) {
            // Elimina todas las preguntas asociadas a esta clase
            $query = "DELETE FROM encuesta_rapida WHERE id_clase = '$id_clase'";
            return self::execute_single_query($query);
        }

         public function eliminar_evaluacion_completa_controller($id_clase) {
            // Elimina todas las preguntas asociadas a esta clase
            $query = "DELETE FROM preguntas_rapidas  WHERE id_clase = '$id_clase'";
            return self::execute_single_query($query);
        }

         public function eliminar_preguntaevalua_controller($id_pregunta) {
            $query = "DELETE FROM preguntas_rapidas WHERE id_pregunta = '$id_pregunta'";
            return self::execute_single_query($query);
        }
    }