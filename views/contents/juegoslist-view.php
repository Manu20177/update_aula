<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="zmdi zmdi-tv-list zmdi-hc-fw"></i> Juegos <small>(Listado)</small></h1>
    </div>
    <p class="lead">
        En esta sección puede ver el listado de todos los juegos registrados en el sistema, puede eliminar un juego cuando lo desee.
    </p>
</div>

<div class="container-fluid">
    <ul class="breadcrumb breadcrumb-tabs">
        <li class="active">
            <a href="<?php echo SERVERURL; ?>juegoadd/" class="btn btn-info">
                <i class="zmdi zmdi-plus"></i> Nuevo
            </a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>juegoslist/" class="btn btn-success">
                <i class="zmdi zmdi-format-list-bulleted"></i> Lista
            </a>
        </li>
    </ul>
</div>

<?php 
    require_once "./controllers/juegosController.php";
    $insCurso = new juegosController();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="zmdi zmdi-format-list-bulleted"></i> Lista de Juegos</h3>
                </div>

              

                <div class="panel-body">
                    <div class="table-responsive">

                        <!-- Aquí va tu tabla generada desde PHP -->
                        <?php
                            $page = explode("/", $_GET['views']);
                            echo $insCurso->pagination_juego_controller();
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


