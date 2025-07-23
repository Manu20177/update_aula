<?php 
require_once "./controllers/correoController.php";

$insVideo = new correoController();

if($_SESSION['userType']=="Administrador"): ?>
<!-- Loader -->
<div id="correo-loader" style="display:none;">
  <div class="loaderc"></div>
</div>
<style>
#correo-loader {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.loaderc {
    border: 8px solid #f3f3f3;
    border-top: 8px solid #3498db;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-edit zmdi-hc-fw"></i> Correo <small>(Redacción)</small></h1>
	</div>
	<p class="lead">
		Bienvenido a la sección de Correo, aquí podrás redactar correo a tus estudiantes (Los campos marcados con * son obligatorios para un correo).
	</p>
</div>
<div class="container-fluid">
	<ul class="breadcrumb breadcrumb-tabs">
	  	<li class="active">
		  	<a href="<?php echo SERVERURL; ?>correo/" class="btn btn-edit">
		  		<i class="zmdi zmdi-plus"></i> Nuevo
		  	</a>
	  	</li>
	  	<li>
	  		<a href="<?php echo SERVERURL; ?>correolist/" class="btn btn-success">
	  			<i class="zmdi zmdi-format-list-bulleted"></i> Lista
	  		</a>
	  	</li>
	</ul>
</div>
<?php 


	$dateNow=date("Y-m-d");
	// $query = $conexion->prepare("SELECT * FROM curso_alumno WHERE id_alumno = :id_alumno AND id_curso = :id_curso");
	// 	$query->bindParam(":id_alumno", $id_alumno);
	// 	$query->bindParam(":id_curso", $id_curso);
	// 	$query->execute();


	$query=$insVideo->execute_single_query("SELECT * FROM `curso` WHERE Estado=4;");

	$Alumnos=$insVideo->execute_single_query("SELECT concat(Nombres,' ',Apellidos) as Nombres, Email FROM `estudiante` ORDER BY `Codigo` DESC;");


?>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-info">
				<div class="panel-heading">
                    <h3 class="panel-title"><i class="zmdi zmdi-edit"></i> Redactar Correo</h3>
                </div>
				
			  	<div class="panel-body">
				    <form action="<?php echo SERVERURL; ?>ajax/ajaxCorreo.php" method="POST" enctype="multipart/form-data" autocomplete="off" data-form="" class="ajaxDataForm">
				    	<fieldset class="full-box">
                    		<legend><i class="zmdi zmdi-account-box-mail"></i> Destinatarios</legend>
				    		<div class="container-fluid">
								<span class="control-label">Tipo de Envio *</span>

								<select class="form-control" name="bd_correos" id="tipoEnvioSelect">
									<option value="">-- Selecciona una opción --</option>

									<option value="individual">Enviar a un alumno</option>
									<option value="lista">Seleccionar varios alumnos</option>
									<option value="csv">Cargar desde CSV</option>
								</select>
				    			<div class="row">
				    				<div id="destinatariosContainer" class="col-xs-12"></div>
				    											
									
				    			</div>
				    		</div>
				    	</fieldset>
				    	<fieldset class="full-box">
							<legend><i class="zmdi zmdi-subject"></i> Asunto y Contenido</legend>
							<div class="container-fluid">
								
								<div class="row">
									<div class="col-xs-12">
                                        <div class="form-group label-floating">
                                            <label class="control-label">Asunto *</label>
                                            <input class="form-control" type="text" name="subject" required>
                                        </div>
                                    </div>
									<div class="col-xs-12">
										  <textarea name="description" class="full-box" id="spv-editor"></textarea>
				    				</div>
								</div>
							</div>
				    	</fieldset>
				    	<fieldset class="full-box">
							<legend><i class="zmdi zmdi-attachment"></i> Archivos adjuntos</legend>
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
					    	<button type="submit" class="btn btn-info btn-raised btn-sm"><i class="zmdi zmdi-floppy"></i> Enviar</button>
					    </p>
					    <div class="full-box form-process"></div>
				    </form>
			  	</div>
			</div>
		</div>
	</div>
