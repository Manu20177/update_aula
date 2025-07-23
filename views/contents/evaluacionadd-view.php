<?php if($_SESSION['userType']=="Administrador"): ?>
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi zmdi-assignment-check "></i> Evaluacion <small>(Registro)</small></h1>
	</div>
	<p class="lead">
		Bienvenido a la sección de Evaluacion, aquí cargar las Evaluaciones para las clases.
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
	require_once "./controllers/encuestaentrevistaController.php";


	$insVideo = new encuestaentrevistaController();
	$query=$insVideo->execute_single_query("SELECT c.*,cu.Titulo as Curso FROM clase c LEFT JOIN preguntas_rapidas pr ON c.id = pr.id_clase LEFT JOIN curso_clase cc on cc.id_clase=c.id LEFT JOIN curso cu on cu.id_curso=cc.id_curso WHERE pr.id_clase IS NULL;

");

	$dateNow=date("Y-m-d");
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-info">
				<div class="panel-heading">
				    <h3 class="panel-title"><i class="zmdi zmdi-plus"></i> Nueva Evaluacion</h3>
				</div>
			  	<div class="panel-body">
				    <form action="<?php echo SERVERURL; ?>ajax/ajaxEvaluacionadd.php" method="POST" enctype="multipart/form-data" autocomplete="off" data-form="AddCurso" class="ajaxDataForm">
				    	<fieldset class="full-box">
				    		<div class="container-fluid">
				    			<div class="row">
				    				<div class="col-xs-12 col-sm-12">
								    	<div class="form-group label-floating">
										  	<span class="control-label">Asignar Clase *</span>
											
											<!-- Campo de búsqueda -->
											<!-- Buscador -->
											<input type="text" id="filtroCursos" placeholder="Buscar clase..." class="form-control" style="margin-bottom:10px;">

											<!-- Select de cursos -->
											<select class="form-control" name="clase_select" id="clase_select" size="5" required="">
												<?php 
												foreach ($query as $key) {
													$cod_clase = $key['id'];
													$clase = $key['Titulo'];
													$tutor = $key['Tutor'];
													$curso = $key['Curso'];
													
													echo "<option value=\"$cod_clase\">$clase | Tutor: $tutor | Curso: $curso</option>";
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
				    				
				    				
									
									

				    				 				
				    			</div>
				    			<div class="col-xs-12 col-sm-6">
										<div class="form-group label-floating">
										  	<span class="control-label">Evaluacion *</span>
											  <input type="file" name="csv_file" multiple="" accept=".csv">
											<div class="input-group">
												<input type="text" readonly="" class="form-control" placeholder="Elija el archivo...">
												<span class="input-group-btn input-group-sm">
													<button type="button" class="btn btn-fab btn-fab-mini">
														<i class="zmdi zmdi-attachment-alt"></i>
													</button>
												</span>
											</div>

										</div>
										<a href="../archivos/evaluacion/plantilla_evaluacion.xlsm">Descargar Plantilla</a>
									<div style="border: 1px solid #ccc; border-left: 5px solid #007BFF; background-color: #f9f9f9; padding: 15px; border-radius: 8px; max-width: 400px;">
										<span style="font-weight: bold; font-size: 16px; color: #007BFF;">Tipos de Preguntas</span>
										<ul style="margin-top: 10px; padding-left: 20px;">
											<li>multiple</li>
											<li>verdadero_falso</li>
											<li>completar</li>
										</ul>
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
