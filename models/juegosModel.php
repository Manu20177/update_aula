<?php
	if($actionsRequired){
		require_once "../core/mainModel.php";
	}else{ 
		require_once "./core/mainModel.php";
	}

	class juegosModel extends mainModel{

        public function save_juego_model($data){
            // Insertar en la tabla juegos
            $estado=1;
            $query = self::connect()->prepare("INSERT INTO juegos(id, tipo, titulo, descripcion, archivo_csv,estado) 
                                            VALUES(:id_juego, :tipo, :titulo, :descripcion, :archivo_csv,:estado)");
            $query->bindParam(":id_juego", $data['id_juego']);
            $query->bindParam(":tipo", $data['tipo']);
            $query->bindParam(":titulo", $data['titulo']);
            $query->bindParam(":descripcion", $data['descripcion']);
            $query->bindParam(":archivo_csv", $data['archivo_csv']);
            $query->bindParam(":estado", $estado);
            $query->execute();

            // Insertar en la tabla juego_clase
            $query2 = self::connect()->prepare("INSERT INTO juego_clase(id_clase, id_juego) 
                                                VALUES(:id_clase, :id_juego)");
            $query2->bindParam(":id_clase", $data['id_clase']);
            $query2->bindParam(":id_juego", $data['id_juego']);
            $query2->execute();

            return $query;
        }

         public function save_palabra_juego_model($data){
			
			$query=self::connect()->prepare("INSERT INTO palabras(juego_id,palabra,pista) VALUES(:id_juego,:palabra,:pista)");
			$query->bindParam(":id_juego",$data['id_juego']);
			$query->bindParam(":palabra",$data['palabra']);
			$query->bindParam(":pista",$data['pista']);

			$query->execute();
			return $query;
		}
                /*----------  Obtener juego por ID  ----------*/
        public function obtenerJuegoPorIdModel($id){
            $query = self::connect()->prepare("SELECT * FROM juegos WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();
            return $query;
        }

        /*----------  Obtener palabras por juego_id  ----------*/
        public function obtenerPalabrasPorJuegoModel($juego_id){
            $query = self::connect()->prepare("SELECT palabra FROM palabras WHERE juego_id = :juego_id");
            $query->bindParam(":juego_id", $juego_id);
            $query->execute();
            return $query;
        }

        public function obtenerPalabrasPorJuegoPista($juego_id){
            $juego_id = self::clean_string($juego_id);

            $query = self::execute_single_query("SELECT palabra, pista FROM palabras WHERE juego_id = '$juego_id' ORDER BY `palabras`.`palabra` ASC");

            if($query && $query->rowCount() > 0){
                return $query->fetchAll(PDO::FETCH_ASSOC); // fetch con arreglo asociativo para obtener ambas columnas
            } else {
                return [];
            }
        }

        /*----------  Delete Curso Model  ----------*/
		public function delete_juego_model($code){
			$query=self::connect()->prepare("DELETE FROM juegos WHERE id=:id");
			$query->bindParam(":id",$code);
			$query->execute();
			return $query;
		}
		/*----------  desactiva Curso Model  ----------*/
		public function desactiva_juego_model($code){
			$query=self::connect()->prepare("UPDATE juegos SET estado=5 WHERE id=:id");
			$query->bindParam(":id",$code);
			$query->execute();
			return $query;
		}

        public function registrar_juego_completado_model($id_alumno, $id_juego) {
            $sql = self::connect()->prepare("INSERT INTO juego_alumno (id_alumno, id_juego) VALUES (:alumno, :juego)");
            $sql->bindParam(":alumno", $id_alumno);
            $sql->bindParam(":juego", $id_juego);
            $sql->execute();
            return $sql;
        }


        public function check_respuesta_usuario_model($id_usuario, $id_juego) {
            $query = self::connect()->prepare("
                  SELECT COUNT(*) 
                    FROM juego_alumno 
                    WHERE id_alumno = :id_usuario AND id_juego = :id_juego
            ");
            $query->bindParam(":id_usuario", $id_usuario);
            $query->bindParam(":id_juego", $id_juego);
            $query->execute();
            return $query->fetchColumn() > 0;
        }






	}