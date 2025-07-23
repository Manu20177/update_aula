<?php
if ($actionsRequired) {
    require_once "../core/mainModel.php";
} else {
    require_once "./core/mainModel.php";
}

class reporteModel extends mainModel {

    /*----------  Total de participantes (con filtros)  ----------*/
    public function total_participantes_model($anio = null, $norma = '', $alumno = '') {
        $sql = "SELECT COUNT(*) AS total 
        FROM cuenta c
        LEFT JOIN estudiante e on e.Codigo=c.Codigo
        WHERE c.Privilegio = 4";

        $sql .= " AND 1=1"; // Placeholder para condiciones dinámicas

        if ($anio || $norma || $alumno) {
            $sql .= "
                AND EXISTS( SELECT 1 
                            FROM curso_alumno ca 
                            JOIN curso_clase cc ON ca.id_curso = cc.id_curso 
                            JOIN curso cu ON cc.id_curso = cu.id_curso 
                            WHERE ca.id_alumno = c.Codigo
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
            $sql .= ")";
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

    /*----------  Participantes por género (con filtros)  ----------*/
    public function participantes_genero_model($anio = null, $norma = '', $alumno = '') {
        $sql = "
            SELECT c.Genero, COUNT(*) AS total 
            FROM cuenta c 
            LEFT JOIN estudiante e on e.Codigo=c.Codigo
            WHERE c.Privilegio = 4";

        if ($anio || $norma || $alumno) {
            $sql .= "
                AND EXISTS( SELECT 1 
                            FROM curso_alumno ca 
                            JOIN curso_clase cc ON ca.id_curso = cc.id_curso 
                            JOIN curso cu ON cc.id_curso = cu.id_curso 
                            WHERE ca.id_alumno = c.Codigo
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
            $sql .= ")";
        }

        $sql .= " GROUP BY c.Genero";

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

    
    /*----------  Nivel educativo por género (con filtros)  ----------*/
    public function nivel_estudios_genero_model($anio = null, $norma = '', $alumno = '') {
        $sql = "
            SELECT e.Nivel, c.Genero, COUNT(*) AS total 
            FROM estudiante e 
            JOIN cuenta c ON e.Codigo = c.Codigo
            WHERE c.Privilegio = 4";

        if ($anio || $norma || $alumno) {
            $sql .= "
                AND EXISTS( SELECT 1 
                            FROM curso_alumno ca 
                            JOIN curso_clase cc ON ca.id_curso = cc.id_curso 
                            JOIN curso cu ON cc.id_curso = cu.id_curso 
                            WHERE ca.id_alumno = c.Codigo
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
            $sql .= ")";
        }

        $sql .= " GROUP BY e.Nivel, c.Genero";

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

    /*----------  Provincia por género (con filtros)  ----------*/
     public function provincia_genero_model($anio = null, $norma = '', $alumno = '') {
        $sql = "
            SELECT p.nombre as provincia, c.Genero,
             COUNT(*) AS total FROM estudiante e 
             JOIN provincias p on p.id_provincia=e.Provincia 
             JOIN cuenta c ON e.Codigo = c.Codigo WHERE c.Privilegio = 4";

        if ($anio || $norma || $alumno) {
            $sql .= "
                AND EXISTS( SELECT 1 
                            FROM curso_alumno ca 
                            JOIN curso_clase cc ON ca.id_curso = cc.id_curso 
                            JOIN curso cu ON cc.id_curso = cu.id_curso 
                            WHERE ca.id_alumno = c.Codigo
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
            $sql .= ")";
        }

        $sql .= " GROUP BY p.nombre, c.Genero";

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

    /*----------  Actividad económica por género (con filtros)  ----------*/
    public function actividad_economica_genero_model($anio = null, $norma = '', $alumno = '') {
        $sql = "
            SELECT e.Actividad, c.Genero, COUNT(*) AS total 
            FROM estudiante e 
            JOIN cuenta c ON e.Codigo = c.Codigo
            WHERE c.Privilegio = 4";

        if ($anio || $norma || $alumno) {
            $sql .= "
                AND EXISTS( SELECT 1 
                            FROM curso_alumno ca 
                            JOIN curso_clase cc ON ca.id_curso = cc.id_curso 
                            JOIN curso cu ON cc.id_curso = cu.id_curso 
                            WHERE ca.id_alumno = c.Codigo
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
            $sql .= ")";
        }

        $sql .= " GROUP BY e.Actividad, c.Genero";

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

    /*----------  Grupo étnico por género (con filtros)  ----------*/
    public function etnia_genero_model($anio = null, $norma = '', $alumno = '') {
        $sql = "
            SELECT e.Etnia, c.Genero, COUNT(*) AS total 
            FROM estudiante e 
            JOIN cuenta c ON e.Codigo = c.Codigo
            WHERE c.Privilegio = 4";

        if ($anio || $norma || $alumno) {
            $sql .= "
                AND EXISTS( SELECT 1 
                            FROM curso_alumno ca 
                            JOIN curso_clase cc ON ca.id_curso = cc.id_curso 
                            JOIN curso cu ON cc.id_curso = cu.id_curso 
                            WHERE ca.id_alumno = c.Codigo
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
            $sql .= ")";
        }

        $sql .= " GROUP BY e.Etnia, c.Genero";

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