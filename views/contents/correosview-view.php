<?php 
	require_once "./controllers/correoController.php";

	$insVideo = new correoController();

	$dateNow=date("Y-m-d");
	$urls=SERVERURL.$_GET['views'];


	$code=explode("/", $_GET['views']);

	$data=$insVideo->data_correo_controller("Only",$code[1]);

	
	
	if($data->rowCount()>0):
		$rows=$data->fetch();

		$nombres=$rows['nombres'];
		$Usuario=$rows['Usuario'];
		if ($nombres!='') {
			# code...
			$emisor=$nombres;
		}else {
			# code...
			$emisor=$Usuario;
		}
		$Adjuntos=$rows['archivos_adjuntos'];
		if ($Adjuntos!='') {
			# code...
			$dadjunto=$Adjuntos;
		}else{
			$dadjunto= "No se Adjuntaron Archivos";
		}
?>
<p class="text-center">
	<a href="<?php echo SERVERURL; ?>correolist/" class="btn btn-info btn-raised btn-sm">
		<i class="zmdi zmdi-long-arrow-return"></i> Volver
	</a>
</p>
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles" ><i class="zmdi zmdi-email zmdi-hc-fw"></i> <small style="color:black;font-weight: bold;">DETALLES DEL CORREO</small></h1>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
			<!-- <p class="text-mutted"><i class="zmdi zmdi-star-circle"></i> TÍTULO O TEMA: <strong><?php echo $rows['Titulo']; ?></strong></p> -->
			<p class="text-mutted"><i class="zmdi zmdi-hourglass-alt"></i> EMISOR: <strong><?php echo $emisor; ?></strong></p>
			<p class="text-mutted"><i class="zmdi zmdi-hourglass-alt"></i> ASUNTO: <strong><?php echo $rows['asunto']; ?></strong></p>
			<p class="text-mutted"><i class="zmdi zmdi-face"></i> DESTINATARIO(S): <strong><?php echo $rows['destinatarios']; ?></strong></p>
			<p class="text-mutted"><i class="zmdi zmdi-time-restore"></i> FECHA DE ENVIO: <strong><?php echo date("d/m/Y", strtotime($rows['fecha_envio'])); ?></strong></p>
			<div class="full-box thumbnail" style="padding: 10px;">
				<h3 class="text-titles text-center"><i class="zmdi zmdi-info"></i> Información del Correo</h3>
				<?php 
					echo $rows['contenido'];
					if($rows['Adjuntos']!=""):
				?>
				<br>
				<h4 class="text-titles text-center"><i class="zmdi zmdi-cloud-download"></i> Archivos Adjuntos</h4>
				<table class="table">
					<thead>
						<tr>
							<th>Archivo</th>
							<th>Descargar</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$attachment=explode(",", $rows['Adjuntos']);
							foreach ($attachment as $files):
								echo '
								<tr>
									<td>'.$files.'</td>
									<td>
										<a href="'.SERVERURL.'attachments/email/'.$files.'" download="'.$files.'" class="btn btn-primary"><i class="zmdi zmdi-download"></i></a>
									</td>
								</tr>
								';
							endforeach;
						?>
					</tbody>
				</table>
			
				<?php
				else:

					?>
					<div class="panel-body">
						<div class="table-responsive">
							<div class="alert alert-info text-center" role="alert">
								<i class="zmdi zmdi-folder-outline zmdi-hc-2x"></i>
								<p class="lead m-t-10">No se Adjuntaron archivos a este Correo</p>
								
							</div>
						</div>
					</div>

					<?php
			endif; ?>
			</div>
		</div>
	</div>
</div>
<br>

<?php else: ?>
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-videocam zmdi-hc-fw"></i> Correos</h1>
	</div>
</div>
<p class="lead text-center">Lo sentimos ocurrió un error inesperado</p>
<?php endif; ?>