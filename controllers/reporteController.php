<?php
$actionsRequired=True;
if ($actionsRequired) {
    require_once "../models/reporteModel.php";
} else {
    require_once "./models/reporteModel.php";
}

class reporteController extends reporteModel {

    public function get_datos_reporte($anio = null, $norma = '', $alumno = '') {
        // Pasamos los parámetros en el orden correcto esperado por los modelos
        $data['total']     = $this->total_participantes_model($anio, $norma, $alumno);
        $data['genero']    = $this->participantes_genero_model($anio, $norma, $alumno);
        $data['nivel']     = $this->nivel_estudios_genero_model($anio, $norma, $alumno);
        $data['provincia'] = $this->provincia_genero_model($anio, $norma, $alumno);
        $data['actividad'] = $this->actividad_economica_genero_model($anio, $norma, $alumno);
        $data['etnia']     = $this->etnia_genero_model($anio, $norma, $alumno);

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

// Llamada directa
if (basename($_SERVER['PHP_SELF']) == 'reporteController.php') {
    $controller = new reporteController();
    // Recibir año y norma como parámetros
    $anio = isset($_GET['anio']) ? intval($_GET['anio']) : null;
    $norma = isset($_GET['norma']) ? trim($_GET['norma']) : '';

    $alumno = isset($_GET['alumno']) ? trim($_GET['alumno']) : '';

    $controller->get_datos_reporte($anio, $norma, $alumno);}