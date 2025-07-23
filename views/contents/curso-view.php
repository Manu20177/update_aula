<?php if($_SESSION['userType']=="Administrador"): 
	
    require_once "./controllers/cursoController.php";

    $insVideo = new cursoController();
	$tipo_curso=$insVideo->execute_single_query("SELECT * FROM `tipo_curso` ORDER BY norma ASC");
?>
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-balance zmdi-hc-fw"></i> Cursos <small>(Registro)</small></h1>
	</div>
	<p class="lead">
		Bienvenido a la sección de cursos, aquí podrás registrar nuevos cursos(Los campos marcados con * son obligatorios para registrar un nuevo curso ).
	</p>
</div>
<div class="container-fluid">
	<ul class="breadcrumb breadcrumb-tabs">
	  	<li class="active">
		  	<a href="<?php echo SERVERURL; ?>curso/" class="btn btn-info">
		  		<i class="zmdi zmdi-plus"></i> Nuevo
		  	</a>
	  	</li>
	  	<li>
	  		<a href="<?php echo SERVERURL; ?>listacursos/" class="btn btn-success">
	  			<i class="zmdi zmdi-format-list-bulleted"></i> Lista
	  		</a>
	  	</li>
	</ul>
</div>
<?php 

	$dateNow=date("Y-m-d");
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-info">
				<div class="panel-heading">
				    <h3 class="panel-title"><i class="zmdi zmdi-plus"></i> Nuevo Curso</h3>
				</div>
			  	<div class="panel-body">
				    <form action="<?php echo SERVERURL; ?>ajax/ajaxCurso.php" method="POST" enctype="multipart/form-data" autocomplete="off" data-form="AddCurso" class="ajaxDataForm">
				    	<fieldset class="full-box">
				    		<legend><i class="zmdi zmdi-videocam"></i> Datos del Curso</legend>
				    		<div class="container-fluid">
				    			<div class="row">
				    				<div class="col-xs-12 col-sm-6">
								    	<div class="form-group label-floating">
										  	<span class="control-label">Título *</span>
										  	<input class="form-control" type="text" name="title" required="">
										</div>
				    				</div>
				    				
				    				<div class="col-xs-12 col-sm-6">
										<div class="form-group label-floating">
										  	<span class="control-label">Fecha *</span>
										  	<input class="form-control" type="date" value="<?php echo $dateNow; ?>" name="date" required="" maxlength="30">
										</div>
				    				</div>
									<div class="col-xs-12 col-sm-6">
										<div class="form-group label-floating">
										  	<span class="control-label">Portada *</span>
											  <input type="file" name="portada[]" multiple="" accept=".jpg, .png, .jpeg">
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
													<option value="<?= htmlspecialchars($fila['id_tipoc']) ?>">
														<?= htmlspecialchars($fila['norma']) ?>
													</option>
												<?php endforeach; ?>
											</select>
										</div>
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
