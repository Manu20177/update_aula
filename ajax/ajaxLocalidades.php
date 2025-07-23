<?php
	$actionsRequired=true;

    require_once "../controllers/studentController.php";

    $controller = new studentController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        $controller->load_locations_controller();
    }
?>