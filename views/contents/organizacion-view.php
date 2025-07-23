<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="zmdi zmdi-menu zmdi-hc-fw"></i> Organiza tus Clases </h1>
    </div>
    <p class="lead">En esta sección puedes organizar las clases registradas en el sistema.</p>
</div>

<?php
// Instanciar controlador de organizador
require_once "./controllers/organizaController.php";
$organizaController = new organizaController();

$query = $organizaController->execute_single_query("
    SELECT * from curso c 
    LEFT JOIN tipo_curso tc on tc.id_tipoc=c.Norma 
    WHERE EXISTS(SELECT * from curso_clase cc WHERE cc.id_curso=c.id_curso) 
    ORDER BY c.Titulo ASC
");

$contar = $query->rowCount();
?>

<div class="col-xs-12 col-sm-12">
    <div class="form-group label-floating">
        <?php if ($contar != 0): ?>
        <span class="control-label">Selecciona el Curso *</span>

        <!-- Campo de búsqueda -->
        <input type="text" id="filtroCursos" placeholder="Buscar curso..." class="form-control" style="margin-bottom:10px;">

        <!-- Select de cursos -->
        <select class="form-control" name="curso_select" id="curso_select" size="5" required>
            <?php foreach ($query as $key): 
                $cod_curso = $key['id_curso'];
                $curso = $key['Titulo'];
                $norma = $key['norma'];
            ?>
                <option value="<?= $cod_curso ?>">
                    <?= $curso ?> | Norma: <?= $norma ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php else: ?>
            <div class="alert alert-warning text-center" role="alert">
                No hay cursos con clases asociadas.
            </div>
        <?php endif; ?>
    </div>

    <!-- Aquí se inyectará la lista de clases -->
    <div id="contenedor-clases">
        <!-- Bloques de clase aparecerán aquí -->
    </div>
</div>

<!-- Incluir jQuery antes de cualquier script personalizado -->
<script src="<?php echo SERVERURL; ?>views/js/jquery-3.1.1.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const selectCurso = document.getElementById("curso_select");
    const contenedorClases = document.getElementById("contenedor-clases");
    const filtroCursos = document.getElementById("filtroCursos");

    const opcionesOriginales = Array.from(selectCurso.options);

    function filtrarOpciones(termino) {
        selectCurso.innerHTML = "";

        if (!termino.trim()) {
            opcionesOriginales.forEach(op => {
                selectCurso.add(op.cloneNode(true));
            });
        } else {
            const terminoLower = termino.toLowerCase();
            opcionesOriginales.forEach(op => {
                if (op.text.toLowerCase().includes(terminoLower)) {
                    selectCurso.add(op.cloneNode(true));
                }
            });
        }

        if (selectCurso.options.length > 0) {
            selectCurso.selectedIndex = 0;
            cargarClases(selectCurso.value);
        } else {
            contenedorClases.innerHTML = `
                <div class='row'>
                    <div class='col-xs-12'>
                        <div class='alert alert-info text-center'>No se encontraron coincidencias.</div>
                    </div>
                </div>`;
        }
    }

    function cargarClases(id_curso) {
        contenedorClases.innerHTML = "<div class='text-center'>Cargando clases...</div>";

        fetch("<?php echo SERVERURL; ?>ajax/ajaxCargarClases.php?id_curso=" + encodeURIComponent(id_curso))
            .then(response => response.text())
            .then(data => {
                if (!data.trim()) {
                    contenedorClases.innerHTML = `
                        <div class='row'>
                            <div class='col-xs-12'>
                                <div class='alert alert-warning text-center'>No hay clases en este curso.</div>
                            </div>
                        </div>`;
                } else {
                    contenedorClases.innerHTML = data;
                }
            })
            .catch(() => {
                contenedorClases.innerHTML = `
                    <div class='row'>
                        <div class='col-xs-12'>
                            <div class='alert alert-danger text-center'>Error al cargar las clases.</div>
                        </div>
                    </div>`;
            });
    }

    // Evento para guardar el orden
    // Evento para guardar el orden
    document.addEventListener("click", function(e) {
        if (e.target && e.target.id === "guardarOrden") {
            const lista = document.getElementById("lista-clases");
            const items = lista.querySelectorAll("li");

            // Recolectar los nuevos órdenes
            const ids = Array.from(items).map((li, index) => ({
                id: li.dataset.id,
                orden: parseInt(li.querySelector(".nuevo-orden").value)
            }));

            // Enviar datos al servidor
            fetch("<?php echo SERVERURL; ?>ajax/ajaxGuardarOrdenClases.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(ids)
            })
            .then(res => res.text())
            .then(html => {
                // Crear un contenedor temporal para insertar el HTML recibido
                const tempDiv = document.createElement("div");
                tempDiv.innerHTML = html;

                // Buscar todos los <script> dentro del HTML devuelto
                const scripts = tempDiv.querySelectorAll("script");

                // Inyectar el contenido en el DOM (por si hay más respuestas)
                document.body.appendChild(tempDiv);

                // Ejecutar cada script encontrado
                scripts.forEach(script => {
                    eval(script.textContent);
                });
            })
            .catch(err => {
                console.error("Error al guardar:", err);
                Swal.fire({
                    title: "Error",
                    text: "Hubo un problema al conectar con el servidor.",
                    icon: "error"
                });
            });
        }
    });

    // Filtro de búsqueda
    if (filtroCursos) {
        filtroCursos.addEventListener("input", function () {
            filtrarOpciones(this.value);
        });
    }

    // Cambio de curso
    selectCurso.addEventListener("change", function () {
        const id_curso = this.value;
        if (id_curso) {
            cargarClases(id_curso);
        } else {
            contenedorClases.innerHTML = "";
        }
    });

    // Cargar automáticamente si hay una opción seleccionada
    const cursoSeleccionado = selectCurso.value;
    if (cursoSeleccionado) {
        cargarClases(cursoSeleccionado);
    }
});
</script>