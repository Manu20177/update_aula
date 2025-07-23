<?php if($_SESSION['userType']=="Administrador"): ?>
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-videocam zmdi-hc-fw"></i> Clase</h1>
	</div>
	<p class="lead">
		Bienvenido a la sección de actualización de los datos de las clases. Acá podrá actualizar la información de la clase.
	</p>
</div>
<?php 
	require_once "./controllers/videoController.php";

	$insVideo = new videoController();

	$urls=SERVERURL.$_GET['views'];
	if(isset($_POST['idAtt']) && isset($_POST['nameAtt'])){
		echo $insVideo->delete_video_attachment_controller($_POST['idAtt'],$_POST['nameAtt'],$urls);
	}

	$code=explode("/", $_GET['views']);
	$query=$insVideo->execute_single_query("SELECT * FROM `curso` WHERE Estado=4;");

	$data=$insVideo->data_video_controller("Only",$code[1]);
	$cod_curso=$insVideo->execute_single_query("SELECT * FROM `curso_clase` WHERE id_clase=$code[1];");

	foreach ($cod_curso as $key) {
		# code...
		$ccurso=$key['id_curso'];
	}

	if($data->rowCount()>0):
		$rows=$data->fetch();


?>
<p class="text-center">
	<a href="<?php echo SERVERURL; ?>classlist/" class="btn btn-info btn-raised btn-sm">
		<i class="zmdi zmdi-long-arrow-return"></i> Volver
	</a>
</p>
<?php if($rows['Adjuntos']!=""): ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-info">
				<div class="panel-heading">
				    <h3 class="panel-title"><i class="zmdi zmdi-attachment"></i> Archivos adjuntos asociados</h3>
				</div>
			  	<div class="panel-body">
			    	<fieldset>
			    		<div class="container-fluid">
			    			<div class="row">
			    				<div class="col-xs-12">
									<table class="table table-striped table-hover ">
										<thead>
											<tr>
												<th>Adjunto</th>
												<th>Eliminar</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$catt=1;
												$attacments=explode(",", $rows['Adjuntos']);
												foreach($attacments as $att): 
											?>
											<tr>
												<td><?php echo $att; ?></td>
												<td>
													<button type="button" class="btn btn-danger btn-raised btn-xs btnFormsAjax" data-action="delatt" data-id="delete-att-<?php $catt; ?>">
														<i class="zmdi zmdi-delete"></i>
													</button>
													<form action="" method="POST" enctype="multipart/form-data" autocomplete="off" id="delete-att-<?php $catt; ?>">
			    										<input type="hidden" name="idAtt" value="<?php echo $rows['id']; ?>">
			    										<input type="hidden" name="nameAtt" value="<?php echo $att; ?>">
													</form>
												</td>
											</tr>
											<?php 
													$catt++;
												endforeach; 
											?>
										</tbody>
									</table>
			    				</div>
			    			</div>
			    		</div>
			    	</fieldset>
			  	</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-success">
				<div class="panel-heading">
				    <h3 class="panel-title"><i class="zmdi zmdi-refresh"></i> Actualizar datos</h3>
				</div>
			  	<div class="panel-body">
				    <form action="<?php echo SERVERURL; ?>ajax/ajaxVideo.php" method="POST" enctype="multipart/form-data" autocomplete="off" data-form="UpdateVideo" class="ajaxDataForm">
				    	<input type="hidden" name="upid" value="<?php echo $rows['id']; ?>">
				    	<fieldset class="full-box">
				    		<legend><i class="zmdi zmdi-videocam"></i> Datos de la clase</legend>
				    		<div class="container-fluid">
								
				    			<div class="row">
				    				<div class="col-xs-12 col-sm-6">
								    	<div class="form-group label-floating">
										  	<span class="control-label">Título *</span>
										  	<input class="form-control" type="text" name="title" value="<?php echo $rows['Titulo']; ?>" required="">
										</div>
				    				</div>
				    				<div class="col-xs-12 col-sm-2">
								    	<div class="form-group label-floating">
										  	<span class="control-label">Horas de la Clase *</span>
										  	<input class="form-control" type="Number" value="<?php echo $rows['horas']; ?>"  name="horas" required="">
										</div>
				    				</div>
				    				<div class="col-xs-12 col-sm-4">
								    	<div class="form-group label-floating">
										  	<span class="control-label">Tutor o Docente *</span>
										  	<input class="form-control" type="text" name="teacher" value="<?php echo $rows['Tutor']; ?>" required="">
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
										  	<span class="control-label">Asignar Curso *</span>
											
											<!-- Campo de búsqueda -->
											<!-- Buscador -->
											<input type="text" id="filtroCursos" placeholder="Buscar curso..." class="form-control" style="margin-bottom:10px;">

											<!-- Select de cursos -->
											<select class="form-control" name="cursos_select" id="cursos_select" size="5" required="">
												<?php 
												foreach ($query as $key) {
													$cod_curso = $key['id_curso'];
													$curso = $key['Titulo'];
													$selected = ($cod_curso == $ccurso) ? 'selected' : '';
													echo "<option value=\"$cod_curso\" $selected>$curso</option>";
												}
												?>
											</select>


											<script>
											document.getElementById('filtroCursos').addEventListener('keyup', function() {
												let filtro = this.value.toLowerCase();
												let opciones = document.getElementById('cursos_select').options;
												for (let i = 0; i < opciones.length; i++) {
													let texto = opciones[i].text.toLowerCase();
													opciones[i].style.display = texto.includes(filtro) ? '' : 'none';
												}
											});
											</script>


										</div>
				    				</div>
									
				    				<div class="col-xs-12">
										<div class="form-group label-floating">
										  	<label class="control-label">Código del vídeo *</label>
										  	<textarea name="upcode" class="form-control" cols="4"><?php echo $rows['Video']; ?></textarea>
										</div>
				    				</div>
				    			</div>
				    		</div>
				    	</fieldset>
				    	<fieldset class="full-box">
							<legend><i class="zmdi zmdi-comment-video"></i> Descripción e información adicional</legend>
							<div class="container-fluid">
								<div class="row">
									<div class="col-xs-12">
										<textarea name="description" class="full-box" id="spv-editor"><?php echo $rows['Descripcion']; ?></textarea>
				    				</div>
								</div>
							</div>
				    	</fieldset>
				    	<fieldset class="full-box">
							<legend><i class="zmdi zmdi-attachment"></i> Agregar más archivos adjuntos</legend>
							<div class="container-fluid">
								<div class="row">
									<div class="col-xs-12">
										<div class="form-group">
											<input type="file" name="attachments[]" multiple="" accept=".jpg, .png, .jpeg, .pdf, .ppt, .pptx, .doc, .docx">
											<div class="input-group">
												<input type="text" readonly="" class="form-control" placeholder="Elija los archivos adjuntos...">
												<span class="input-group-btn input-group-sm">
													<button type="button" class="btn btn-fab btn-fab-mini">
														<i class="zmdi zmdi-attachment-alt"></i>
													</button>
												</span>
											</div>
											<span><small>Tamaño máximo de los archivos adjuntos 5MB. Tipos de archivos permitidos imágenes PNG y JPG, documentos PDF, WORD y POWERPOINT</small></span>
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