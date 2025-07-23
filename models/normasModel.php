<?php
	if($actionsRequired){
		require_once "../core/mainModel.php";
	}else{ 
		require_once "./core/mainModel.php";
	}

	class normasModel extends mainModel{

		/*----------  Add Student Model  ----------*/
		public function add_norma_model($data){
			$query=self::connect()->prepare("INSERT INTO tipo_curso(norma,descripcion) VALUES(:Titulo,:Descripcion)");
			$query->bindParam(":Titulo",$data['Titulo']);
			$query->bindParam(":Descripcion",$data['Descripcion']);
		
			$query->execute();
			return $query;
		}


		/*----------  Data Student Model  ----------*/
		public function data_norma_model($data){
			if($data['Tipo']=="Count"){
				$query=self::connect()->prepare("SELECT id_tipoc FROM tipo_curso");
			}elseif($data['Tipo']=="Only"){
				$query=self::connect()->prepare("SELECT * FROM tipo_curso WHERE id_tipoc=:Codigo");
				$query->bindParam(":Codigo",$data['Codigo']);
			}
			$query->execute();
			return $query;
		}


		/*----------  Delete Student Model  ----------*/
		public function delete_norma_model($code){
			$query=self::connect()->prepare("DELETE FROM tipo_curso WHERE id_tipoc=:Codigo");
			$query->bindParam(":Codigo",$code);
			$query->execute();
			return $query;
		}

		public function valida_norma($code){
			$query=self::connect()->prepare("SELECT COUNT(*) FROM `curso` WHERE `Norma`=:Codigo");
			$query->bindParam(":Codigo",$code);
			$query->execute();
			return $query->fetchColumn();
		}


		/*----------  Update Norma Model  ----------*/
		public function update_norma_model($data){
			$query=self::connect()->prepare("UPDATE tipo_curso SET norma=:Titulo,descripcion=:Descripcion WHERE id_tipoc=:Codigo");
			$query->bindParam(":Titulo",$data['Titulo']);
			$query->bindParam(":Descripcion",$data['Descripcion']);
			$query->bindParam(":Codigo",$data['Codigo']);
			$query->execute();
			return $query;
		}
	}