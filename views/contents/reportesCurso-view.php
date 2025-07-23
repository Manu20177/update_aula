<?php 
    require_once "./controllers/cursoController.php";
    $insVideo = new cursoController();
	$tipo_curso=$insVideo->execute_single_query("SELECT * FROM `tipo_curso` ORDER BY norma ASC");
	$tipo_alumno=$insVideo->execute_single_query("SELECT * FROM `tipo_usuario`");

?>
<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="zmdi zmdi-chart"></i> Reporte de Curso - Satisfacción y Aprendizaje</h1>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-success">
                <div class="panel-body">

                    <!-- Botón Imprimir -->
                    <div class="row">
                        <div class="col-xs-12 text-right">
                            <button onclick="imprimirReporte()" class="btn btn-primary btn-raised btn-sm no-print">
                                <i class="zmdi zmdi-print"></i> Imprimir Reporte
                            </button>
                            <button onclick="exportarAExcel()" class="btn btn-success btn-raised btn-sm no-print">
                            <i class="zmdi zmdi-download"></i> Exportar a Excel
                        </button>
                        </div>
                    </div>
              

                    <!-- Selector de Año y Norma -->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4">
                                <label for="anioSelector">Selecciona un año:</label>
                                <select id="anioSelector" onchange="cargarDatosPorAnio()" class="form-control" style="width: auto; display: inline-block;">
                                    <option value="">-- Seleccionar --</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                </select>
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <div class="form-group label-floating">
                                    <span class="control-label">Norma *</span>
                                    <select id="normaSelector" onchange="cargarDatosPorAnio()" class="form-control" name="norma">
                                        <?php foreach ($tipo_curso as $fila): ?>
                                            <option value="<?= htmlspecialchars($fila['id_tipoc']) ?>" data-descripcion="<?= htmlspecialchars($fila['descripcion']) ?>">
                                                <?= htmlspecialchars($fila['norma']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" id="valorNormaInput" class="form-control" readonly placeholder="Selecciona una norma">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <div class="form-group label-floating">
                                    <span class="control-label">Tipo de Alumno *</span>
                                    <select id="alumnoSelector" onchange="cargarDatosPorAnio()" class="form-control" name="alumno">
                                        <option value="0">General</option>

                                        <?php foreach ($tipo_alumno as $alumno): ?>
                                            <option value="<?= htmlspecialchars($alumno['id_tipo']) ?>">
                                                <?= htmlspecialchars($alumno['tipo']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" id="valorNormaInput" class="form-control" readonly placeholder="Selecciona una norma">


                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fecha de generación -->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12">
                                <p class="text-muted">
                                    <i class="zmdi zmdi-chart"></i> FECHA DE GENERACIÓN:
                                    <strong><?= date("Y-m-d") ?></strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Total de participantes -->
                    <div id="total-participantes" class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12">
                                <h3><i class="zmdi zmdi-account-box"></i> Total de Participantes de los Cursos</h3>
                                <p><strong>Total:</strong> <span id="total-count">Cargando...</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Gráfico de Satisfacción -->
                    <div class="chart-page">
                        <h2><i class="zmdi zmdi-smile"></i> Nivel de Satisfacción por Clase</h2>
                        <canvas id="satisfaccionChart" style="max-width: 600px;"></canvas>
                        <div class="chart-description" id="satisfaccionDescription"></div>
                    </div>

                    <!-- Gráfico de Rendimiento Académico -->
                    <div class="chart-page">
                        <h2><i class="zmdi zmdi-check-circle"></i> Rendimiento Académico por Alumno</h2>
                        <canvas id="aprendizajeChart" style="max-width: 600px;"></canvas>
                        <div class="chart-description" id="aprendizajeDescription"></div>
                    </div>

                    <!-- Gráfico por Clase -->
                    <div class="chart-page">
                        <h2><i class="zmdi zmdi-bookmark"></i> Porcentaje de Aciertos por Clase</h2>
                        <canvas id="claseChart" style="max-width: 600px;"></canvas>
                        <div class="chart-description" id="claseDescription"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script de gráficos y datos -->
<script>
function generarGraficos(data) {
    // Gráfico de Satisfacción
    if (data.satisfaccion && data.satisfaccion.length > 0) {
        const labels = data.satisfaccion.map(p => p.nombre_clase);
        const valores = data.satisfaccion.map(p => parseFloat(p.porcentaje_satisfaccion).toFixed(2));
        window.satisfaccionChartInstance = new Chart(document.getElementById('satisfaccionChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Porcentaje de Satisfacción (%)',
                    data: valores,
                    backgroundColor: '#4CAF50'
                }]
            }
        });

        let desc = "<ul>";
        data.satisfaccion.forEach(p => {
            desc += `<li><strong>${p.nombre_clase}</strong>: ${parseFloat(p.porcentaje_satisfaccion).toFixed(2)}%</li>`;
        });
        desc += "</ul>";
        desc += "<p>Este gráfico muestra el nivel de satisfacción promedio por clase.</p>";
        document.getElementById("satisfaccionDescription").innerHTML = desc;
    } else {
        document.getElementById("satisfaccionDescription").innerHTML = "<p>No hay datos disponibles para este año.</p>";
    }

    // Gráfico de Aprendizaje (notas)
    if (data.aprendizaje && data.aprendizaje.length > 0) {
        const labels = data.aprendizaje.map(a => a.Nombre);
        const notas = data.aprendizaje.map(a => parseFloat(a.promedio_nota).toFixed(2));
        window.aprendizajeChartInstance = new Chart(document.getElementById('aprendizajeChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Promedio de Nota',
                    data: notas,
                    backgroundColor: '#2196F3'
                }]
            }
        });

        let desc = "<ul>";
        data.aprendizaje.forEach(a => {
            desc += `<li><strong>${a.Nombre}</strong>: Promedio: ${parseFloat(a.promedio_nota).toFixed(2)}</li>`;
        });
        desc += "</ul>";
        desc += "<p>Este gráfico muestra el promedio de notas por estudiante.</p>";
        document.getElementById("aprendizajeDescription").innerHTML = desc;
    } else {
        document.getElementById("aprendizajeDescription").innerHTML = "<p>No hay datos disponibles para este año.</p>";
    }

    // Gráfico por clase (porcentaje de acierto)
    if (data.clases && data.clases.length > 0) {
        const labels = data.clases.map(c => c.Clase);
        const aciertos = data.clases.map(c => c.aciertos);
        const totales = data.clases.map(c => c.total);
        const porcentajes = labels.map((_, i) => {
            const total = totales[i];
            return total === 0 ? 0 : ((aciertos[i] / total) * 100).toFixed(1);
        });

        window.claseChartInstance = new Chart(document.getElementById('claseChart'), {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: '% de acierto',
                    data: porcentajes,
                    borderColor: '#FF9800',
                    fill: false
                }]
            }
        });

        let desc = "<ul>";
        data.clases.forEach((c, i) => {
            const porcentaje = porcentajes[i];
            desc += `<li><strong>${c.Clase}</strong>: ${porcentaje}% de acierto</li>`;
        });
        desc += "</ul>";
        desc += "<p>Este gráfico muestra el porcentaje de aciertos por clase.</p>";
        document.getElementById("claseDescription").innerHTML = desc;
    } else {
        document.getElementById("claseDescription").innerHTML = "<p>No hay datos disponibles para este año.</p>";
    }
}
function cargarDatosPorAnio() {
    const anio = document.getElementById("anioSelector").value;
    const norma = document.getElementById("normaSelector").value;
    const alumno = document.getElementById("alumnoSelector").value;

    // Obtener el data-descripcion de esa opción
    const selectedOption = normaSelector.options[normaSelector.selectedIndex]; // obtiene la opción seleccionada

    const descripcion = selectedOption.getAttribute("data-descripcion"); // obtiene data-descripcion
    // Mostrar en el input
    document.getElementById("valorNormaInput").value = descripcion;

     // Validación básica
    if (!anio || !norma || !alumno) {
        alert("Por favor, selecciona un año, una norma y un tipo de alumno.");
        return;
    }


    // Limpiar gráficos anteriores si existen
    if (window.satisfaccionChartInstance) window.satisfaccionChartInstance.destroy();
    if (window.aprendizajeChartInstance) window.aprendizajeChartInstance.destroy();
    if (window.claseChartInstance) window.claseChartInstance.destroy();

    // Limpiar descripciones
    document.getElementById("satisfaccionDescription").innerHTML = "Cargando...";
    document.getElementById("aprendizajeDescription").innerHTML = "Cargando...";
    document.getElementById("claseDescription").innerHTML = "Cargando...";

    // Enviar ambos parámetros al backend
    fetch(`../controllers/reporteCursoController.php?anio=${anio}&norma=${encodeURIComponent(norma)}&alumno=${encodeURIComponent(alumno)}`)
        .then(res => res.json())
        .then(data => {
            window.reportData = data;
            document.getElementById("total-count").textContent = data.total?.total || 0;
            generarGraficos(data);
        })
        .catch(err => {
            console.error("Error al cargar los datos:", err);
            alert("No se pudieron cargar los datos del reporte");
        });
}
</script>

