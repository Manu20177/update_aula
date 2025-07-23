<?php 
    require_once "./controllers/cursoController.php";
    $insVideo = new cursoController();
	$tipo_curso=$insVideo->execute_single_query("SELECT * FROM `tipo_curso` ORDER BY norma ASC");
	$tipo_alumno=$insVideo->execute_single_query("SELECT * FROM `tipo_usuario`");

?>
<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="zmdi zmdi-balance zmdi-hc-fw"></i> Reporte General</h1>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-info">
                <div class="panel-body">

                    <!-- Botón de imprimir -->
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
                                <h3><i class="zmdi zmdi-account-box"></i> Total de Participantes</h3>
                                <p><strong>Total:</strong> <span id="total-count">Cargando...</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Gráfico de Género -->
                    <div class="chart-page">
                        <h2><i class="zmdi zmdi-male-female"></i> Distribución por Género</h2>
                        <canvas id="genderChart" style="max-width: 600px;"></canvas>
                        <div class="chart-description" id="genderDescription"></div>
                    </div>

                    <!-- Gráfico nivel educativo -->
                    <div class="chart-page">
                        <h2><i class="zmdi zmdi-graduation-cap"></i> Nivel Educativo por Género</h2>
                        <canvas id="nivelChart" style="max-width: 600px;"></canvas>
                        <div class="chart-description" id="nivelDescription"></div>
                    </div>

                    <!-- Gráfico provincia -->
                    <div class="chart-page">
                        <h2><i class="zmdi zmdi-pin-drop"></i> Provincia por Género</h2>
                        <canvas id="provinciaChart" style="max-width: 600px;"></canvas>
                        <div class="chart-description" id="provinciaDescription"></div>
                    </div>

                    <!-- Gráfico actividad económica -->
                    <div class="chart-page">
                        <h2><i class="zmdi zmdi-money-box"></i> Actividad Económica por Género</h2>
                        <canvas id="actividadChart" style="max-width: 600px;"></canvas>
                        <div class="chart-description" id="actividadDescription"></div>
                    </div>

                    <!-- Gráfico grupo étnico -->
                    <div class="chart-page">
                        <h2><i class="zmdi zmdi-accounts-alt"></i> Grupo Étnico por Género</h2>
                        <canvas id="etniaChart" style="max-width: 600px;"></canvas>
                        <div class="chart-description" id="etniaDescription"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
function getPorcentaje(valor, total) {
    return ((valor / total) * 100).toFixed(1);
}

