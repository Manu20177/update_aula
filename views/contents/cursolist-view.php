<?php 
    require_once "./controllers/cursoController.php";

    $insVideo = new cursoController();

    $dateNow=date("Y-m-d");
?>
<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="zmdi zmdi-tv-list zmdi-hc-fw"></i> Cursos</h1>
    </div>
    <p class="lead">
        En esta sección puede ver los Cursos de todas las clases impartidas en la plataforma de <strong><?php echo COMPANY; ?></strong>.
    </p>
</div>



<div class="container-custom">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="zmdi zmdi-format-list-bulleted"></i> Cursos Disponibles</h3>
                </div>
				
                <div class="panel-body" id="cursoCardsContainer">
					<!-- Campo de búsqueda -->
					<div class="form-group">
						<input type="text" id="busquedaCurso" class="form-control" placeholder="Buscar curso..." autocomplete="off">
					</div>
                    <?php
                        $page = explode("/", $_GET['views']);
                        echo $insVideo->pagination_curso_card_controller($page[1], 10);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
<script>
$(document).ready(function () {
    const delay = 500;
    let timer;

    $('#busquedaCurso').on('input', function () {
        const termino = $(this).val();
        clearTimeout(timer);

        timer = setTimeout(() => {
            cargarCursos(termino);
        }, delay);
    });

    function cargarCursos(busqueda) {
        $.ajax({
            url: '<?= SERVERURL ?>ajax/ajaxBuscarCurso.php',
            type: 'POST',
            data: { busqueda: busqueda },
            success: function(response) {
                $('#cursoCardsContainer').html(response);
            }
        });
    }
});
</script>