<?php if($_SESSION['userType']=="Administrador"): ?>
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-videocam zmdi-hc-fw"></i> Cursos</h1>
	</div>
	<p class="lead">
		Bienvenido a la sección de actualización de los datos de los Cursos. Acá podrá actualizar la información del Curso.
	</p>
</div>
<?php 
	require_once "./controllers/cursoController.php";

	$insVideo = new cursoController();

	$urls=SERVERURL.$_GET['views'];

	$code=explode("/", $_GET['views']);

	$data=$insVideo->data_curso_controller("Only",$code[1]);
	$tipo_curso=$insVideo->execute_single_query("SELECT * FROM `tipo_curso` ORDER BY norma ASC");
	if($data->rowCount()>0):
		$rows=$data->fetch();
?>
<p class="text-center">
	<a href="<?php echo SERVERURL; ?>listacursos/" class="btn btn-info btn-raised btn-sm">
		<i class="zmdi zmdi-long-arrow-return"></i> Volver
	</a>
</p>

<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-success">
				<div class="panel-heading">
				    <h3 class="panel-title"><i class="zmdi zmdi-refresh"></i> Actualizar datos</h3>
				</div>
			  	<div class="panel-body">
				    <form action="<?php echo SERVERURL; ?>ajax/ajaxCurso.php" method="POST" enctype="multipart/form-data" autocomplete="off" data-form="UpdateCurso" class="ajaxDataForm">
				    	<input type="hidden" name="upid" value="<?php echo $rows['id_curso']; ?>">
				    	<fieldset class="full-box">
				    		<legend><i class="zmdi zmdi-videocam"></i> Datos del Curso</legend>
				    		<div class="container-fluid">
								<div class="row">
									<?php
									// Ruta base donde se almacenan las portadas
									$rutaPortadas = '/aula/attachments/class_portada/';

									// Verificamos si hay portada y si el archivo existe físicamente
									$nombreArchivo = $rows['Portada'];
									$rutaCompleta = $rutaPortadas . $nombreArchivo;

									if (!empty($nombreArchivo)) {
										$imagenMostrar = $rutaPortadas . $nombreArchivo;
									} else {
										$imagenMostrar = $rutaPortadas . 'sin_portada.jpg';
									}
									?>
										<div class="form-group label-floating" style="text-align: center;">
											<span class="control-label" style="font-weight: bold; font-size: 25px;">Portada</span><br>
											
											<img src="<?php echo $imagenMostrar; ?>" 
												alt="Portada" 
												class="img-responsive" 
												style="max-width: 200px; margin: 10px auto; display: block;">
										</div>
								</div>
				    			<div class="row">
				    				<div class="col-xs-12 col-sm-6">
								    	<div class="form-group label-floating">
										  	<span class="control-label">Título *</span>
										  	<input class="form-control" type="text" name="title" value="<?php echo $rows['Titulo']; ?>" required="">
										</div>
				    				</div>
				    				
				    				<div class="col-xs-12 col-sm-6">
										<div class="form-group label-floating">
										  	<span class="control-label">Fecha *</span>
										  	<input class="form-control" type="date" name="date" value="<?php echo $rows['Fecha']; ?>" required="" maxlength="30">
										</div>
				    				</div>
									<div class="col-xs-12 col-sm-6">
										<div class="form-group label-floating">
										  	<span class="control-label">Portada *</span>
											  <input type="file" name="portada1[]"  multiple="" accept=".jpg, .png, .jpeg">
											<div class="input-group">
												<input type="text" readonly="" class="form-control" placeholder="Elija la portada...">
												<span class="input-group-btn input-group-sm">
													<button type="button" class="btn btn-fab btn-fab-mini">
														<i class="zmdi zmdi-book-image"></i>
													</button>
												</span>
											</div>

										</div>
				    				</div>
				    				<div class="col-xs-12 col-sm-6">
								    	<div class="form-group label-floating">
										  	<span  class="control-label">Norma *</span>
										  	<select class="form-control" name="norma">
												<?php foreach ($tipo_curso as $fila): ?>
													<option value="<?= htmlspecialchars($fila['id_tipoc']) ?>"
														<?= ($fila['id_tipoc'] == $rows['Norma']) ? 'selected' : '' ?>>
														<?= htmlspecialchars($fila['norma']) ?>
													</option>
												<?php endforeach; ?>
											</select>
										</div>
				    				</div>
									<div class="col-xs-12 col-sm-6">
										<div class="form-group label-floating">
											<span class="control-label">Estado *</span>
											<select class="form-control" name="estado">
												<option value="4" <?php if ($rows['Estado'] == 4) echo 'selected'; ?>>Activo</option>
												<option value="5" <?php if ($rows['Estado'] == 5) echo 'selected'; ?>>Inactivo</option>
											</select>
										</div>
									</div>

				    				
				    			</div>
				    		</div>
				    	</fieldset>
				    	
				    	
					    <p class="text-center">
					    	<button type="submit" class="btn btn-success btn-raised btn-sm"><i class="zmdi zmdi-refresh"></i> Guardar cambios</button>
					    </p>
					    <div class="full-box form-process"></div>
				    </form>
				    <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
				    	
				    </form>
			  	</div>
			</div>
		</div>
	</div>
</div>
<?php else: ?>
	<p class="lead text-center">Lo sentimos ocurrió un error inesperado</p>
<?php
		endif;
	else:
		$logout2 = new loginController();
        echo $logout2->login_session_force_destroy_controller(); 
	endif;
?>