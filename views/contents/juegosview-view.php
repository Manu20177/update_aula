<?php 
	require_once "./controllers/juegosController.php";

	$insJuego = new juegosController();

	$code = explode("/", $_GET['views']);
	// $juego=1;

	$juego=$code[1];
	$tipojuego=$code[2];

	$data = $insJuego->obtenerJuegoPorId($juego);
	if ($tipojuego==1) {
		# code...
		$palabras = $insJuego->obtenerPalabrasPorJuego($juego);

	}

	if ($tipojuego==2 || $tipojuego==3 || $tipojuego==4) {
		# code...
		$palabras = $insJuego->obtenerPalabrasPorJuegoPista($juego);

	}
	if($data): 
		$rows = $data;
		$Titulo=$rows['titulo'];
		$Descripcion=$rows['descripcion'];
		$Clase=$rows['Titulo'];
		
?>
<p class="text-center">
	<a href="<?php echo SERVERURL; ?>juegoslist/" class="btn btn-info btn-raised btn-sm">
		<i class="zmdi zmdi-long-arrow-return"></i> Volver
	</a>
</p>

<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles" ><i class="zmdi zmdi-gamepad zmdi-hc-fw"></i> <small style="color:black;font-weight: bold;"><?php 
				echo $Titulo;
			?></small></h1>
	</div>
	<div class="col-xs-12">
		<p class="text-mutted"><strong><?php echo $Descripcion; ?></strong></p>
		<p class="text-mutted"><i class="zmdi zmdi-time"></i> CLASE DE ASIGNADA: <strong><?php echo $Clase; ?></strong></p>
		
	<hr>

	</div>
	
	

	<!-- Aquí irá el juego -->



<?php if ($tipojuego == 1): ?>
			<!-- Mostrar lista de pistas -->
		<!-- Mostrar lista de palabras -->
	<div class="text-center" style="margin-bottom: 20px;">
		<h4>Palabras a encontrar:</h4>
		<ul id="lista-palabras" style="list-style: none; padding: 0;">
			<?php foreach ($palabras as $p): ?>
				<li style="display: inline-block; margin: 5px; padding: 5px 10px; background: #eee; border-radius: 5px;" data-palabra="<?php echo strtoupper($p); ?>">
					<?php echo strtoupper($p); ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>


	<div id="sopa-container" style="margin-top: 30px;"></div>


	
	<!-- Cargamos el script que genera el juego -->
	<script>
	window.palabrasJuego = <?php echo json_encode(array_map('strtoupper', $palabras)); ?>;
	// alert (window.palabrasJuego)
	</script>
	<script src="<?php echo SERVERURL; ?>views/js/sopa.js"></script>
<?php endif; ?>

<?php if ($tipojuego == 2): 
	$palabrasc = [];
	$contador = 1;
	foreach ($palabras as $p) {
		$palabrasc[] = [
			"numero" => $contador,
			"palabra" => strtoupper($p["palabra"]),
			"pista" => $p["pista"] ?? ""
		];
		$contador++;
	}
?>
<div class="text-center" style="margin-bottom: 20px;">
	<h4>Pistas del crucigrama:</h4>
	<ol id="lista-pistas" style="padding-left: 20px;">
		<?php foreach ($palabrasc as $p): ?>
			<li><strong><?php echo $p['numero']; ?>.</strong> <?php echo $p['pista']; ?></li>
		<?php endforeach; ?>
	</ol>

</div>

<div id="crucigrama-container" style="margin-top: 30px;"></div>

<script>
	window.palabrasCrucigrama = <?php echo json_encode($palabrasc); ?>;
</script>
<script src="<?php echo SERVERURL; ?>views/js/crucigrama.js"></script>
<?php endif; ?>


<?php if ($tipojuego == 3): 
    $cartas = [];
    $id = 0;
    foreach ($palabras as $p) {
        $cartas[] = ["id" => $id, "tipo" => "palabra", "contenido" => strtoupper($p["palabra"])];
        $cartas[] = ["id" => $id, "tipo" => "pista", "contenido" => $p["pista"]];
        $id++;
    }
    shuffle($cartas);
?>
<div class="text-center" style="margin-bottom: 20px;">
	<h4>Juego de Memoria: Encuentra las parejas palabra - pista</h4>
</div>

<div id="memoria-container" class="text-center" style="display: grid; grid-template-columns: repeat(4, 150px); gap: 10px; justify-content: center; margin-top: 30px;"></div>

<script>
	window.cartasMemoria = <?php echo json_encode($cartas); ?>;
</script>
<script src="<?php echo SERVERURL; ?>views/js/memoria.js"></script>
<?php endif; ?>

<?php if ($tipojuego == 4): 
    $items = [];
    $id = 0;
    foreach ($palabras as $p) {
        $items[] = ["id" => $id, "palabra" => strtoupper($p["palabra"]), "pista" => $p["pista"]];
        $id++;
    }
?>
<div class="text-center" style="margin-bottom: 20px;">
    <h4>Juego Arrastrar y Soltar: Une la palabra con su pista</h4>
</div>

<div style="display: flex; justify-content: center; gap: 40px; margin-top: 30px;">
    <div id="palabras-container" style="display: flex; flex-direction: column; gap: 15px;">
        <h5>Palabras</h5>
        <?php foreach ($items as $item): ?>
            <div draggable="true" class="drag-palabra" data-id="<?php echo $item['id']; ?>"
                 style="padding: 10px; border: 2px solid #007bff; background: #007bff; color: white; cursor: grab; user-select:none; width: 200px; text-align: center; font-weight: bold;">
                <?php echo $item['palabra']; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="pistas-container" style="display: flex; flex-direction: column; gap: 15px;">
        <h5>Pistas</h5>
        <?php 
            $pistas = $items;
            shuffle($pistas);
        ?>
        <?php foreach ($pistas as $pista): ?>
            <div class="drop-pista" data-id="<?php echo $pista['id']; ?>"
                 style="padding: 10px; border: 2px solid #28a745; background: #e8f5e9; min-height: 40px; width: 300px; user-select:none;">
                <?php echo $pista['pista']; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    window.arrastrarItems = <?php echo json_encode($items); ?>;
</script>
<script src="<?php echo SERVERURL; ?>views/js/arrastrar.js"></script>

<?php endif; ?>


</div>

<br>

<?php else: ?>
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-videocam zmdi-hc-fw"></i> Clase</h1>
	</div>
</div>
<p class="lead text-center">Lo sentimos ocurrió un error inesperado</p>
<?php endif; ?>