<?php
$actionsRequired = true;
if ($actionsRequired) {
    require_once "../models/reporteCursoModel.php";
} else {
    require_once "./models/reporteCursoModel.php";
}

class reporteCursoController extends reporteCursoModel {

    // Nuevo método para el reporte de satisfacción y aprendizaje
    public function get_datos_reporte_satisfaccion_aprendizaje($anio = null, $norma = '', $alumno = '') {
    $data['total']        = $this->total_participantes_model($anio, $norma, $alumno);
    $data['satisfaccion'] = $this->obtener_satisfaccion_model($anio, $norma, $alumno);
    $data['aprendizaje']  = $this->obtener_aprendizaje_model($anio, $norma, $alumno);
    $data['clases']       = $this->evaluaciones_por_clase_model($anio, $norma, $alumno);

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
}

// Llamada directa desde la URL

if (basename($_SERVER['PHP_SELF']) == 'reporteCursoController.php') {
    $controller = new reporteCursoController();

    // Recibir año y norma como parámetros
    $anio = isset($_GET['anio']) ? intval($_GET['anio']) : null;
    $norma = isset($_GET['norma']) ? trim($_GET['norma']) : '';
    $alumno = isset($_GET['alumno']) ? trim($_GET['alumno']) : '';


    $controller->get_datos_reporte_satisfaccion_aprendizaje($anio, $norma, $alumno);
}