<?php
	if($actionsRequired){
		require_once "../core/mainModel.php";
	}else{ 
		require_once "./core/mainModel.php";
	}

	class cursoclaseModel extends mainModel{

		/*----------  Add Incripcion Curso Model  ----------*/
		public function add_incripcion_model($data){
	
			$estado=4;
			$query=self::connect()->prepare("INSERT INTO curso_alumno(id_curso,id_alumno,estado_curso) VALUES(:id_curso,:id_alumno,:Estado)");
			$query->bindParam(":id_alumno",$data['id_alumno']);
			$query->bindParam(":id_curso",$data['id_curso']);
			$query->bindParam(":Estado",$estado);
			$query->execute();
			return $query;
		} /*----------  Verificar si el usuario ya respondió esta clase  ----------*/
        public function check_respuesta_evaluacion_model($id_usuario, $id_juego) {
            $query = self::connect()->prepare("
                SELECT COUNT(*) 
                FROM juego_alumno ja 
                LEFT JOIN juegos j on j.id=ja.id_juego
                WHERE id_alumno = :id_usuario AND id_juego = :id_juego AND j.estado!=5
            ");
            $query->bindParam(":id_usuario", $id_usuario);
            $query->bindParam(":id_juego", $id_juego);
            $query->execute();
            return $query->fetchColumn() > 0;
        }

		public function check_respuesta_encuesta_model($id_usuario, $id_clase) {
            $query = self::connect()->prepare("
                  SELECT COUNT(*) 
                    FROM encuesta_usuario 
                    WHERE id_usuario = :id_usuario AND id_clase = :id_clase AND estado=1
					
            ");
            $query->bindParam(":id_usuario", $id_usuario);
            $query->bindParam(":id_clase", $id_clase);
            $query->execute();
            return $query->fetchColumn() > 0;
        }
        public function check_juego_resuelto_model($id_usuario, $id_juego) {
            $query = self::connect()->prepare("
                  SELECT COUNT(*) 
                FROM juego_alumno ja 
                LEFT JOIN juegos j on j.id=ja.id_juego
                WHERE id_alumno = :id_usuario AND id_juego = :id_juego AND j.estado!=5
            ");
            $query->bindParam(":id_usuario", $id_usuario);
            $query->bindParam(":id_juego", $id_juego);
            $query->execute();
            return $query->fetchColumn() > 0;
        }





	}