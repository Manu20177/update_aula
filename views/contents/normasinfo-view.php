<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-settings zmdi-hc-fw"></i> Datos de la Norma</h1>
	</div>
	<p class="lead">
		Bienvenido a la sección de actualización de los datos de las Normas. Acá podrá actualizar la información de las normas registrados en el sistema.
	</p>
</div>
<?php 
	require_once "./controllers/normasController.php";

	$normasIns = new normasController();

	if(isset($_POST['code'])){
		echo $normasIns->update_norma_controller();
	}

	$code=explode("/", $_GET['views']);

	$data=$normasIns->data_norma_controller("Only",$code[1]);
	if($data->rowCount()>0):
		$rows=$data->fetch();
?>
<?php if($_SESSION['userType']=="Administrador"): ?>

<p class="text-center">
	<a href="<?php echo SERVERURL; ?>normaslist/" class="btn btn-info btn-raised btn-sm">
		<i class="zmdi zmdi-long-arrow-return"></i> Volver
	</a>
</p>
<?php endif; ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-success">
				<div class="panel-heading">
				    <h3 class="panel-title"><i class="zmdi zmdi-refresh"></i> Actualizar datos</h3>
				</div>
			  	<div class="panel-body">
				    <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
				    	<fieldset>
				    		<legend><i class="zmdi zmdi-account-box"></i> Datos de la Norma</legend><br>
				    		<input type="hidden" name="code" value="<?php echo $rows['id_tipoc']; ?>">
				    		<div class="container-fluid">
				    			<div class="row">
									<div class="col-xs-12 col-sm-6">
								    	<div class="form-group label-floating">
										  	<label class="control-label">Titulo Corto *</label>
										  	<input pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" type="text" name="titulo" value="<?php echo $rows['norma']; ?>" required="" maxlength="55">
										</div>
				    				</div>
				    				<div class="col-xs-12 col-sm-6">
								    	<div class="form-group label-floating">
										  	<label class="control-label">Descripción *</label>
										  	<input pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,255}" class="form-control" type="text" name="descripcion" value="<?php echo $rows['descripcion']; ?>" required="" maxlength="255">
										</div>
				    				</div>
				    				
				    			</div>
				    		</div>
				    	</fieldset>
					    <p class="text-center">
					    	<button type="submit" class="btn btn-success btn-raised btn-sm"><i class="zmdi zmdi-refresh"></i> Guardar cambios</button>
					    </p>
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

?>