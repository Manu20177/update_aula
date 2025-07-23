<?php
$actionsRequired = true;

require_once "../controllers/encuestaController.php";

$id_clase = isset($_GET['id_clase']) ? intval($_GET['id_clase']) : 0;

if ($id_clase <= 0) {
    echo json_encode(["success" => false, "message" => "Clase inválida"]);
    exit;
}

$encuestaController = new encuestaController();
$result = $encuestaController->eliminar_encuesta_completa_controller($id_clase);

echo json_encode([
    "success" => $result,
    "message" => $result ? "Encuesta eliminada completamente" : "Error al eliminar la encuesta"
]);