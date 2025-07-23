<?php
if ($actionsRequired) {
    require_once "../core/mainModel.php";
} else {
    require_once "./core/mainModel.php";
}

class reporteCursoModel extends mainModel {

    /*----------  Total de participantes en el curso  ----------*/
     public function total_participantes_model($anio = null, $norma = '', $alumno = '') {
        $sql = "SELECT COUNT(*) AS total FROM curso_alumno ca LEFT JOIN estudiante e on e.Codigo=ca.id_alumno LEFT JOIN curso_clase cc on cc.id_curso=ca.id_curso JOIN clase cl ON cc.id_clase = cl.id JOIN
                curso cu on cu.id_curso=cc.id_curso WHERE 1=1
";
        
        if ($anio) {
            $sql .= " AND YEAR(ca.fecha_termino) = :anio";
        }
        if ($norma) {
            $sql .= " AND cu.Norma = :norma";
        }
        if ($alumno!=0) {
                $sql .= " AND e.Tipo = :alumno";
            }

        $query = self::connect()->prepare($sql);
        
        if ($anio) {
            $query->bindParam(":anio", $anio, PDO::PARAM_INT);
        }
        if ($norma) {
            $query->bindParam(":norma", $norma, PDO::PARAM_STR);
        }
        if ($alumno!=0) {
            $query->bindParam(":alumno", $alumno, PDO::PARAM_STR);
        }

        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }


    /*----------  Satisfacción promedio por pregunta  ----------*/
    public function obtener_satisfaccion_model($anio = null, $norma = '', $alumno = '') {
        $sql = "
            SELECT er.id_clase, cl.Titulo AS nombre_clase, 
            AVG( (CASE WHEN ter.respuesta_usuario = er.opcion1 
            THEN 3 WHEN ter.respuesta_usuario = er.opcion2 
            THEN 2 WHEN ter.respuesta_usuario = er.opcion3 THEN 1 ELSE 0 END) ) * 100 / 3 AS porcentaje_satisfaccion 
            FROM encuesta_rapida er 
            JOIN encuesta_respuesta ter ON er.id_pregunta = ter.id_pregunta 
            JOIN clase cl ON er.id_clase = cl.id
            JOIN 
                curso_clase cc on cc.id_clase=cl.id 
            JOIN
                curso cu on cu.id_curso=cc.id_curso
            JOIN estudiante e on e.Codigo=ter.id_usuario
            WHERE 1=1
        ";
        
        if ($anio) {
            $sql .= " AND YEAR(cl.Fecha) = :anio";
        }
        if ($norma) {
            $sql .= " AND cu.Norma = :norma";
        }
        if ($alumno!=0) {
                $sql .= " AND e.Tipo = :alumno";
        }

        $sql .= " GROUP BY er.id_clase, cl.Titulo";

        $query = self::connect()->prepare($sql);

        if ($anio) {
            $query->bindParam(":anio", $anio, PDO::PARAM_INT);
        }
        if ($norma) {
            $query->bindParam(":norma", $norma, PDO::PARAM_STR);
        }
         if ($alumno!=0) {
            $query->bindParam(":alumno", $alumno, PDO::PARAM_STR);
        }

        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /*----------  Aprendizaje (notas por alumno)  ----------*/
    public function obtener_aprendizaje_model($anio = null, $norma = '', $alumno = '') {
        $sql = "
            SELECT 
                CONCAT(e.Nombres, ' ', e.Apellidos) AS Nombre,
                nu.id_usuario,
                AVG(nu.nota) AS promedio_nota
            FROM 
                notas_usuario nu
            JOIN 
                cuenta c ON nu.id_usuario = c.Codigo
            JOIN 
                estudiante e ON e.Codigo = c.Codigo
            JOIN 
                clase cl ON nu.id_clase = cl.id
            JOIN 
                curso_clase cc on cc.id_clase=cl.id 
            JOIN
                curso cu on cu.id_curso=cc.id_curso
            WHERE 1=1
        ";
        
        if ($anio) {
            $sql .= " AND YEAR(nu.fecha_respuesta) = :anio";
        }
        if ($norma) {
            $sql .= " AND cu.Norma = :norma";
        }
        if ($alumno!=0) {
                $sql .= " AND e.Tipo = :alumno";
        }

        $sql .= " GROUP BY nu.id_usuario";

        $query = self::connect()->prepare($sql);

        if ($anio) {
            $query->bindParam(":anio", $anio, PDO::PARAM_INT);
        }
        if ($norma) {
            $query->bindParam(":norma", $norma, PDO::PARAM_STR);
        }
        if ($alumno!=0) {
            $query->bindParam(":alumno", $alumno, PDO::PARAM_STR);
        }

        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /*----------  Evaluación correcta por clase  ----------*/
   public function evaluaciones_por_clase_model($anio = null, $norma = '', $alumno = '') {
        $sql ="
            SELECT cl.Titulo AS Clase, 
            SUM(CASE WHEN ra.respuesta_usuario = pr.respuesta THEN 1 ELSE 0 END) AS aciertos,
            COUNT(*) AS total 
            FROM preguntas_rapidas pr 
            JOIN respuestas_alumnos ra ON pr.id_pregunta = ra.id_pregunta 
            JOIN clase cl ON pr.id_clase = cl.id
             JOIN 
                curso_clase cc on cc.id_clase=cl.id 
            JOIN
                curso cu on cu.id_curso=cc.id_curso
            JOIN estudiante e on e.Codigo=ra.id_usuario
            WHERE 1=1
        ";
        
        if ($anio) {
            $sql .= " AND YEAR(cl.Fecha) = :anio";
        }
        if ($norma) {
            $sql .= " AND cu.Norma = :norma";
        }
        if ($alumno!=0) {
                $sql .= " AND e.Tipo = :alumno";
        }

        $sql .= " GROUP BY cl.id";

        $query = self::connect()->prepare($sql);

        if ($anio) {
            $query->bindParam(":anio", $anio, PDO::PARAM_INT);
        }
        if ($norma) {
            $query->bindParam(":norma", $norma, PDO::PARAM_STR);
        }
        if ($alumno!=0) {
            $query->bindParam(":alumno", $alumno, PDO::PARAM_STR);
        }

        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}