<?php
$actionsRequired = true;
require_once "../controllers/organizaController.php";
$organizaController = new organizaController();

$id_curso = isset($_GET['id_curso']) ? filter_input(INPUT_GET, 'id_curso', FILTER_SANITIZE_STRING) : '';

if (empty($id_curso)) {
    die("<p class='text-center'>Curso no válido</p>");
}

$clases = $organizaController->cargar_clases_controller($id_curso);

if (empty($clases)) {
    echo "
        <div class='row'>
            <div class='col-xs-12'>
                <div class='alert alert-warning text-center'>No hay clases registradas en este curso.</div>
            </div>
        </div>
    ";
    exit;
}
?>

<style>
.lista-clases {
    list-style-type: none;
    padding: 0;
    margin: 0;
}
.lista-clases li {
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    padding: 15px;
    margin-bottom: 10px;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-radius: 4px;
}
.lista-clases .orden {
    font-weight: bold;
    color: #007bff;
    min-width: 30px;
    text-align: center;
    margin-right: 15px;
}
.lista-clases select {
    width: 60px;
    padding: 5px;
}
#guardarOrden {
    margin-top: 20px;
}
</style>

<ul id="lista-clases" class="lista-clases">
    <?php 
    $total_clases = count($clases);
    foreach ($clases as $index => $clase): ?>
        <li data-id="<?= $clase['id_curso_clase'] ?>">
            <span class="orden"><?= $index + 1 ?></span>
            <?= htmlspecialchars($clase['Titulo']) ?>
            
            <select class="nuevo-orden" data-id="<?= $clase['id_curso_clase'] ?>">
                <?php for ($i = 1; $i <= $total_clases; $i++): ?>
                    <option value="<?= $i ?>" <?= $i == $clase['orden'] ? 'selected' : '' ?>>
                        <?= $i ?>
                    </option>
                <?php endfor; ?>
            </select>
        </li>
    <?php endforeach; ?>
</ul>

<button id="guardarOrden" class="btn btn-success">Guardar Orden</button>

<script>
(function($) {
    "use strict";

    // Botón Guardar Orden
    $(document).on("click", "#guardarOrden", function () {
        const lista = $("#lista-clases")[0];
        const ids = [];

        $(lista).find("li").each(function(index, li) {
            const id = $(li).data("id");
            const nuevoOrden = $(li).find(".nuevo-orden").val();
            ids.push({
                id: id,
                orden: parseInt(nuevoOrden)
            });
        });

        $.ajax({
            url: "<?php echo SERVERURL; ?>ajax/ajaxGuardarOrdenClases.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(ids),
            success: function (res) {
                const data = typeof res === 'object' ? res : JSON.parse(res);
                alert(data.mensaje || "Orden guardado correctamente.");
            },
            error: function (err) {
                console.error("Error al guardar:", err);
                alert("Error al guardar el orden.");
            }
        });
    });

})(jQuery);
</script>