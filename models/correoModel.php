<?php
	if($actionsRequired){
		require_once "../core/mainModel.php";
	}else{ 
		require_once "./core/mainModel.php";
	}

	class correoModel extends mainModel{

		/*----------  Add Video Model  ----------*/
		public function save_email_model($datos) {
			$query = "INSERT INTO correos_enviados(usuario_id, destinatarios, asunto, contenido, archivos_adjuntos, fecha_envio)
					VALUES(:usuario_id, :destinatarios, :asunto, :contenido, :archivos_adjuntos, NOW())";

			$stmt = self::connect()->prepare($query);
			$stmt->bindParam(":usuario_id", $datos['usuario_id']);
			$stmt->bindParam(":destinatarios", $datos['destinatarios']);
			$stmt->bindParam(":asunto", $datos['asunto']);
			$stmt->bindParam(":contenido", $datos['contenido']);
			$stmt->bindParam(":archivos_adjuntos", $datos['adjuntos']);
			$stmt->execute();

			return $stmt;
		}
		public function data_correo_model($data){
			if($data['Tipo']=="Count"){
				$query=self::connect()->prepare("SELECT id FROM correos_enviados");
			}elseif($data['Tipo']=="Only"){
				$query=self::connect()->prepare("SELECT ce.*, concat(e.Nombres+ ' '+e.Apellidos) as nombres, c.Usuario,ce.archivos_adjuntos as Adjuntos FROM `correos_enviados` ce LEFT JOIN estudiante e on e.Codigo=ce.usuario_id LEFT JOIN cuenta c on c.Codigo = ce.usuario_id WHERE ce.id=:id");
				$query->bindParam(":id",$data['id']);
			}
			$query->execute();
			return $query;
		}
	}