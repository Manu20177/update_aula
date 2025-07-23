<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-tv-list zmdi-hc-fw"></i> Evaluacion <small>(Listado)</small></h1>
	</div>
	<p class="lead">
		En esta sección puede ver las Evaluaciones registradas en el sistema, o eliminar una evaluacion cuando lo desee.
	</p>
</div>
<div class="container-fluid">
	<ul class="breadcrumb breadcrumb-tabs">
	  	<li class="active">
		  	<a href="<?php echo SERVERURL; ?>evaluacionadd/" class="btn btn-info">
		  		<i class="zmdi zmdi-plus"></i> Nuevo
		  	</a>
	  	</li>
	  	<li>
	  		<a href="<?php echo SERVERURL; ?>listaevaluacion/" class="btn btn-success">
	  			<i class="zmdi zmdi-format-list-bulleted"></i> Lista
	  		</a>
	  	</li>
	</ul>
</div>
<?php

// Instanciar controlador de preguntas
require_once "./controllers/encuestaController.php";
$encuestaController = new encuestaController();


// $query=$encuestaController->execute_single_query("SELECT DISTINCT c.* FROM clase c INNER JOIN 
// preguntas_rapidas er ON c.id = er.id_clase WHERE NOT EXISTS
//  ( SELECT 1 FROM respuestas_alumnos ere WHERE ere.id_pregunta = er.id_pregunta);




