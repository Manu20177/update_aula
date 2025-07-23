<?php
	if($actionsRequired){
		require_once "../core/mainModel.php";
	}else{ 
		require_once "./core/mainModel.php";
	}

	class cursoclaveModel extends mainModel{

		/*----------  Add Incripcion Curso Model  ----------*/
		public function add_incripcion_model($data){
	
			$estado=4;
			$query=self::connect()->prepare("INSERT INTO curso_alumno(id_curso,id_alumno,estado_curso) VALUES(:id_curso,:id_alumno,:Estado)");
			$query->bindParam(":id_alumno",$data['id_alumno']);
			$query->bindParam(":id_curso",$data['id_curso']);
			$query->bindParam(":Estado",$estado);
			$query->execute();
			return $query;
		}


	}