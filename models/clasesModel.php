<?php
	if($actionsRequired){
		require_once "../core/mainModel.php";
	}else{ 
		require_once "./core/mainModel.php";
	}

	class cursoModel extends mainModel{

				/*----------  Data Video Model  ----------*/
		public function data_curso_model($data){
			if($data['Tipo']=="Count"){
				$query=self::connect()->prepare("SELECT id FROM clase");
			}elseif($data['Tipo']=="Only"){
				$query=self::connect()->prepare("SELECT * FROM clase WHERE id=:id");
				$query->bindParam(":id",$data['id']);
			}
			$query->execute();
			return $query;
		}



	}