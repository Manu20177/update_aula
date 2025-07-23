<?php
define('actionsRequired', true); // Ajusta esto según tu estructura

require_once './core/mainModel.php';
require_once './models/reporteModel.php';

$model = new reporteModel();

echo "<pre>";
print_r($model->total_participantes_model(2025,"Educacion Financiera"));
print_r($model->participantes_genero_model(2025,"Educacion Financiera"));
print_r($model->nivel_estudios_genero_model(2025,"Educacion Financiera"));
print_r($model->provincia_genero_model(2025,"Educacion Financiera"));
print_r($model->actividad_economica_genero_model(2025,"Educacion Financiera"));
print_r($model->etnia_genero_model(2025,"Educacion Financiera"));
echo "</pre>";


// require_once './core/mainModel.php';
// require_once './models/reporteCursoModel.php';

// $model = new reporteCursoModel();

// echo "<pre>";
// print_r($model->total_participantes_model(2025,"Todas"));
// print_r($model->obtener_satisfaccion_model());
// print_r($model->obtener_aprendizaje_model());
// print_r($model->evaluaciones_por_clase_model());

// echo "</pre>";