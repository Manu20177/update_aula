<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
if ($actionsRequired) {
	require_once "../core/mainModel.php";
} else {
	require_once "./core/mainModel.php";
}

class verificarcursoController extends mainModel {

	public function validarCursoInscrito($datos) {
		$conexion = mainModel::connect();

		$id_alumno = $datos['id_alumno'];
		$id_curso  = $datos['cod'];
		$titulo    = $datos['titulo'];
		$fecha     = $datos['fecha'];
		$portada   = $datos['portada'];

		$query = $conexion->prepare("SELECT * FROM curso_alumno WHERE id_alumno = :id_alumno AND id_curso = :id_curso");
		$query->bindParam(":id_alumno", $id_alumno);
		$query->bindParam(":id_curso", $id_curso);
		$query->execute();

		if ($query->rowCount() > 0) {
			// Ya está inscrito → redirigir a cursoclase
			$_SESSION["curso_inscrito"] = [
				"titulo" => $titulo,
				"portada" => $portada,
				"cod" => $id_curso,
				"id_alumno" => $id_alumno
			];
			 echo '
                <script>
                    window.location.href = "'.SERVERURL.'cursoclases/";
                </script>
            ';
			exit;
		} else {
			// No está inscrito → guardar datos y redirigir a cursoclave
			$_SESSION["curso_seleccionado"] = [
				"titulo" => $titulo,
				"fecha" => $fecha,
				"portada" => $portada,
				"cod" => $id_curso,
				"id_alumno" => $id_alumno
			];
			 echo '
                <script>
                    window.location.href = "'.SERVERURL.'cursoclave/";
                </script>
            ';
			exit;
		}
	}
	
	
}

// Ejecutar si llega POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$controller = new verificarcursoController();
	$controller->validarCursoInscrito($_POST);
}
