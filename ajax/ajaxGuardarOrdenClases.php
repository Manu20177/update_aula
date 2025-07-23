<?php
$actionsRequired = true;
require_once "../controllers/organizaController.php";

$data = json_decode(file_get_contents('php://input'), true);

$organizaController = new organizaController();
echo $organizaController->guardar_orden_clases_controller($data);