</div>
<script>
    // Guardar las opciones del select desde PHP
    var alumnosSelectHTML = `
        <div class="col-xs-12">
            <div class="form-group label-floating">
                <label class="control-label">Seleccionar Alumnos *</label>
                <div class="pull-right">
                    <button type="button" id="toggle-select-all" class="btn btn-sm btn-primary btn-raised">Seleccionar Todos</button>
                </div>
                <!-- Campo de búsqueda -->
                <div class="input-group input-group-sm margin-bottom-sm">
                    <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                    <input type="text" id="searchAlumnos" class="form-control" placeholder="Buscar alumno...">
                </div>
                <!-- Lista de alumnos -->
                <select class="form-control" id="alumnosSelect" name="alumnos[]" multiple size="5" required>
                    <?php if (!empty($Alumnos->rowCount())): ?>
                        <?php foreach ($Alumnos as $row): ?>
                            <option value="<?php echo $row['Email']; ?>">
                                <?php echo $row['Nombres'] . ' | ' . $row['Email']; ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option disabled>No hay alumnos registrados</option>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    `;
</script>
<script>
$(document).ready(function () {
    let scrollPosition = 0;

    // Guardar posición del scroll antes de seleccionar
    $('#destinatariosContainer').on('mousedown', '#alumnosSelect option', function(e) {
        e.preventDefault(); // Evita comportamiento predeterminado

        const selectElement = $('#alumnosSelect')[0];
        scrollPosition = selectElement.scrollTop; // Guardamos posición actual

        const isSelected = $(this).prop('selected');
        $(this).prop('selected', !isSelected); // Alternar selección

        // Restablecer scroll después de un breve delay
        setTimeout(() => {
            selectElement.scrollTop = scrollPosition;
        }, 0);
    });

    // Filtrar alumnos
    $('#destinatariosContainer').on('input', '#searchAlumnos', function () {
        const filter = $(this).val().toLowerCase();
        $('#alumnosSelect option').each(function () {
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.includes(filter));
        });
    });

    // Botón Seleccionar Todos / Deseleccionar Todos
    $(document).on('click', '#toggle-select-all', function () {
        const isChecked = $(this).data('all-selected') || false;
        const options = $('#alumnosSelect option').not('[disabled]');

        if (isChecked) {
            options.prop('selected', false);
            $(this).text('Seleccionar Todos');
        } else {
            options.prop('selected', true);
            $(this).text('Deseleccionar Todos');
        }

        $(this).data('all-selected', !isChecked);
    });
});
</script>
<script>

$(document).ready(function () {
     $('form.ajaxDataForm').on('submit', function() {
        $('#correo-loader').show();

    });
    $('#tipoEnvioSelect').on('change', function () {
        let tipo = $(this).val();
        let html = '';

        $('#destinatariosContainer').empty(); // Limpiar contenido anterior

        if (tipo === 'individual') {
            html = `
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <span class="control-label">Correo Individual *</span>
                        <input class="form-control" type="email" name="correo_individual" placeholder="ejemplo@correo.com" required>
                    </div>
                </div>
            `;
        } else if (tipo === 'lista') {
            html = alumnosSelectHTML; // Usamos el HTML generado desde PHP

        } else if (tipo === 'csv') {
            html = `
                <div class="col-xs-12">
                    <div class="form-group label-floating">
                        <span class="control-label">Archivo CSV *</span>
                        <input type="file" name="csv_file" accept=".csv" required>
                        <div class="input-group">
                            <input type="text" readonly class="form-control" placeholder="Elija un archivo CSV...">
                            <span class="input-group-btn input-group-sm">
                                <button type="button" class="btn btn-fab btn-fab-mini">
                                    <i class="zmdi zmdi-attachment-alt"></i>
                                </button>
                            </span>
                            
                        </div>
                        <small>Tamaño máximo 5MB. Solo archivos .CSV permitidos.</small>
                      
                    </div>

                </div>
                    <a href="<?php echo SERVERURL; ?>archivos/correos/envio_correos.csv">
                        <i class="zmdi zmdi-file-text"></i> Descargar plantilla CSV
                    </a>
            `;
        }

        $('#destinatariosContainer').append(html);
    });
});
</script>
<?php 
	else:
		$logout2 = new loginController();
        echo $logout2->login_session_force_destroy_controller(); 
	endif;
?>
