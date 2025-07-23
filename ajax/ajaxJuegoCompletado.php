<?php
session_start();
$actionsRequired = true;

require_once "../controllers/juegosController.php";

$juegoCtrl = new juegosController();

if (isset($_POST['id_juego']) && isset($_SESSION['userKey'])) {
    $id_alumno = $_SESSION['userKey'];
    $id_juego  = $_POST['id_juego'];

    if (!empty($id_juego)) {
        $juegoCtrl->registrar_juego_completado_controller($id_alumno, $id_juego);
        echo "Registrado";
    } else {
        echo "Error: ID vacío";
    }
} else {
    echo "Error: Datos incompletos";
}
?>