function generarGraficos(data) {
    // Gráfico de Género
    if (data.genero && data.genero.length > 0) {
        const labels = data.genero.map(g => g.Genero);
        const valores = data.genero.map(g => g.total);
        const femenino = data.genero.find(g => g.Genero === "Femenino")?.total || 0;
        const masculino = data.genero.find(g => g.Genero === "Masculino")?.total || 0;

        window.genderChartInstance = new Chart(document.getElementById('genderChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Participantes',
                    data: valores,
                    backgroundColor: ['#36A2EB', '#FF6384']
                }]
            }
        });

        const desc = `
            <p><strong>Total:</strong> ${data.total?.total || 0} participantes</p>
            <ul>
                <li>Femenino: ${femenino} (${getPorcentaje(femenino, data.total?.total || 1)}%)</li>
                <li>Masculino: ${masculino} (${getPorcentaje(masculino, data.total?.total || 1)}%)</li>
            </ul>
            <p>Distribución por género de los estudiantes.</p>
        `;
        document.getElementById("genderDescription").innerHTML = desc;
    } else {
        document.getElementById("genderDescription").innerHTML = "<p>No hay datos disponibles para este filtro.</p>";
    }

    // Gráfico Nivel Educativo
    if (data.nivel && data.nivel.length > 0) {
        const nivelMap = {};
        data.nivel.forEach(row => {
            if (!nivelMap[row.Nivel]) {
                nivelMap[row.Nivel] = { Masculino: 0, Femenino: 0 };
            }
            nivelMap[row.Nivel][row.Genero] = row.total;
        });
        const labels = Object.keys(nivelMap);
        const hombres = labels.map(k => nivelMap[k].Masculino || 0);
        const mujeres = labels.map(k => nivelMap[k].Femenino || 0);

        window.nivelChartInstance = new Chart(document.getElementById('nivelChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { label: 'Hombres', data: hombres, backgroundColor: '#36A2EB' },
                    { label: 'Mujeres', data: mujeres, backgroundColor: '#FF6384' }
                ]
            }
        });

        let desc = "<ul>";
        labels.forEach(label => {
            const h = nivelMap[label].Masculino || 0;
            const m = nivelMap[label].Femenino || 0;
            const t = parseInt(h) + parseInt(m);
            desc += `<li><strong>${label}</strong>: ${t} participantes (Hombres: ${h}, Mujeres: ${m})</li>`;
        });
        desc += "</ul>";
        desc += "<p>Distribución por nivel educativo según el género.</p>";
        document.getElementById("nivelDescription").innerHTML = desc;
    } else {
        document.getElementById("nivelDescription").innerHTML = "<p>No hay datos disponibles para este filtro.</p>";
    }

    // Gráfico Provincia
    if (data.provincia && data.provincia.length > 0) {
        const provMap = {};
        data.provincia.forEach(row => {
            if (!provMap[row.provincia]) {
                provMap[row.provincia] = { Masculino: 0, Femenino: 0 };
            }
            provMap[row.provincia][row.Genero] = row.total;
        });
        const provLabels = Object.keys(provMap);
        const hombres = provLabels.map(k => provMap[k].Masculino || 0);
        const mujeres = provLabels.map(k => provMap[k].Femenino || 0);

        window.provinciaChartInstance = new Chart(document.getElementById('provinciaChart'), {
            type: 'bar',
            data: {
                labels: provLabels,
                datasets: [
                    { label: 'Hombres', data: hombres, backgroundColor: '#36A2EB' },
                    { label: 'Mujeres', data: mujeres, backgroundColor: '#FF6384' }
                ]
            }
        });

        let desc = "<ul>";
        provLabels.forEach(label => {
            const h = provMap[label].Masculino || 0;
            const m = provMap[label].Femenino || 0;
            const t = parseInt(h) + parseInt(m);
            desc += `<li><strong>${label}</strong>: ${t} participantes (Hombres: ${h}, Mujeres: ${m})</li>`;
        });
        desc += "</ul>";
        desc += "<p>Cantidad de hombres y mujeres por provincia.</p>";
        document.getElementById("provinciaDescription").innerHTML = desc;
    } else {
        document.getElementById("provinciaDescription").innerHTML = "<p>No hay datos disponibles para este filtro.</p>";
    }

    // Gráfico Actividad Económica
    if (data.actividad && data.actividad.length > 0) {
        const actMap = {};
        data.actividad.forEach(row => {
            if (!actMap[row.Actividad]) {
                actMap[row.Actividad] = { Masculino: 0, Femenino: 0 };
            }
            actMap[row.Actividad][row.Genero] = row.total;
        });
        const actLabels = Object.keys(actMap);
        const hombres = actLabels.map(k => actMap[k].Masculino || 0);
        const mujeres = actLabels.map(k => actMap[k].Femenino || 0);

        window.actividadChartInstance = new Chart(document.getElementById('actividadChart'), {
            type: 'bar',
            data: {
                labels: actLabels,
                datasets: [
                    { label: 'Hombres', data: hombres, backgroundColor: '#36A2EB' },
                    { label: 'Mujeres', data: mujeres, backgroundColor: '#FF6384' }
                ]
            }
        });

        let desc = "<ul>";
        actLabels.forEach(label => {
            const h = actMap[label].Masculino || 0;
            const m = actMap[label].Femenino || 0;
            const t = parseInt(h) + parseInt(m);
            desc += `<li><strong>${label}</strong>: ${t} participantes (Hombres: ${h}, Mujeres: ${m})</li>`;
        });
        desc += "</ul>";
        desc += "<p>Distribución por actividad económica según el género.</p>";
        document.getElementById("actividadDescription").innerHTML = desc;
    } else {
        document.getElementById("actividadDescription").innerHTML = "<p>No hay datos disponibles para este filtro.</p>";
    }

    // Gráfico Grupo Étnico
    if (data.etnia && data.etnia.length > 0) {
        const etniaMap = {};
        data.etnia.forEach(row => {
            if (!etniaMap[row.Etnia]) {
                etniaMap[row.Etnia] = { Masculino: 0, Femenino: 0 };
            }
            etniaMap[row.Etnia][row.Genero] = row.total;
        });
        const etniaLabels = Object.keys(etniaMap);
        const hombres = etniaLabels.map(k => etniaMap[k].Masculino || 0);
        const mujeres = etniaLabels.map(k => etniaMap[k].Femenino || 0);

        window.etniaChartInstance = new Chart(document.getElementById('etniaChart'), {
            type: 'bar',
            data: {
                labels: etniaLabels,
                datasets: [
                    { label: 'Hombres', data: hombres, backgroundColor: '#36A2EB' },
                    { label: 'Mujeres', data: mujeres, backgroundColor: '#FF6384' }
                ]
            }
        });

        let desc = "<ul>";
        etniaLabels.forEach(label => {
            const h = etniaMap[label].Masculino || 0;
            const m = etniaMap[label].Femenino || 0;
            const t = parseInt(h) + parseInt(m);
            desc += `<li><strong>${label}</strong>: ${t} participantes (Hombres: ${h}, Mujeres: ${m})</li>`;
        });
        desc += "</ul>";
        desc += "<p>Distribución por grupo étnico según el género.</p>";
        document.getElementById("etniaDescription").innerHTML = desc;
    } else {
        document.getElementById("etniaDescription").innerHTML = "<p>No hay datos disponibles para este filtro.</p>";
    }
}