// ");
$query=$encuestaController->execute_single_query("SELECT id_clase, Titulo,Tutor, 
CASE WHEN SUM(pregunta_sin_responder) > 0 THEN 'Editable' 
ELSE 'Ya respondida' END AS estado_evaluacion 
FROM ( SELECT c.id AS id_clase, c.Titulo,c.Tutor, 
CASE WHEN COUNT(ere.id_respuesta) = 0 THEN 1
 ELSE 0 END AS pregunta_sin_responder 
 FROM clase c INNER JOIN preguntas_rapidas er ON c.id = er.id_clase 
 LEFT JOIN respuestas_alumnos ere ON er.id_pregunta = ere.id_pregunta 
 GROUP BY c.id, c.Titulo, er.id_pregunta ) AS subconsulta 
 GROUP BY id_clase, Titulo, Tutor ORDER BY `subconsulta`.`id_clase` ASC;




");
$contar=$query->rowCount();
?>
<div class="col-xs-12 col-sm-12">
    <div class="form-group label-floating">
        <?php if ($contar != 0): ?>
        <span class="control-label">Selecciona la Clase *</span>

        <!-- Campo de búsqueda -->
        <input type="text" id="filtroCursos" placeholder="Buscar clase..." class="form-control" style="margin-bottom:10px;">

        <!-- Select de cursos -->
        <select class="form-control" name="clase_select" id="clase_select" size="5" required>
            <?php foreach ($query as $key): 
                $cod_clase = $key['id_clase'];
                $clase = $key['Titulo'];
                $tutor = $key['Tutor'];
                $estado = $key['estado_evaluacion'];
                $color = ($estado == 'Editable') ? 'green' : 'red';
            ?>
                <option value="<?= $cod_clase ?>" style="color:<?= $color ?>;">
                    Clase: <?= $clase ?> | Tutor: <?= $tutor ?> | Estado: <?= $estado ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php else: ?>
            <div class="alert alert-warning text-center" role="alert">
                No hay encuestas registradas en ninguna clase.
            </div>
        <?php endif; ?>
        
    </div>

    <!-- Aquí se inyectará la encuesta justo debajo del select -->
    <div id="contenedor-encuesta">
        <!-- Aquí aparecerán las preguntas cuando se seleccione una clase -->
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const selectClase = document.getElementById("clase_select");
    const contenedorEncuesta = document.getElementById("contenedor-encuesta");
    const filtroCursos = document.getElementById("filtroCursos");

    let estadoEvaluacionActual = '';
    const opcionesOriginales = Array.from(selectClase.options); // Guardamos las opciones iniciales

    // ===== Función para filtrar el select =====
    function filtrarOpciones(termino) {
        selectClase.innerHTML = ""; // Limpiamos el select

        if (!termino.trim()) {
            // Si no hay término, restauramos todas las opciones originales
            opcionesOriginales.forEach(op => {
                selectClase.add(op.cloneNode(true));
            });
        } else {
            const terminoLower = termino.toLowerCase();
            opcionesOriginales.forEach(op => {
                if (op.text.toLowerCase().includes(terminoLower)) {
                    selectClase.add(op.cloneNode(true));
                }
            });
        }

        // Recargar la primera opción si queda alguna
        if (selectClase.options.length > 0) {
            selectClase.selectedIndex = 0;
            cargarEvaluacion(selectClase.value);
        } else {
            contenedorEncuesta.innerHTML = `
                <div class="row">
                    <div class="col-xs-12">
                        <div class="alert alert-info text-center" role="alert">
                            No se encontraron coincidencias.
                        </div>
                    </div>
                </div>
            `;
        }
    }

    // ===== Cargar Evaluación =====
    function cargarEvaluacion(id_clase) {
        const selectedOption = selectClase.options[selectClase.selectedIndex];
        const estadoTexto = selectedOption.text || selectedOption.innerText;
        estadoEvaluacionActual = estadoTexto.includes("Editable") ? "Editable" : "Ya respondida";

        contenedorEncuesta.innerHTML = "<div class='text-center'>Cargando evaluación...</div>";

        fetch("<?php echo SERVERURL; ?>ajax/ajaxCargarEvaluacion.php?id_clase=" + id_clase)
            .then(response => response.text())
            .then(data => {
                if (!data.trim()) {
                    contenedorEncuesta.innerHTML = `
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="alert alert-warning text-center" role="alert">
                                    No existen preguntas en esta evaluación.
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    contenedorEncuesta.innerHTML = data;
                }
            })
            .catch(() => {
                contenedorEncuesta.innerHTML = `
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="alert alert-danger text-center" role="alert">
                                Error al cargar la evaluación.
                            </div>
                        </div>
                    </div>
                `;
            });
    }

    // ===== Eventos =====

    // Filtro de búsqueda
    if (filtroCursos) {
        filtroCursos.addEventListener("input", function () {
            filtrarOpciones(this.value);
        });
    }

    // Cambio de clase
    selectClase.addEventListener("change", function () {
        const id_clase = this.value;
        if (id_clase) {
            cargarEvaluacion(id_clase);
        } else {
            contenedorEncuesta.innerHTML = "";
        }
    });

    // Cargar automáticamente si hay una opción seleccionada por defecto
    const claseSeleccionada = selectClase.value;
    if (claseSeleccionada) {
        cargarEvaluacion(claseSeleccionada);
    }

    // Delegación de eventos para botones dinámicos
    contenedorEncuesta.addEventListener("click", function(e) {
        // Eliminar pregunta individual
        const deleteBtnPregunta = e.target.closest(".delete-btn-pregunta");
        if (deleteBtnPregunta) {
            if (estadoEvaluacionActual !== "Editable") {
                alert("No puedes eliminar preguntas porque la evaluación ya fue respondida.");
                return;
            }

            const id_pregunta = deleteBtnPregunta.dataset.idpregunta;
            const id_clase = deleteBtnPregunta.dataset.idclase;

            if (confirm("¿Estás seguro de eliminar esta pregunta?")) {
                fetch("<?php echo SERVERURL; ?>ajax/ajaxEliminarPreguntaevalua.php?id_pregunta=" + id_pregunta + "&id_clase=" + id_clase)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert("Pregunta eliminada correctamente.");
                            cargarEvaluacion(id_clase); // Recargar evaluación
                        } else {
                            alert("Error al eliminar la pregunta.");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Hubo un error al procesar la solicitud.");
                    });
            }
        }

        // Eliminar toda la evaluación
        const deleteBtnAll = e.target.closest(".delete-btn-all");
        if (deleteBtnAll) {
            if (estadoEvaluacionActual !== "Editable") {
                alert("No puedes eliminar la evaluación porque ya fue respondida.");
                return;
            }

            const id_clase = deleteBtnAll.dataset.idclase;

            if (confirm("¿Estás seguro de eliminar toda la evaluación? Esta acción no se puede deshacer.")) {
                fetch("<?php echo SERVERURL; ?>ajax/ajaxEliminarEvaluacion.php?id_clase=" + id_clase)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert("Evaluación eliminada completamente.");
                            window.location.href = "<?php echo SERVERURL; ?>listaevaluacion/";
                        } else {
                            alert("Error al eliminar la evaluación.");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Hubo un error al eliminar la evaluación.");
                    });
            }
        }
    });
});
</script>