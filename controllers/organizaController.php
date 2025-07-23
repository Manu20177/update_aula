<?php
require_once __DIR__ . '/../core/configGeneral.php';

if($actionsRequired){
    require_once "../models/organizaModel.php";
}else{ 
    require_once "./models/organizaModel.php";
}

class organizaController extends organizaModel {

    /*----------  Cargar clases por curso  ----------*/
    public function cargar_clases_controller($id_curso) {
        $id_curso = self::clean_string($id_curso);
        $resultado = self::get_clases_by_curso_model($id_curso);

        if ($resultado->rowCount() > 0) {
            return $resultado->fetchAll();
        } else {
            return [];
        }
    }

    /*----------  Guardar nuevo orden de clases  ----------*/
    public function guardar_orden_clases_controller($ordenes) {
        if(self::update_orden_clases_model($ordenes)){
			$dataAlert=[
                "title" => "¡Éxito!",
                "text" => "El orden de las clases ha sido actualizado correctamente.",
                "type" => "success"
            ];
            $url=SERVERURL."organizacion";
            return self::sweet_alert_url_reload($dataAlert,$url);
        }else{
            $dataAlert=[
               "title" => "¡Ocurrió un error!",
                "text" => "No se pudo guardar el nuevo orden. Inténtalo nuevamente.",
                "type" => "error"
            ];
            $url=SERVERURL."organizacion";
            return self::sweet_alert_url_reload($dataAlert,$url);
        }
    }
}