function cargarDatosPorAnio() {
    const anio = document.getElementById("anioSelector").value;
    const norma = document.getElementById("normaSelector").value;
    const alumno = document.getElementById("alumnoSelector").value;

    // Obtener el data-descripcion de esa opción
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
    if (window.genderChartInstance) window.genderChartInstance.destroy();
    if (window.nivelChartInstance) window.nivelChartInstance.destroy();
    if (window.provinciaChartInstance) window.provinciaChartInstance.destroy();
    if (window.actividadChartInstance) window.actividadChartInstance.destroy();
    if (window.etniaChartInstance) window.etniaChartInstance.destroy();

    // Limpiar descripciones
    document.getElementById("genderDescription").innerHTML = "Cargando...";
    document.getElementById("nivelDescription").innerHTML = "Cargando...";
    document.getElementById("provinciaDescription").innerHTML = "Cargando...";
    document.getElementById("actividadDescription").innerHTML = "Cargando...";
    document.getElementById("etniaDescription").innerHTML = "Cargando...";

    fetch(`../controllers/reporteController.php?anio=${anio}&norma=${encodeURIComponent(norma)}&alumno=${encodeURIComponent(alumno)}`)
        .then(res => res.json())
        .then(data => {
            window.reportData = data;
            document.getElementById("total-count").textContent = data.total?.total || 0;
            generarGraficos(data);
        })
        .catch(err => {
            console.error("Error al cargar los datos:", err);
            alert('Hubo un problema al cargar los datos. Revisa la consola.');
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
    titulo.textContent = "Reporte de "+norma_descrip;
    titulo.style.textAlign = "center";
    titulo.style.fontFamily = "Arial, sans-serif";
    titulo.style.fontSize = "20px";
    titulo.style.marginBottom = "10px";
    titulo.style.color = "#000";

    // Crear subtítulo dinámico
    const subtitulo = document.createElement("p");
    subtitulo.innerHTML = "<strong>Año seleccionado:</strong> " + anioSeleccionado + "<br>"+"<strong>Tipo de Usuarios:</strong> " + alumno ;
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
    footer.innerHTML = "<p style='text-align:center; font-size:12px; color:#666;'>Documento generado automáticamente - Reporte de "+norma_descrip+"</p>";
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
            <title>Reporte de `+normaSeleccionada+`</title>
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
    const wsGenero = wb.addWorksheet("Género");
    wsGenero.columns = [
        { header: "Género", key: "genero", width: 15 },
        { header: "Total", key: "total", width: 10 }
    ];
    if (data.genero && data.genero.length > 0) {
        data.genero.forEach(row => {
            wsGenero.addRow({ genero: row.Genero, total: parseInt(row.total) });
        });
    } else {
        wsGenero.addRow(["No hay datos disponibles"]);
    }

    // Agregar imagen del gráfico de género
    const genderCanvas = document.getElementById("genderChart");
    if (genderCanvas) {
        const imageId = wb.addImage({
            base64: genderCanvas.toDataURL(),
            extension: 'png',
        });

        wsGenero.addImage(imageId, 'D2:F15'); // Posición y tamaño del gráfico
    }

    // Repite para otras hojas
    // Ejemplo: Nivel Educativo
    const wsNivel = wb.addWorksheet("Nivel Educativo");
    wsNivel.columns = [
        { header: "Nivel", key: "nivel", width: 20 },
        { header: "Hombres", key: "masculino", width: 10 },
        { header: "Mujeres", key: "femenino", width: 10 }
    ];

    const nivelMap = {};
    data.nivel.forEach(row => {
        if (!nivelMap[row.Nivel]) nivelMap[row.Nivel] = { Masculino: 0, Femenino: 0 };
        nivelMap[row.Nivel][row.Genero] += parseInt(row.total);
    });

    Object.keys(nivelMap).forEach(nivel => {
        wsNivel.addRow({
            nivel: nivel,
            masculino: nivelMap[nivel].Masculino || 0,
            femenino: nivelMap[nivel].Femenino || 0
        });
    });

    // Agregar imagen del gráfico de nivel educativo
    const nivelCanvas = document.getElementById("nivelChart");
    if (nivelCanvas) {
        const imageNivelId = wb.addImage({
            base64: nivelCanvas.toDataURL(),
            extension: 'png'
        });

        wsNivel.addImage(imageNivelId, 'E2:G15');
    }

    // Repite para provincia, actividad y etnia...

        // Hoja: Provincia
    const wsProvincia = wb.addWorksheet("Provincia");
    wsProvincia.columns = [
        { header: "Provincia", key: "provincia", width: 20 },
        { header: "Hombres", key: "masculino", width: 10 },
        { header: "Mujeres", key: "femenino", width: 10 }
    ];

    const provinciaMap = {};
    if (data.provincia && data.provincia.length > 0) {
        data.provincia.forEach(row => {
            if (!provinciaMap[row.provincia]) provinciaMap[row.provincia] = { Masculino: 0, Femenino: 0 };
            provinciaMap[row.provincia][row.Genero] += parseInt(row.total);
        });

        Object.keys(provinciaMap).forEach(prov => {
            wsProvincia.addRow({
                provincia: prov,
                masculino: provinciaMap[prov].Masculino || 0,
                femenino: provinciaMap[prov].Femenino || 0
            });
        });
    } else {
        wsProvincia.addRow(["No hay datos disponibles"]);
    }

    // Agregar imagen del gráfico de provincia
    const provinciaCanvas = document.getElementById("provinciaChart");
    if (provinciaCanvas) {
        const imageProvinciaId = wb.addImage({
            base64: provinciaCanvas.toDataURL(),
            extension: 'png'
        });

        wsProvincia.addImage(imageProvinciaId, 'D2:F15');
    }


    // Hoja: Actividad Económica
    const wsActividad = wb.addWorksheet("Actividad Económica");
    wsActividad.columns = [
        { header: "Actividad", key: "actividad", width: 30 },
        { header: "Hombres", key: "masculino", width: 10 },
        { header: "Mujeres", key: "femenino", width: 10 }
    ];

    const actividadMap = {};
    if (data.actividad && data.actividad.length > 0) {
        data.actividad.forEach(row => {
            if (!actividadMap[row.Actividad]) actividadMap[row.Actividad] = { Masculino: 0, Femenino: 0 };
            actividadMap[row.Actividad][row.Genero] += parseInt(row.total);
        });

        Object.keys(actividadMap).forEach(act => {
            wsActividad.addRow({
                actividad: act,
                masculino: actividadMap[act].Masculino || 0,
                femenino: actividadMap[act].Femenino || 0
            });
        });
    } else {
        wsActividad.addRow(["No hay datos disponibles"]);
    }

    // Agregar imagen del gráfico de actividad económica
    const actividadCanvas = document.getElementById("actividadChart");
    if (actividadCanvas) {
        const imageActividadId = wb.addImage({
            base64: actividadCanvas.toDataURL(),
            extension: 'png'
        });

        wsActividad.addImage(imageActividadId, 'D2:F15');
    }


    // Hoja: Grupo Étnico
    const wsEtnia = wb.addWorksheet("Grupo Étnico");
    wsEtnia.columns = [
        { header: "Etnia", key: "etnia", width: 20 },
        { header: "Hombres", key: "masculino", width: 10 },
        { header: "Mujeres", key: "femenino", width: 10 }
    ];

    const etniaMap = {};
    if (data.etnia && data.etnia.length > 0) {
        data.etnia.forEach(row => {
            if (!etniaMap[row.Etnia]) etniaMap[row.Etnia] = { Masculino: 0, Femenino: 0 };
            etniaMap[row.Etnia][row.Genero] += parseInt(row.total);
        });

        Object.keys(etniaMap).forEach(etnia => {
            wsEtnia.addRow({
                etnia: etnia,
                masculino: etniaMap[etnia].Masculino || 0,
                femenino: etniaMap[etnia].Femenino || 0
            });
        });
    } else {
        wsEtnia.addRow(["No hay datos disponibles"]);
    }

    // Agregar imagen del gráfico étnico
    const etniaCanvas = document.getElementById("etniaChart");
    if (etniaCanvas) {
        const imageEtniaId = wb.addImage({
            base64: etniaCanvas.toDataURL(),
            extension: 'png'
        });

        wsEtnia.addImage(imageEtniaId, 'D2:F15');
    }
    // Guardar el archivo
    wb.xlsx.writeBuffer().then(buffer => {
        const blob = new Blob([buffer], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = `Reporte_General_${anio}.xlsx`;
        link.click();
    }).catch(err => {
        console.error("Error al generar el archivo:", err);
        alert("Hubo un problema al generar el archivo Excel.");
    });
}
</script>
<!-- Encabezado oculto para impresión -->
<div id="print-header" style="text-align:center; display:none;">
    <h2>Reporte de Educación Financiera</h2>
    <p><strong>Fecha:</strong> <?= date("Y-m-d") ?></p>
    <hr>
</div>