<?php 
	require_once "./controllers/cursoController.php";

	$insVideo = new cursoController();

	$dateNow=date("Y-m-d");
	$urls=SERVERURL.$_GET['views'];


	$code=explode("/", $_GET['views']);

	$data=$insVideo->data_curso_controller("Only",$code[1]);
	if($data->rowCount()>0):
		$rows=$data->fetch();
?>
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles" ><i class="zmdi zmdi-videocam zmdi-hc-fw"></i> <small style="color:black;font-weight: bold;"><?php echo $rows['Titulo']; ?></small></h1>
	</div>
</div>
<p class="text-center">
	<a href="<?php echo SERVERURL; ?>listacursos/" class="btn btn-info btn-raised btn-sm">
		<i class="zmdi zmdi-long-arrow-return"></i> Volver
	</a>
</p>
<div class="container-fluid">
	<div class="row">
		<div class="row">
			<?php
			// Ruta base donde se almacenan las portadas
			$rutaPortadas = '/attachments/class_portada/';

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
		<div class="col-xs-12">
			<p class="text-mutted"><i class="zmdi zmdi-key"></i> Clave de Acesso: <strong><?php echo $rows['id_curso']; ?></strong></p>
			<p class="text-mutted"><i class="zmdi zmdi-time-restore"></i> FECHA DE PUBLICACIÓN: <strong><?php echo date("d/m/Y", strtotime($rows['Fecha'])); ?></strong></p>
			
		
		</div>
	</div>
</div>
<br>

<?php else: ?>
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-videocam zmdi-hc-fw"></i> Curso</h1>
	</div>
</div>
<p class="lead text-center">Lo sentimos ocurrió un error inesperado</p>
<?php endif; ?>