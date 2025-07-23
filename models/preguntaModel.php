<?php
    if($actionsRequired){
        require_once "../core/mainModel.php";
    }else{ 
        require_once "./core/mainModel.php";
    }

    class preguntaModel extends mainModel {

        /*----------  Obtener preguntas por clase  ----------*/
        public function get_preguntas_by_clase_model($id_clase) {
            $query = self::connect()->prepare("SELECT * FROM preguntas_rapidas WHERE id_clase = :id_clase");
            $query->bindParam(":id_clase", $id_clase);
            $query->execute();
            return $query;
        }

        public function get_respuestas_usuario_model($id_usuario, $id_clase) {
            $query = self::connect()->prepare("
                SELECT 
                    r.id_pregunta, 
                    r.respuesta_usuario,
                    p.pregunta,
                    p.tipo,
                    p.respuesta_correcta
                FROM respuestas_alumnos r
                INNER JOIN preguntas_rapidas p ON r.id_pregunta = p.id_pregunta
                WHERE r.id_usuario = :id_usuario AND p.id_clase = :id_clase
            ");
            $query->execute([
                ':id_usuario' => $id_usuario,
                ':id_clase' => $id_clase
            ]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        /*----------  Verificar si el usuario ya respondió esta clase  ----------*/
        public function check_respuesta_usuario_model($id_usuario, $id_clase) {
            $query = self::connect()->prepare("
                  SELECT COUNT(*) 
                    FROM notas_usuario 
                    WHERE id_usuario = :id_usuario AND id_clase = :id_clase AND estado=1
            ");
            $query->bindParam(":id_usuario", $id_usuario);
            $query->bindParam(":id_clase", $id_clase);
            $query->execute();
            return $query->fetchColumn() > 0;
        }
     /*----------  Verificar Nota  ----------*/
        public function nota_alumno($id_usuario, $id_clase) {
            $query = self::connect()->prepare("
                  SELECT * FROM `notas_usuario` WHERE id_usuario = :id_usuario AND id_clase = :id_clase AND estado=1
            ");
            $query->bindParam(":id_usuario", $id_usuario);
            $query->bindParam(":id_clase", $id_clase);
            $query->execute();
            $resultado = $query->fetch(PDO::FETCH_ASSOC);

            return $resultado ? $resultado['nota'] : false;
        }
        /*----------  Guardar respuesta del alumno  ----------*/
        public function save_respuesta_model($data) {
            $query = self::connect()->prepare("
                INSERT INTO respuestas_alumnos (id_usuario, id_pregunta, respuesta_usuario)
                VALUES (:id_usuario, :id_pregunta, :respuesta_usuario)
            ");
            $query->bindParam(":id_usuario", $data['id_usuario']);
            $query->bindParam(":id_pregunta", $data['id_pregunta']);
            $query->bindParam(":respuesta_usuario", $data['respuesta_usuario']);
            return $query->execute();
        }
        /*----------  Registrar finalización de evaluación en notas_usuario  ----------*/
        public function registrar_finalizacion_model($data) {
            $query = self::connect()->prepare("
                INSERT INTO notas_usuario (
                    id_usuario, 
                    id_clase, 
                    nota, 
                    estado
                ) VALUES (
                    :id_usuario, 
                    :id_clase, 
                    :nota, 
                    :estado
                )
            ");
            
            // Vincular parámetros
             $query->bindParam(":id_usuario", $data['id_usuario']);
            $query->bindParam(":id_clase", $data['id_clase']);
            $query->bindParam(":nota", $data['nota']);
            $query->bindParam(":estado", $data['estado']);

            // Ejecutar y devolver true/false según resultado
            return $query->execute();
        }
    }