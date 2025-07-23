<?php
$actionsRequired = true;

require_once "../controllers/preguntaController.php";

// Iniciar sesión
if (!isset($_SESSION)) {
    session_start();
}

// Instanciar controlador
$preguntaController = new preguntaController();

// Verificar sesión
if (!isset($_SESSION['userKey'])) {
    echo self::sweet_alert_single([
        "title" => "Error",
        "text" => "Debes iniciar sesión primero.",
        "type" => "error"
    ]);
    exit;
}

// Procesar envío del formulario
if (isset($_POST['id_clase']) && isset($_POST['preguntas'])) {

    $id_usuario = $_SESSION['userKey'];
    $id_clase = intval($_POST['id_clase']);
    $preguntas = $_POST['preguntas']; // Array asociativo id_pregunta => respuesta

    // Verificar si ya respondió esta evaluación
    echo $preguntaController->check_evaluacion_respondida_controller($id_usuario, $id_clase);
    

    // Este método ya muestra su propia alerta (success o error)
    // En caso de éxito:
    echo $preguntaController->guardar_respuestas_controller($id_usuario, $id_clase, $preguntas);
}