<!-- Estilos para impresión -->
<style>
   @media print {
        body * {
            visibility: hidden;
        }
        .panel, .panel * {
            visibility: visible;
        }
        .panel {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        canvas {
            page-break-inside: avoid;
            break-inside: avoid;
        }
        .btn {
            display: none !important;
        }
        h1, h2, p, table, thead, tbody, tfoot, tr, th, td {
            color: #000 !important;
        }
    } 
    .chart-description {
        margin-top: -20px;
        margin-bottom: 40px;
        padding: 10px;
        background-color: #f1f1f1;
        border-left: 4px solid #ccc;
        font-size: 14px;
        color: #555;
    }

    .chart-description ul {
        margin: 5px 0;
        padding-left: 20px;
    }

    @media print {
        .chart-description {
            page-break-inside: avoid;
            break-inside: avoid;
        }
    }
</style>

<script>
function imprimirReporte() {
    // Obtener los valores seleccionados
    const anioSeleccionado = document.getElementById("anioSelector").value;
    const normaSeleccionada = document.getElementById("normaSelector").value;
    var norma_descrip = document.getElementById("valorNormaInput").value;
    const select = document.getElementById("alumnoSelector");

    // Paso 2: Obtener la opción seleccionada
    const alumno = select.options[select.selectedIndex].text;


    // Validar selección
    if (!anioSeleccionado || !normaSeleccionada) {
        alert("Por favor, selecciona un año y una norma antes de imprimir.");
        return;
    }

    // Clonar contenido principal
    const clonedContent = document.querySelector(".panel-body").cloneNode(true);

    // Eliminar elementos no deseados (formularios, botones, selects, etc.)
    const unwantedElements = clonedContent.querySelectorAll("form, button, select, label, span");
    unwantedElements.forEach(el => el.remove());

    // Eliminar específicamente los elementos con texto "Total de Participantes" o "Total:"
    const elementsToRemoveByText = Array.from(clonedContent.querySelectorAll("h1, h2, h3, p"))
        .filter(el => 
            el.textContent.includes("Total de Participantes") || 
            el.textContent.includes("Total:")
        );
    elementsToRemoveByText.forEach(el => el.remove());

    // Opcional: Si sabes las clases exactas, puedes usar esto:
    const unwantedElementsByClass = clonedContent.querySelectorAll(".total-participants, .total-count");
    unwantedElementsByClass.forEach(el => el.remove());

    // Convertir cada canvas en imagen
    const canvases = document.querySelectorAll("canvas");
    const canvasList = clonedContent.querySelectorAll("canvas");

    canvasList.forEach((canvas, index) => {
        const originalCanvas = canvases[index];
        const img = document.createElement("img");
        img.src = originalCanvas.toDataURL("image/png");
        img.style.maxWidth = "100%";
        img.style.display = "block";
        img.style.margin = "0 auto";
        img.style.pageBreakInside = "avoid";

        canvas.parentNode.insertBefore(img, canvas);
        canvas.remove();
    });

    // Asegurarse de que cada gráfico esté dentro de un contenedor .chart-page
    const chartContainers = clonedContent.querySelectorAll(".chart-container"); // Cambia por tu clase real
    chartContainers.forEach(container => {
        const wrapper = document.createElement("div");
        wrapper.className = "chart-page";
        wrapper.style.breakInside = "avoid";
        wrapper.style.pageBreakInside = "avoid";
        wrapper.style.pageBreakAfter = "always";
        container.parentNode.insertBefore(wrapper, container);
        wrapper.appendChild(container);
    });

    // Crear título formal personalizado
    const titulo = document.createElement("h1");
    titulo.textContent = "Reporte de Curso - Satisfacción y Aprendizaje";
    titulo.style.textAlign = "center";
    titulo.style.fontFamily = "Arial, sans-serif";
    titulo.style.fontSize = "20px";
    titulo.style.marginBottom = "10px";
    titulo.style.color = "#000";

    // Crear subtítulo dinámico
    const subtitulo = document.createElement("p");
    subtitulo.innerHTML = "<strong>Año seleccionado:</strong> " + anioSeleccionado + "<br>" +
                          "<strong>"+ norma_descrip+"</strong> <br>"+"<strong>Tipo de Usuarios:</strong> " + alumno  ;
    subtitulo.style.textAlign = "center";
    subtitulo.style.fontFamily = "Arial, sans-serif";
    subtitulo.style.fontSize = "14px";
    subtitulo.style.marginBottom = "30px";
    subtitulo.style.color = "#333";

    // Insertar título y subtítulo AL PRINCIPIO DEL PRIMER .chart-page
    const firstChartPage = clonedContent.querySelector(".chart-page");
    if (firstChartPage) {
        firstChartPage.insertBefore(subtitulo, firstChartPage.firstChild);
        firstChartPage.insertBefore(titulo, subtitulo);
    } else {
        clonedContent.insertBefore(subtitulo, clonedContent.firstChild);
        clonedContent.insertBefore(titulo, subtitulo);
    }

    // Crear pie de página
    const footer = document.createElement("div");
    footer.innerHTML = "<p style='text-align:center; font-size:12px; color:#666;'>Documento generado automáticamente - Reporte de Curso - Satisfacción y Aprendizaje</p>";
    footer.style.pageBreakAfter = "avoid";
    footer.style.pageBreakBefore = "auto";
    footer.style.marginTop = "50px";

    // Insertar el footer dentro del ÚLTIMO .chart-page
    const allChartPages = clonedContent.querySelectorAll(".chart-page");
    if (allChartPages.length > 0) {
        const lastChartPage = allChartPages[allChartPages.length - 1];
        lastChartPage.appendChild(footer);
    } else {
        clonedContent.appendChild(footer);
    }

    // Aplicar estilo de salto de página a todos los .chart-page
    clonedContent.querySelectorAll(".chart-page").forEach(el => {
        el.style.pageBreakAfter = "always";
        el.style.breakInside = "avoid";
        el.style.pageBreakInside = "avoid";
    });

    // Abrir ventana nueva e insertar estilo profesional
    const printWindow = window.open("", "_blank");
    printWindow.document.write(`
        <html>
        <head>
            <title>Reporte de Curso - Satisfacción y Aprendizaje</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                    background-color: white;
                    color: black;
                }
                h1, h2, h3 {
                    page-break-after: avoid;
                    page-break-before: avoid;
                }
                .chart-page {
                    break-inside: avoid;
                    page-break-inside: avoid;
                    page-break-after: always;
                    padding-bottom: 20px;
                }
                img {
                    max-width: 100%;
                    display: block;
                    margin: 0 auto;
                    page-break-inside: avoid;
                    break-inside: avoid;
                }
                .chart-description {
                    break-inside: avoid;
                    page-break-inside: avoid;
                }
                /* Ocultar elementos específicos en impresión */
                .total-participants,
                .total-count {
                    display: none !important;
                }
            </style>
        </head>
        <body>${clonedContent.innerHTML}</body>
        </html>
    `);
    printWindow.document.close();

    // Imprimir después de cargar
    printWindow.onload = () => {
        setTimeout(() => {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }, 500);
    };
}
function exportarAExcel() {
    if (typeof ExcelJS === 'undefined') {
        alert("ExcelJS no está cargado.");
        return;
    }

    const anio = document.getElementById("anioSelector").value;
    const normaSelect = document.getElementById("normaSelector");
    const alumnoSelect = document.getElementById("alumnoSelector");

    const normaNombre = normaSelect.options[normaSelect.selectedIndex]?.text || "";
    const alumnoNombre = alumnoSelect.options[alumnoSelect.selectedIndex]?.text || "";

    if (!window.reportData) {
        alert("No hay datos cargados para exportar.");
        return;
    }

    const data = window.reportData;

    // Crear libro
    const wb = new ExcelJS.Workbook();

    // Hoja de filtros
    const wsFiltros = wb.addWorksheet("Filtros");
    wsFiltros.addRow(["FILTRO DE REPORTE"]);
    wsFiltros.addRow([]);
    wsFiltros.addRow(["Año:", anio]);
    wsFiltros.addRow(["Norma:", normaNombre]);
    wsFiltros.addRow(["Tipo de Alumno:", alumnoNombre]);

    // Hoja: Género
    const wsSatisfaccion = wb.addWorksheet("Satisfacción");
    wsSatisfaccion.columns = [
        { header: "Clase", key: "clase", width: 20 },
        { header: "Satisfacción (%)", key: "satisfaccion", width: 15 }
    ];
    if (data.satisfaccion && data.satisfaccion.length > 0) {
        data.satisfaccion.forEach(row => {
            wsSatisfaccion.addRow({ clase: row.nombre_clase, satisfaccion: parseFloat(row.porcentaje_satisfaccion).toFixed(2)});
        });
    } else {
        wsSatisfaccion.addRow(["No hay datos disponibles"]);
    }

    // Agregar imagen del gráfico de género
    const satisfaccionCanvas = document.getElementById("satisfaccionChart");
    if (satisfaccionCanvas) {
        const imageId = wb.addImage({
            base64: satisfaccionCanvas.toDataURL(),
            extension: 'png',
        });

        wsSatisfaccion.addImage(imageId, 'D2:F15'); // Posición y tamaño del gráfico
    }

    // Hoja: Aprendizaje
    const wsAprendizaje = wb.addWorksheet("Aprendizaje");
    wsAprendizaje.columns = [
         { header: "Estudiante", key: "estudiante", width: 25 },
        { header: "Promedio de Nota", key: "promedio_nota", width: 15 }
    ];
    if (data.aprendizaje && data.aprendizaje.length > 0) {
        data.aprendizaje.forEach(row => {
            wsAprendizaje.addRow({ estudiante: row.Nombre, promedio_nota: parseFloat(row.promedio_nota).toFixed(2) });
        });
    } else {
        wsAprendizaje.addRow(["No hay datos disponibles"]);
    }

    // Agregar imagen del gráfico de género
    const wsAprendizajeCanvas = document.getElementById("aprendizajeChart");
    if (wsAprendizajeCanvas) {
        const imageId = wb.addImage({
            base64: wsAprendizajeCanvas.toDataURL(),
            extension: 'png',
        });

        wsAprendizaje.addImage(imageId, 'D2:F15'); // Posición y tamaño del gráfico
    }

    
    // Hoja: Porcentaje de Acierto por Clase
    const wsClase = wb.addWorksheet("%Aciertos");
    wsClase.columns = [
         { header: "Clase", key: "clase", width: 20 },
        { header: "Aciertos", key: "aciertos", width: 10 },
        { header: "Total", key: "total", width: 10 },
        { header: "% de Acierto", key: "porcentaje", width: 15 }
    ];
    if (data.clases && data.clases.length > 0) {
        data.clases.forEach((row, i) => {
            const total = row.total || 1;
            const aciertos = row.aciertos || 0;
            const porcentaje = ((aciertos / total) * 100).toFixed(2);

            wsClase.addRow({
                clase: row.Clase,
                aciertos: parseInt(aciertos),
                total: parseInt(total),
                porcentaje: porcentaje
            });
        });
    } else {
        wsClase.addRow(["No hay datos disponibles"]);
    }

    // Agregar imagen del gráfico de género
    const wsClaseCanvas = document.getElementById("claseChart");
    if (wsClaseCanvas) {
        const imageId = wb.addImage({
            base64: wsClaseCanvas.toDataURL(),
            extension: 'png',
        });

        wsClase.addImage(imageId, 'D2:F15'); // Posición y tamaño del gráfico
    }

  
   
    // Guardar el archivo
    wb.xlsx.writeBuffer().then(buffer => {
        const blob = new Blob([buffer], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = `Reporte_Cursos_${anio}.xlsx`;
        link.click();
    }).catch(err => {
        console.error("Error al generar el archivo:", err);
        alert("Hubo un problema al generar el archivo Excel.");
    });
}
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const anioSelector = document.getElementById("anioSelector");
    const currentYear = new Date().getFullYear();

    // Establecer el año actual como valor seleccionado
    if (anioSelector) {
        anioSelector.value = currentYear;
        cargarDatosPorAnio();
        // Si quieres cargar automáticamente los datos del año actual
        // if (anioSelector.value) {
        //      // Llama a la función que carga los datos
        // }
    }
});
</script>
<!-- Encabezado oculto para impresión -->
<div id="print-header" style="text-align:center; display:none;">
    <h2>Reporte de Educación Financiera</h2>
    <p><strong>Fecha:</strong> <?= date("Y-m-d") ?></p>
    <hr>
</div>