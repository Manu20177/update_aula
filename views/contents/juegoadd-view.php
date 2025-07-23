<?php if($_SESSION['userType']=="Administrador"): ?>
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi zmdi-gamepad "></i> Juegos Interactivos <small>(Clases)</small></h1>
	</div>
	<p class="lead">
		Bienvenido a la sección de Juegos Interactivos, aquí puedes crear un juego interactivo para cada clase.
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


	$insVideo = new juegosController();
	$query=$insVideo->execute_single_query("SELECT c.* FROM clase c LEFT JOIN juego_clase jc ON jc.id_clase = c.id WHERE jc.id_clase IS NULL;


");
	$tipojuegos=$insVideo->execute_single_query("SELECT * FROM `tipo_juego`");
	$dateNow=date("Y-m-d");
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="zmdi zmdi-plus"></i> Nuevo Juego</h3>
				</div>
				<div class="panel-body">
					<form action="<?php echo SERVERURL; ?>ajax/ajaxJuegoadd.php" method="POST" enctype="multipart/form-data" autocomplete="off" data-form="" class="ajaxDataForm">
						<fieldset class="full-box">
							<div class="container-fluid">
								<div class="row">

									<!-- Asignar Clase -->
									<div class="col-xs-12 col-sm-6">
										<div class="form-group label-floating">
											<span class="control-label">Asignar Clase *</span>
											<input type="text" id="filtroCursos" placeholder="Buscar clase..." class="form-control" style="margin-bottom:10px;">
											<select class="form-control" name="clase_select" id="clase_select" size="5" required>
												<?php 
												foreach ($query as $key) {
													$cod_clase = $key['id'];
													$clase = $key['Titulo'];
													$tutor = $key['Tutor'];
													echo "<option value=\"$cod_clase\">$clase | Tutor: $tutor</option>";
												}
												?>
											</select>
											<script>
												document.getElementById('filtroCursos').addEventListener('keyup', function() {
													let filtro = this.value.toLowerCase();
													let opciones = document.getElementById('clase_select').options;
													for (let i = 0; i < opciones.length; i++) {
														let texto = opciones[i].text.toLowerCase();
														opciones[i].style.display = texto.includes(filtro) ? '' : 'none';
													}
												});
											</script>
										</div>
									</div>

									<!-- Título del Juego -->
									<div class="col-xs-12 col-sm-6">
										<div class="form-group label-floating">
											<span class="control-label">Título del Juego *</span>
											<input type="text" name="titulo_juego" class="form-control" required>
										</div>
									</div>

									<!-- Descripción del Juego -->
									<div class="col-xs-12 col-sm-12">
										<div class="form-group label-floating">
											<span class="control-label">Descripción *</span>
											<textarea name="descripcion_juego" class="form-control" rows="2" required></textarea>
										</div>
									</div>

									<!-- Tipo de Juego -->
									<div class="col-xs-12 col-sm-6">
										<div class="form-group label-floating">
											<span class="control-label">Tipo de Juego *</span>
											<select name="tipo_juego" class="form-control" required>
												<option value="" disabled selected>Seleccione un tipo</option>
												<?php 
												if (!empty($tipojuegos)) {
													foreach ($tipojuegos as $tipo) {
														echo '<option value="' . $tipo['id_tipoj'] . '">' . htmlspecialchars($tipo['titulo']) . '</option>';
													}
												}
												?>
											</select>
										</div>
									</div>

									<!-- ID del Juego (autogenerado) -->
									<?php $id_juego = "game" . rand(1000, 99999); ?>
									<div class="col-xs-12 col-sm-6">
										<div class="form-group label-floating">
											<span class="control-label">ID del Juego</span>
											<input type="text" name="id_juego" class="form-control" readonly value="<?php echo $id_juego; ?>">
										</div>
									</div>

									<!-- Evaluación (subir archivo CSV) -->
									<div class="col-xs-12 col-sm-12">
										<div class="form-group label-floating">
											<span class="control-label">Palabras y Pistas *</span>
											<input type="file" name="csv_file" multiple accept=".csv">
											<div class="input-group">
												<input type="text" readonly class="form-control" placeholder="Elija el archivo...">
												<span class="input-group-btn input-group-sm">
													<button type="button" class="btn btn-fab btn-fab-mini">
														<i class="zmdi zmdi-attachment-alt"></i>
													</button>
												</span>
											</div>
										</div>
										<a href="../archivos/juegos/plantilla_juegos.xlsm">Descargar Plantilla</a>

									</div>


									
								</div>
							</div>
						</fieldset>

						<p class="text-center">
							<button type="submit" class="btn btn-info btn-raised btn-sm"><i class="zmdi zmdi-floppy"></i> Guardar</button>
						</p>
						<div class="full-box form-process"></div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
	else:
		$logout2 = new loginController();
        echo $logout2->login_session_force_destroy_controller(); 
	endif;
?>
