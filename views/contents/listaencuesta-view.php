<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="zmdi zmdi-tv-list zmdi-hc-fw"></i> Encuesta <small>(Listado)</small></h1>
    </div>
    <p class="lead">
        En esta sección puede ver las Encuestas registradas en el sistema, o eliminar una encuesta cuando lo desee.
    </p>
</div>

<div class="container-fluid">
    <ul class="breadcrumb breadcrumb-tabs">
        <li class="active">
            <a href="<?php echo SERVERURL; ?>encuestaadd/" class="btn btn-info">
                <i class="zmdi zmdi-plus"></i> Nuevo
            </a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>listaencuesta/" class="btn btn-success">
                <i class="zmdi zmdi-format-list-bulleted"></i> Lista
            </a>
        </li>
    </ul>
</div>

<?php
// Instanciar controlador de preguntas
require_once "./controllers/encuestaController.php";
$encuestaController = new encuestaController();

$query = $encuestaController->execute_single_query("
    SELECT id_clase, Titulo, Tutor,
    CASE WHEN SUM(pregunta_sin_responder) > 0 THEN 'Editable'
         ELSE 'Ya respondida' END AS estado_encuesta
    FROM (
        SELECT c.id AS id_clase, c.Titulo, c.Tutor,
               CASE WHEN COUNT(ere.id_respuesta) = 0 THEN 1 ELSE 0 END AS pregunta_sin_responder
        FROM clase c
        INNER JOIN encuesta_rapida er ON c.id = er.id_clase
        LEFT JOIN encuesta_respuesta ere ON er.id_pregunta = ere.id_pregunta
        GROUP BY c.id, c.Titulo, c.Tutor, er.id_pregunta
    ) AS subconsulta
    GROUP BY id_clase, Titulo, Tutor
");

$contar = $query->rowCount();
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
                $estado = $key['estado_encuesta'];
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
    const filtroCursos = document.getElementById("filtroCursos"); // Campo de búsqueda
    let estadoEncuestaActual = '';

    // Guardamos todas las opciones iniciales del select
    const opcionesOriginales = Array.from(selectClase.options);

    // ===== Función para filtrar opciones =====
    function filtrarOpciones(termino) {
        // Limpiamos el select
        selectClase.innerHTML = "";

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

        // Si queda alguna opción seleccionada, recargar encuesta
        if (selectClase.options.length > 0) {
            selectClase.selectedIndex = 0;
            cargarEncuesta(selectClase.value);
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

    // ===== Cargar Encuesta =====
    function cargarEncuesta(id_clase) {
        const selectedOption = selectClase.options[selectClase.selectedIndex];
        const estadoTexto = selectedOption.text || selectedOption.innerText;
        estadoEncuestaActual = estadoTexto.includes("Editable") ? "Editable" : "Ya respondida";

        contenedorEncuesta.innerHTML = "<div class='text-center'>Cargando encuesta...</div>";

        fetch("<?php echo SERVERURL; ?>ajax/ajaxCargarEncuesta.php?id_clase=" + id_clase)
            .then(response => response.text())
            .then(data => {
                if (!data.trim()) {
                    contenedorEncuesta.innerHTML = `
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="alert alert-warning text-center" role="alert">
                                    No existen preguntas en esta encuesta.
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
                                Error al cargar la encuesta.
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
            cargarEncuesta(id_clase);
        } else {
            contenedorEncuesta.innerHTML = "";
        }
    });

    // Cargar automáticamente si hay una opción seleccionada por defecto
    const claseSeleccionada = selectClase.value;
    if (claseSeleccionada) {
        cargarEncuesta(claseSeleccionada);
    }

    // Delegación de eventos para botones dinámicos
    contenedorEncuesta.addEventListener("click", function(e) {
        // Eliminar pregunta individual
        const deleteBtnPregunta = e.target.closest(".delete-btn-pregunta");
        if (deleteBtnPregunta) {
            if (estadoEncuestaActual !== "Editable") {
                alert("No puedes eliminar preguntas porque la encuesta ya fue respondida.");
                return;
            }

            const id_pregunta = deleteBtnPregunta.dataset.idpregunta;
            const id_clase = deleteBtnPregunta.dataset.idclase;

            if (confirm("¿Estás seguro de eliminar esta pregunta?")) {
                fetch("<?php echo SERVERURL; ?>ajax/ajaxEliminarPregunta.php?id_pregunta=" + id_pregunta + "&id_clase=" + id_clase)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert("Pregunta eliminada correctamente.");
                            cargarEncuesta(id_clase); // Recargar encuesta
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

        // Eliminar toda la encuesta
        const deleteBtnAll = e.target.closest(".delete-btn-all");
        if (deleteBtnAll) {
            if (estadoEncuestaActual !== "Editable") {
                alert("No puedes eliminar la encuesta porque ya fue respondida.");
                return;
            }

            const id_clase = deleteBtnAll.dataset.idclase;

            if (confirm("¿Estás seguro de eliminar toda la encuesta? Esta acción no se puede deshacer.")) {
                fetch("<?php echo SERVERURL; ?>ajax/ajaxEliminarEncuesta.php?id_clase=" + id_clase)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert("Encuesta eliminada completamente.");
                            window.location.href = "<?php echo SERVERURL; ?>listaencuesta/";
                        } else {
                            alert("Error al eliminar la encuesta.");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Hubo un error al eliminar la encuesta.");
                    });
            }
        }
    });
});
</script>