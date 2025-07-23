<?php
	if($actionsRequired){
		require_once "../core/mainModel.php";
	}else{ 
		require_once "./core/mainModel.php";
	}

	class videoModel extends mainModel{

		/*----------  Add Video Model  ----------*/
		public function add_video_model($data){
			$conexion = self::connect();
			$Estado=4;

			$query=$conexion->prepare("INSERT INTO clase(Video,Fecha,Titulo,Tutor,Descripcion,Adjuntos,estado_clase) VALUES(:Video,:Fecha,:Titulo,:Tutor,:Descripcion,:Adjuntos,:Estado)");
			$query->bindParam(":Video",$data['Video']);
			$query->bindParam(":Fecha",$data['Fecha']);
			$query->bindParam(":Titulo",$data['Titulo']);
			$query->bindParam(":Tutor",$data['Tutor']);
			$query->bindParam(":Descripcion",$data['Descripcion']);
			$query->bindParam(":Adjuntos",$data['Adjuntos']);
			$query->bindParam(":Estado",$Estado);
			$query->execute();

			$lastId = $conexion->lastInsertId();

			$query1=$conexion->prepare("INSERT INTO curso_clase(id_curso,id_clase,orden,horas) VALUES(:Curso,:Clase,:Posicion,:Horas)");
			$query1->bindParam(":Curso",$data['Curso']);
			$query1->bindParam(":Posicion",$data['Posicion']);
			$query1->bindParam(":Horas",$data['Horas']);
			$query1->bindParam(":Clase",$lastId);
			$query1->execute();

			return $lastId;
		}


		/*----------  Data Video Model  ----------*/
		public function data_video_model($data){
			if($data['Tipo']=="Count"){
				$query=self::connect()->prepare("SELECT id FROM clase");
			}elseif($data['Tipo']=="Only"){
				$query=self::connect()->prepare("SELECT * FROM clase c LEFT JOIN curso_clase cc on cc.id_clase=c.id WHERE id=:id");
				$query->bindParam(":id",$data['id']);
			}
			$query->execute();
			return $query;
		}


		/*----------  Delete Video Model  ----------*/
		public function delete_video_model($code){
			$query=self::connect()->prepare("DELETE FROM clase WHERE id=:id");
			$query->bindParam(":id",$code);
			$query->execute();
			return $query;
		}

		/*----------  desactiva Video Model  ----------*/
		public function desactiva_video_model($code){
			$query=self::connect()->prepare("UPDATE clase SET estado_clase=5 WHERE id=:id");
			$query->bindParam(":id",$code);
			$query->execute();
			return $query;
		}


		/*----------  Update Video Model  ----------*/
		public function update_video_model($data){
			$query=self::connect()->prepare("UPDATE clase SET Video=:Video,Fecha=:Fecha,Titulo=:Titulo,Tutor=:Tutor,Descripcion=:Descripcion,Adjuntos=:Adjuntos WHERE id=:id");
			$query->bindParam(":Video",$data['Video']);
			$query->bindParam(":Fecha",$data['Fecha']);
			$query->bindParam(":Titulo",$data['Titulo']);
			$query->bindParam(":Tutor",$data['Tutor']);
			$query->bindParam(":Descripcion",$data['Descripcion']);
			$query->bindParam(":Adjuntos",$data['Adjuntos']);
			$query->bindParam(":id",$data['id']);
			$query->execute();

			$query1=self::connect()->prepare("UPDATE curso_clase SET id_curso=:Curso,horas=:Horas WHERE id_clase=:Clase");
			$query1->bindParam(":Curso",$data['Curso']);
			$query1->bindParam(":Horas",$data['Horas']);
			$query1->bindParam(":Clase",$data['id']);
			$query1->execute();
			return $query;
		}
	}