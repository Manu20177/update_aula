<?php
	if($actionsRequired){
		require_once "../core/mainModel.php";
	}else{ 
		require_once "./core/mainModel.php";
	}

	class encuestaentrevistaModel extends mainModel{

		/*----------  Add Encuesta Model  ----------*/
		public function save_encuesta_model($data){
			
			$query=self::connect()->prepare("INSERT INTO encuesta_rapida(id_clase,pregunta,tipo,opcion1,opcion2,opcion3) VALUES(:id_clase,:pregunta,:tipo,:opcion1,:opcion2,:opcion3)");
			$query->bindParam(":id_clase",$data['id_clase']);
			$query->bindParam(":pregunta",$data['pregunta']);
			$query->bindParam(":tipo",$data['tipo']);
			$query->bindParam(":opcion1",$data['opcion1']);
			$query->bindParam(":opcion2",$data['opcion2']);
			$query->bindParam(":opcion3",$data['opcion3']);
			$query->execute();
			return $query;
		}

		/*----------  Add Evaluacion Model  ----------*/
		public function save_evaluacion_model($data){
			
			$query=self::connect()->prepare("INSERT INTO preguntas_rapidas(id_clase,pregunta,tipo,opcion1,opcion2,opcion3,respuesta) VALUES(:id_clase,:pregunta,:tipo,:opcion1,:opcion2,:opcion3,:respuesta)");
			$query->bindParam(":id_clase",$data['id_clase']);
			$query->bindParam(":pregunta",$data['pregunta']);
			$query->bindParam(":tipo",$data['tipo']);
			$query->bindParam(":opcion1",$data['opcion1']);
			$query->bindParam(":opcion2",$data['opcion2']);
			$query->bindParam(":opcion3",$data['opcion3']);
			$query->bindParam(":respuesta",$data['respuesta']);
			$query->execute();
			return $query;
		}


		/*----------  Data Curso Model  ----------*/
		public function data_curso_model($data){
			if($data['Tipo']=="Count"){
				$query=self::connect()->prepare("SELECT id_curso FROM curso");
			}elseif($data['Tipo']=="Only"){
				$query=self::connect()->prepare("SELECT * FROM curso WHERE id_curso=:id");
				$query->bindParam(":id",$data['id']);
			}
			$query->execute();
			return $query;
		}


		/*----------  Delete Curso Model  ----------*/
		public function delete_curso_model($code){
			$query=self::connect()->prepare("DELETE FROM curso WHERE id_curso=:id");
			$query->bindParam(":id",$code);
			$query->execute();
			return $query;
		}
		/*----------  desactiva Curso Model  ----------*/
		public function desactiva_video_model($code){
			$query=self::connect()->prepare("UPDATE curso SET Estado=5 WHERE id_curso=:id");
			$query->bindParam(":id",$code);
			$query->execute();
			return $query;
		}


		/*----------  Update Curso Model  ----------*/
		public function update_curso_model($data){
			$query=self::connect()->prepare("UPDATE curso SET Titulo=:Titulo,Portada=:Portada,Fecha=:Fecha,Estado=:Estado WHERE id_curso=:id");
			$query->bindParam(":Titulo",$data['Titulo']);
			$query->bindParam(":Portada",$data['Portada']);
			$query->bindParam(":Fecha",$data['Fecha']);
			$query->bindParam(":Estado",$data['Estado']);
			$query->bindParam(":id",$data['id']);
			$query->execute();
			return $query;
		}
	}