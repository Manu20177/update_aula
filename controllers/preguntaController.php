<?php
require_once __DIR__ . '/../core/configGeneral.php';

    if($actionsRequired){
        require_once "../models/preguntaModel.php";
    }else{ 
        require_once "./models/preguntaModel.php";
    }

    class preguntaController extends preguntaModel {

        /*----------  Cargar preguntas por clase  ----------*/
        public function cargar_preguntas_controller($id_clase) {
            $id_clase = self::clean_string($id_clase);
            $resultado = self::get_preguntas_by_clase_model($id_clase);
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        }

        /*----------  Verificar si el usuario ya responde la evaluación  ----------*/
        public function check_evaluacion_respondida_controller($id_usuario, $id_clase) {
            return self::check_respuesta_usuario_model($id_usuario, $id_clase);
        }


         /*----------  Verificar si el usuario ya responde la evaluación  ----------*/
        public function obtener_nota($id_usuario, $id_clase) {
            return self::nota_alumno($id_usuario, $id_clase);
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

                $aciertos = 0;
                $total_preguntas = count($preguntas_db);
                $valor_por_pregunta = $total_preguntas > 0 ? 10 / $total_preguntas : 0;

                // Guardar respuestas y verificar aciertos
                foreach ($preguntas_db as $p) {
                    $id_pregunta = $p['id_pregunta'];
                    $respuesta_correcta = $p['respuesta'];

                    if (!isset($preguntas[$id_pregunta])) {
                        continue;
                    }

                    $respuesta_usuario = $preguntas[$id_pregunta];

                    $datos_respuesta = [
                        'id_usuario' => $id_usuario,
                        'id_pregunta' => $id_pregunta,
                        'respuesta_usuario' => $respuesta_usuario
                    ];
                    self::save_respuesta_model($datos_respuesta);
                    function normalizar_texto($texto) {
                        // Convertir a minúsculas, quitar espacios y eliminar tildes
                        $texto = strtolower(trim($texto));
                        $texto = str_replace(
                            ['á', 'é', 'í', 'ó', 'ú', 'ñ'],
                            ['a', 'e', 'i', 'o', 'u', 'n'],
                            $texto
                        );
                        return $texto;
                    }

                    if (normalizar_texto($respuesta_usuario) === normalizar_texto($respuesta_correcta)) {
                        $aciertos++;
                    }
                    if (strtolower(trim($respuesta_correcta)) === "") {
                        $aciertos++;
                    }
                }

                $nota = round($aciertos * $valor_por_pregunta, 2);


                // 4. Registrar finalización y nota
                $datos_nota = [
                    'id_usuario' => $id_usuario,
                    'id_clase' => $id_clase,
                    'nota' => $nota,
                    'estado' => 1 // Completado
                ];

                

            	if($nota>=7){

               
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
                    

                self::registrar_finalizacion_model($datos_nota);

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
						"title"=>"No ha aprobado el Curso",
						"text"=>"Usted ha obtenido una nota de {$nota}, debe realizar la evaluación nuevamente",
						"type"=>"error"
					];
					return self::sweet_alert_reset($dataAlert);
				}

            } catch (Exception $e) {
                
              
                $dataAlert=[
						"title" => "Error",
                        "text" => "Hubo un problema al guardar tus respuestas. Inténtalo nuevamente.",
                        "type" => "error"
					];
                return self::sweet_alert_single($dataAlert);

            }
        }
    }