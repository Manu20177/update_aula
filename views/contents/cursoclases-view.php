<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-videocam zmdi-hc-fw"></i> Curso</h1>
	</div>
	<p class="lead">
		Aqui podras Realizar y dar seguimiento a las Clases, Evaluaciones y Encuestas de Curso.
	</p>
</div>
<?php 
	require_once "./controllers/cursoclaseController.php";

	$insVideo = new cursoclaseController();

	$urls=SERVERURL.$_GET['views'];

	$datos = $_SESSION["curso_inscrito"] ?? null;

	$code      = $datos['cod'];
	$titulo    = $datos['titulo'];
	$portada   = $datos['portada'];

	if ($portada="attachments/class_portada/") {
		# code...
		$portada=SERVERURL."attachments/class_portada/sin_portada.jpg";
	}
	$id_alumno = $datos['id_alumno'];

	
?>
<p class="text-center">
	<a href="<?php echo SERVERURL; ?>cursolist/" class="btn btn-info btn-raised btn-sm">
		<i class="zmdi zmdi-long-arrow-return"></i> Volver
	</a>
</p>
<fieldset class="full-box">
	<div class="container-fluid">

		<!-- Sección de cursos tipo aula virtual -->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default mt-4 shadow" style="border-radius: 8px;">
					
					<!-- Encabezado del curso -->
					<div class="panel-heading bg-primary text-white text-center" style="padding: 20px; border-radius: 8px 8px 0 0;">
						<h2 class="panel-title mb-0"><strong><?php echo $titulo; ?></strong></h2>
					</div>

					<!-- Contenido del curso -->
					<div class="panel-body" style="padding: 30px;">
						
						<!-- Portada del curso -->
						<div class="text-center mb-4">
							<img src="<?php echo $portada; ?>" 
							     alt="Portada" 
							     class="img-responsive img-thumbnail"
							     style="max-width: 250px; margin: auto;">
						</div>

						<?php
						

					
						$query = $insVideo->obtenerclases_controller($id_alumno, $code);

					$total_juegos=0;
						$total_evaluacion=0;
						$total_encuesta=0;

						$resp_juego=0;
						$resp_evaluacion=0;
						$resp_encuesta=0;
						


						if ($query->rowCount() > 0) {
							foreach ($query as $key) {
								# code...
								$titulo=$key['Titulo'];
								$id_clase=$key['id_clase'];
								$id_juego=$key['id_juego'];
								$tipoj=$key['tipo'];
								

								$valejuego=$insVideo->execute_single_query("SELECT * FROM juegos WHERE id='$id_juego' and estado!=5");
								$valevalua=$insVideo->execute_single_query("SELECT * FROM preguntas_rapidas WHERE id_clase='$id_clase'");
								$valeencuesta=$insVideo->execute_single_query("SELECT * FROM encuesta_rapida WHERE id_clase='$id_clase'");
								
									# code...


								
								$cant_juegos=$insVideo->obtenerCantJuegos($id_juego);

								
								$cant_evaluacion=$insVideo->obtenerCantEvaluaciones($id_clase);
								$cant_encuesta=$insVideo->obtenerCantEncuestas($id_clase);

								$total_juegos=$total_juegos+$cant_juegos;
								$total_evaluacion=$total_evaluacion+$cant_evaluacion;
								$total_encuesta=$total_encuesta+$cant_encuesta;

								$juego_resuelto = $insVideo->check_juego_resuelto_controller($id_alumno, $id_juego);
								$envaluacion_respondida = $insVideo->check_evaluacion_respondida_controller($id_alumno, $id_juego);
								$encuesta_respondida =  $insVideo->check_encuesta_respondida_controller($id_alumno, $id_clase);

								if ($juego_resuelto) {
									# code...
									$resp_juego=$resp_juego+1;

								}
								if ($envaluacion_respondida) {
									# code...
									$resp_evaluacion=$resp_evaluacion+1;

								}
								if ($encuesta_respondida) {
									# code...
									$resp_encuesta=$resp_encuesta+1;

								}



								

								
								?>
		<!-- Clase: Introducción -->
								<div class="course-section mb-4">
									<h4 class="text-primary">📘 Clase: <?php echo $titulo ?></h4>
									<ul class="list-group">
										<li class="list-group-item d-flex justify-content-between align-items-center">
											<span class="activity-label">Video de la clase</span>
											<a href="<?php echo SERVERURL; ?>classview/<?php echo $id_clase; ?>" class="btn btn-link complete-btn" data-id="1">Ver</a>
										</li>
										<?php if ($valejuego->rowCount() >0) {?>
										<li class="list-group-item d-flex justify-content-between align-items-center">
											<span class="activity-label">Juego Interactivo: </span>
											<a href="<?php echo SERVERURL; ?>juego/<?php echo $id_juego; ?>/<?php echo $tipoj; ?>" class="btn btn-link complete-btn" data-id="4">Resolver</a>
										</li>
										<?php }
										 if ($valevalua->rowCount() >0) {?>
										<li class="list-group-item d-flex justify-content-between align-items-center">
											<span class="activity-label">Evaluación</span>
											<a href="<?php echo SERVERURL; ?>preguntas/<?php echo $id_clase; ?>" class="btn btn-link complete-btn" data-id="2">Resolver</a>
										</li>
										<?php }
										if ($valeencuesta->rowCount() >0) {?>

										
										<li class="list-group-item d-flex justify-content-between align-items-center">
											<span class="activity-label">Encuesta</span>
											<a href="<?php echo SERVERURL; ?>encuesta/<?php echo $id_clase; ?>" class="btn btn-link complete-btn" data-id="3">Responder</a>
										</li>
										<?php }?>
									</ul>
								</div>

								<?php
							}
							
							
							if ($total_encuesta==$resp_encuesta && $total_evaluacion==$resp_evaluacion && $total_juegos==$resp_juego) {

								$curso_ins=$insVideo->execute_single_query("SELECT * FROM `curso_alumno` WHERE id_curso='$code' && id_alumno='$id_alumno' && estado_curso=4");
								$Datoscuro=$curso_ins->rowCount();
								# code...
							
								
								if($Datoscuro>0 ){
									$insVideo->completar_curso($id_alumno, $code);
									$datos_Certificado=$insVideo->execute_single_query("SELECT * FROM `curso_alumno` WHERE id_curso='$code' && id_alumno='$id_alumno' && estado_curso=4");

									
									}
									?>
									<div class="course-section mb-4">
										<hr>
										
										<h4 class="text-primary" style="color: green;"> Ha completado el Curso Exitosamente</h4>

										<span class="activity-label">Certificado del Curso</span>
										<!-- /views/certificado-view.php -->
										
									
										<a href="<?php echo SERVERURL; ?>certificado/<?php echo $id_alumno; ?>/<?php echo $code; ?>" class="btn btn-success">
											Descargar Certificado
										</a>								
						</div>

						<?php 



						} 


						}else{
								?>
								<span class="activity-label" style="color:red;" >No se Han registrado Clases dentro de este curso</span>

								<?php
						}

					

						// echo $total_encuesta; //2
						// echo $total_evaluacion; //1


						// echo $resp_encuesta;
						// echo $resp_evaluacion;


					?>
						
						<!-- Clase: Normativa Básica -->
						

					</div> <!-- panel-body -->

				</div> <!-- panel -->
			</div>
		</div>
	</div>
</fieldset>
