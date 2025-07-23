<?php
    if($actionsRequired){
        require_once "../core/mainModel.php";
    }else{ 
        require_once "./core/mainModel.php";
    }

    class organizaModel extends mainModel {

                // En organizaModel.php, dentro de la clase organizaModel

        /*----------  Obtener clases por curso  ----------*/
        public function get_clases_by_curso_model($id_curso) {
            $sql = "SELECT cc.id_curso_clase, c.id, c.Titulo, cc.orden 
                    FROM curso_clase cc
                    INNER JOIN clase c ON c.id = cc.id_clase
                    WHERE cc.id_curso = '$id_curso'
                    ORDER BY cc.orden ASC";

            return self::execute_single_query($sql);
        }

        /*----------  Actualizar orden de clases  ----------*/
       public function update_orden_clases_model($ordenes) {
            try {
                foreach ($ordenes as $item) {
                    $id_curso_clase = intval($item['id']);
                    $orden = intval($item['orden']);

                    if ($id_curso_clase <= 0 || $orden <= 0) continue;

                    $sql = "UPDATE curso_clase 
                            SET orden = '$orden' 
                            WHERE id_curso_clase = '$id_curso_clase'";

                    self::execute_single_query($sql);
                }

                return true;
            } catch (Exception $e) {
                error_log("Error al actualizar orden: " . $e->getMessage());
                return false;
            }
        }
    }