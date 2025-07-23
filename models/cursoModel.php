<?php
	if($actionsRequired){
		require_once "../core/mainModel.php";
	}else{ 
		require_once "./core/mainModel.php";
	}

	class cursoModel extends mainModel{

		/*----------  Add Curso Model  ----------*/
		public function add_curso_model($data){
			$query2=self::execute_single_query("SELECT id_curso FROM curso");
			$correlative=($query2->rowCount())+1;
			$code=self::generate_code("CCF",10,$correlative);
			$estado=4;
			$query=self::connect()->prepare("INSERT INTO curso(id_curso,Titulo,Portada,Fecha,Estado,Norma) VALUES(:id_curso,:Titulo,:Portada,:Fecha,:Estado,:Norma)");
			$query->bindParam(":id_curso",$code);
			$query->bindParam(":Fecha",$data['Fecha']);
			$query->bindParam(":Titulo",$data['Titulo']);
			$query->bindParam(":Portada",$data['Portada']);
			$query->bindParam(":Estado",$estado);
			$query->bindParam(":Norma",$data['Norma']);
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
			$query=self::connect()->prepare("UPDATE curso SET Titulo=:Titulo,Portada=:Portada,Fecha=:Fecha,Estado=:Estado,Norma=:Norma WHERE id_curso=:id");
			$query->bindParam(":Titulo",$data['Titulo']);
			$query->bindParam(":Portada",$data['Portada']);
			$query->bindParam(":Fecha",$data['Fecha']);
			$query->bindParam(":Estado",$data['Estado']);
			$query->bindParam(":Norma",$data['Norma']);
			$query->bindParam(":id",$data['id']);
			$query->execute();
			return $query;
		}
	}