<?php
    session_start(); // Importante si usas $_SESSION['userKey']
	$actionsRequired=true;

	require_once "../core/configGeneral.php"; // Cambia esta ruta según donde esté tu archivo

    require_once "../controllers/cursoController.php";

    $controller = new cursoController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $busqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : "";
        echo $controller->pagination_curso_card_controller(1, 10, $busqueda);
        exit();
    }
?>