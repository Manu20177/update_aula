<?php
$actionsRequired = true;
require_once "../controllers/encuestaController.php";

$id_pregunta = isset($_GET['id_pregunta']) ? intval($_GET['id_pregunta']) : 0;
$id_clase = isset($_GET['id_clase']) ? intval($_GET['id_clase']) : 0;

if ($id_pregunta <= 0 || $id_clase <= 0) {
    echo json_encode(["success" => false, "message" => "Datos inválidos"]);
    exit;
}

$encuestaController = new encuestaController();
$result = $encuestaController->eliminar_preguntaevalua_controller($id_pregunta);

echo json_encode([
    "success" => $result,
    "message" => $result ? "Pregunta eliminada correctamente" : "Error al eliminar la pregunta"
]);