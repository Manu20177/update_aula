<?php 
	require_once "./controllers/studentController.php";

	$insStudent = new studentController();

	if(isset($_POST['name']) && isset($_POST['username'])){
		echo $insStudent->add_acountstudent_controller();
	}
?>
<div class="full-box cover containerLogin">
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
				<div class="panel panel-info">
					<div class="panel-heading">
				    <h3 class="panel-title"><i class="zmdi zmdi-plus"></i> Registro al Mi Aula Credito Familiar</h3>
				</div>
				<style>.scrollable-form {
    max-height: 90vh;
    overflow-y: auto;
    overflow-x: hidden;
	
    padding-right: 10px;
	
}</style>
			  	<div class="panel-body scrollable-form">
				    <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                        
                        <!-- Datos personales -->
                        <fieldset>
                            <legend><i class="zmdi zmdi-account-box"></i> Datos Personales</legend><br>
                            <div class="row">
                                <div class="col-xs-12 col-sm-4">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Cédula * <div id="cedula-status" style="margin-top:0px;"></div></label> <!-- Aquí va el mensaje -->

                                        <input pattern="[0-9]{10}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                            class="form-control" type="text" name="cedula"
                                            value="<?php if(isset($_POST['cedula'])) echo $_POST['cedula']; ?>" required>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Nombres *</label>
                                        <input pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" type="text" name="name" value="<?php if(isset($_POST['name'])) echo $_POST['name']; ?>" required maxlength="30">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Apellidos *</label>
                                        <input pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" type="text" name="lastname" value="<?php if(isset($_POST['lastname'])) echo $_POST['lastname']; ?>" required maxlength="30">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Email</label>
                                        <input class="form-control" type="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Teléfono / Celular *</label>
                                        <input pattern="[0-9]{1,10}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control" type="text" name="telefono" value="<?php if(isset($_POST['telefono'])) echo $_POST['telefono']; ?>" required>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Tipo de Usuario *</label>
                                        <select class="form-control" name="tipousu">
                                            <option value="1">Socio</option>
                                            <option value="2">Representante</option>
                                            <option value="3">Trabajador</option>
                                            <option value="4">Directivo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Nivel de Estudios *</label>
                                        <select class="form-control" name="nivel">
                                            <option value="Inicial">Educación Inicial</option>
                                            <option value="Primaria">Educación General Básica</option>
                                            <option value="Secundaria">Bachillerato General Unificado</option>
                                            <option value="TercerNivel">Educación Superior</option>
                                            <option value="Postgrado">Postgrado</option>
                                        </select>
                                    </div>
                                </div>
                                 <div class="col-xs-12 col-sm-4">
                                    <div class="form-group label-floating">
                                        <select class="form-control" name="provincia" id="provincia">
                                            <option value="">Seleccione una provincia</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-4">
                                    <div class="form-group label-floating">
                                        <select class="form-control" name="canton" id="canton" disabled>
                                            <option value="">Seleccione una provincia primero</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-4">
                                    <div class="form-group label-floating">
                                        <select class="form-control" name="parroquia" id="parroquia" disabled>
                                            <option value="">Seleccione un cantón primero</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Actividad Económica *</label>
                                        <select class="form-control" name="actividad">
                                            <option value="Agricultura">Agricultura</option>
                                            <option value="Ganadería">Ganadería</option>
                                            <option value="Pesca">Pesca</option>
                                            <option value="Silvicultura">Silvicultura</option>
                                            <option value="Minería">Minería</option>
                                            <option value="Petróleo y Gas">Petróleo y Gas</option>
                                            <option value="Industria Manufacturera">Industria Manufacturera</option>
                                            <option value="Construcción">Construcción</option>
                                            <option value="Comercio">Comercio</option>
                                            <option value="Servicios">Servicios</option>
                                            <option value="Turismo">Turismo</option>
                                            <option value="Tecnología e Informática">Tecnología e Informática</option>
                                            <option value="Educación">Educación</option>
                                            <option value="Salud">Salud</option>
                                            <option value="Artes y Entretenimiento">Artes y Entretenimiento</option>
                                            <option value="Transporte y Logística">Transporte y Logística</option>
                                            <option value="Finanzas y Banca">Finanzas y Banca</option>
                                            <option value="Administración Pública">Administración Pública</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Grupo Étnico *</label>
                                        <select class="form-control" name="etnia">
                                            <option value="Mestizo">Mestizo</option>
                                            <option value="Indígena">Indígena</option>
                                            <option value="Afroecuatoriano">Afroecuatoriano</option>
                                            <option value="Blanco">Blanco</option>
                                            <option value="Montuvio">Montuvio</option>
                                            <option value="Negro">Negro</option>
                                            <option value="Mulato">Mulato</option>
                                            <option value="Asiático">Asiático</option>
                                            <option value="Otro">Otro</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Datos de cuenta -->
                        <fieldset>
                            <legend><i class="zmdi zmdi-key"></i> Datos de la Cuenta</legend><br>
                            <div class="row">
                                <div class="col-xs-12 col-sm-3">
                                   <div class="form-group label-floating">
										<label class="control-label">Nombre de usuario *</label>
										<input id="username" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ]{1,15}" 
											class="form-control" type="text" name="username" id="username"
											required maxlength="15">
										<!-- Mensaje de disponibilidad -->
										<div id="username-status" style="margin-top: 5px;"></div>
									</div>
                                </div>
                                <div class="col-xs-12 col-sm-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Género</label>
                                        <select name="gender" class="form-control">
                                            <?php if(isset($_POST['gender'])) echo '<option>'.$_POST['gender'].'</option>'; ?>
                                            <option value="Masculino">Masculino</option>
                                            <option value="Femenino">Femenino</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-3" style="position: relative;">
                                    <div class="form-group label-floating">

                                        <label class="control-label">Contraseña *</label>
                                        
                                        <input class="form-control" type="password" name="password1" id="password1" value="<?php if(isset($_POST['password1'])) echo $_POST['password1']; ?>" required maxlength="70">
                                        <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;" onclick="togglePassword('password1')">
                                            <i class="zmdi zmdi-eye" id="icon-password1"></i>
                                        </span>
                                    </div>

                                </div>

                               <div class="col-xs-12 col-sm-3" style="position: relative;">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Repita la Contraseña *</label>


                                        <input class="form-control" type="password" name="password2" id="password2" value="<?php if(isset($_POST['password2'])) echo $_POST['password2']; ?>" required maxlength="70">
                                        <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;" onclick="togglePassword('password2')">
                                            <i class="zmdi zmdi-eye" id="icon-password2"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="col-xs-12 text-center">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="check_terminos"> Acepto los 
                                    <a href="#" data-toggle="modal" data-target="#modalTerminos">Aviso de Privacidad</a> *
                                </label>
                            </div>
                        </div>

                        <!-- Botones -->
                        <p class="text-center">
                           <button id="registroBtn" type="submit" class="btn btn-success btn-raised btn-sm" disabled>
								<i class="zmdi zmdi-floppy"></i> Registrate
							</button>
                            <a href="<?php echo SERVERURL; ?>login" class="btn btn-info btn-raised btn-sm">
                                <i class="zmdi zmdi-sign-in"></i> Iniciar Sesión
                            </a>
                        </p>
                    </form>
			  	</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal de Términos y Condiciones -->
<div class="modal fade" id="modalTerminos" tabindex="-1" role="dialog" aria-labelledby="modalTerminosLabel">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="modalTerminosLabel">Aviso de Privacidad</h4>
        </div>
        <div class="modal-body" style="max-height:400px; overflow-y:auto; white-space: normal; font-size: 13px; line-height: 1.2;">
            <p>Cooperativa de Ahorro y Crédito del Emigrante Ecuatoriano y su Familia Ltda., comercialmente conocido como Crédito Familiar, con domicilio en Quisquis 910 entre José de Antepara y Garcia Moreno, es el responsable del uso y protección de sus datos personales.</p>
            <strong>FINALIDADES PRIMARIAS:</strong> Los datos personales que recabamos de usted, los utilizaremos para las siguientes finalidades que son necesarias para el servicio que solicita: gestionar tu acceso y participación en cursos de educación financiera, enviar notificaciones, recordatorios y material educativo, mejorar los contenidos y servicios ofrecidos, evaluar tu perfil para posibles servicios financieros personalizados y cumplir con obligaciones legales y de control interno.<br>
            <strong>FINALIDADES SECUNDARIAS:</strong> De manera adicional, utilizaremos su información personal para las siguientes finalidades secundarias que no son necesarias para el servicio solicitado, pero que nos permiten y facilitan brindarle una mejor atención: mercadotecnia o publicidad, prospección comercial, elaboración de perfiles financieros o educativos a partir del análisis de participación, hábitos de estudio y preferencias con fines de segmentación o mejoras comerciales, invitación a encuestas, estudios de mercado o investigaciones vinculadas al desarrollo de productos financieros o educativos.<br>
            <strong>DATOS PERSONALES RECABADOS:</strong> Para las finalidades señaladas en el presente aviso de privacidad, podemos recabar sus datos de identificación y contacto, datos sobre características físicas, datos laborales, datos académicos, datos de acceso y uso, y datos financieros.<br>
            <strong>DATOS SENSIBLES:</strong> Además, nos comprometemos a que los siguientes datos sensibles recabados: datos sobre ideología, creencias religiosas, filosóficas, morales, opiniones políticas y afiliación sindical, datos sobre origen étnico o racial, serán tratados bajo las más estrictas medidas de seguridad que garanticen su confidencialidad.<br>
            <strong>DERECHOS ARCO:</strong> Usted tiene derecho a conocer qué datos personales tenemos de usted, para qué los utilizamos y las condiciones del uso que les damos (Acceso). Asimismo, es su derecho solicitar la corrección de su información personal en caso de que esté desactualizada, sea inexacta o incompleta (Rectificación); que la eliminemos de nuestros registros o bases de datos cuando considere que la misma no está siendo utilizada adecuadamente (Cancelación); así como oponerse al uso de sus datos personales para fines específicos (Oposición). Estos derechos se conocen como derechos ARCO. Para el ejercicio de cualquiera de los derechos ARCO, usted deberá presentar la solicitud respectiva a través del mismo correo electrónico donde se envió la petición. La respuesta a su solicitud será atendida en un plazo máximo de 20 días hábiles.<br>
            <strong>DATOS RECABADOS POR EL SITIO WEB:</strong> Nuestro sitio web recaba automáticamente los siguientes datos: identificadores, nombre de usuario y contraseñas de sesión, fecha y hora del inicio y final de una sesión de un usuario.<br>
            <strong>CONTACTO:</strong> Para más información sobre este aviso de privacidad, puede contactarnos en: correo electrónico <a href="mailto:ccf@creditofamiliar.com.ec">ccf@creditofamiliar.com.ec</a>, sitio web <a href="http://aulaccf.creditofamiliar.com.ec/" target="_blank" rel="noopener">http://aulaccf.creditofamiliar.com.ec/</a>.<br><br>
            <small>Última actualización: 16/7/2025</small>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-info" data-dismiss="modal">He leído y entiendo</button>
        </div>
        </div>



  </div>
</div>
<script>
$(document).ready(function () {
    $('input[name="cedula"]').on('input', function () {
        const cedula = $(this).val();
        const mensaje = $('#cedula-status');

        if (cedula.length === 10) {
            if (validarCedulaEcuatoriana(cedula)) {
                mensaje.html('<span class="text-success">✅ Cédula válida</span>');
            } else {
                mensaje.html('<span class="text-danger">❌ Cédula inválida</span>');
            }
        } else {
            mensaje.html('');
        }
    });
    // Validación en tiempo real de las contraseñas
    $('input[name="password1"], input[name="password2"]').on('input', function() {
        const pass1 = $('input[name="password1"]').val();
        const pass2 = $('input[name="password2"]').val();

        if (pass2.length === 0) {
            $('#passMatchMessage').remove();
            $('input[name="password2"]').removeClass('is-valid is-invalid');
            return;
        }

        if (pass1 === pass2) {
            if ($('#passMatchMessage').length === 0) {
                $('input[name="password2"]').after('<small id="passMatchMessage" class="text-success">Las contraseñas coinciden.</small>');
            } else {
                $('#passMatchMessage').text('Las contraseñas coinciden.').removeClass('text-danger').addClass('text-success');
            }
            $('input[name="password2"]').removeClass('is-invalid').addClass('is-valid');
        } else {
            if ($('#passMatchMessage').length === 0) {
                $('input[name="password2"]').after('<small id="passMatchMessage" class="text-danger">Las contraseñas no coinciden.</small>');
            } else {
                $('#passMatchMessage').text('Las contraseñas no coinciden.').removeClass('text-success').addClass('text-danger');
            }
            $('input[name="password2"]').removeClass('is-valid').addClass('is-invalid');
        }
    });
    let usernameDisponible = false;
    let terminosAceptados = false;

    function actualizarEstadoBoton() {
        const habilitar = usernameDisponible && terminosAceptados;
        $('#registroBtn').prop('disabled', !habilitar);
    }

    // Validar disponibilidad de usuario
    $('#username').on('input', function () {
        const username = $(this).val().trim();

        if (username.length < 3) {
            $('#username-status').html('<span class="text-warning">Escribe al menos 3 caracteres.</span>');
            usernameDisponible = false;
            actualizarEstadoBoton();
            return;
        }

        $('#username-status').html('<i class="zmdi zmdi-refresh-alt zmdi-hc-spin"></i> Validando...');

        $.ajax({
            url: '<?php echo SERVERURL."ajax/ajaxValidarUsuario.php"?>',
            method: 'POST',
            data: { username: username },
            dataType: 'json',
            success: function (response) {
                if (response.disponible === true) {
                    $('#username-status').html('<span class="text-success"><i class="zmdi zmdi-check-circle"></i> Usuario disponible</span>');
                    usernameDisponible = true;
                } else {
                    $('#username-status').html('<span class="text-danger"><i class="zmdi zmdi-close-circle"></i> Usuario no disponible</span>');
                    usernameDisponible = false;
                }
                actualizarEstadoBoton();
            },
            error: function () {
                $('#username-status').html('<span class="text-warning">Error al validar.</span>');
                usernameDisponible = false;
                actualizarEstadoBoton();
            }
        });
    });

    // Detectar aceptación de términos
    $('#check_terminos').change(function () {
        terminosAceptados = $(this).is(':checked');
        actualizarEstadoBoton();
    });

    // Cargar provincias
    $.ajax({
        url: '<?php echo SERVERURL."ajax/ajaxLocalidades.php"?>',
        type: 'POST',
        data: { action: 'load_provinces' },
        success: function(response) {
            $('#provincia').html(response);
        }
    });

    // Cargar cantones cuando cambia la provincia
    $('#provincia').change(function () {
        const id_provincia = $(this).val();
        if (id_provincia) {
            $.ajax({
                url: '<?php echo SERVERURL."ajax/ajaxLocalidades.php"?>',
                type: 'POST',
                data: { action: 'load_cantons', id_provincia: id_provincia },
                success: function(response) {
                    $('#canton').html(response).prop('disabled', false);
                    $('#parroquia').html('<option value="">Seleccione un cantón</option>').prop('disabled', true);
                }
            });
        } else {
            $('#canton').html('<option value="">Seleccione una provincia</option>').prop('disabled', true);
            $('#parroquia').html('<option value="">Seleccione un cantón</option>').prop('disabled', true);
        }
    });

    // Cargar parroquias cuando cambia el cantón
    $('#canton').change(function () {
        const id_canton = $(this).val();
        if (id_canton) {
            $.ajax({
                url: '<?php echo SERVERURL."ajax/ajaxLocalidades.php"?>',
                type: 'POST',
                data: { action: 'load_parishes', id_canton: id_canton },
                success: function(response) {
                    $('#parroquia').html(response).prop('disabled', false);
                }
            });
        } else {
            $('#parroquia').html('<option value="">Seleccione un cantón</option>').prop('disabled', true);
        }
    });
});
function togglePassword(id) {
    const input = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('zmdi-eye');
        icon.classList.add('zmdi-eye-off');
    } else {
        input.type = 'password';
        icon.classList.remove('zmdi-eye-off');
        icon.classList.add('zmdi-eye');
    }
}

function validarCedulaEcuatoriana(cedula) {
    if (!/^\d{10}$/.test(cedula)) return false;

    const provincia = parseInt(cedula.substring(0, 2), 10);
    if (provincia < 1 || provincia > 24) return false;

    const tercerDigito = parseInt(cedula[2], 10);
    if (tercerDigito > 6) return false;

    const coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
    let suma = 0;

    for (let i = 0; i < 9; i++) {
        let valor = coeficientes[i] * parseInt(cedula[i]);
        if (valor >= 10) valor -= 9;
        suma += valor;
    }

    const digitoVerificador = (10 - (suma % 10)) % 10;
    return digitoVerificador === parseInt(cedula[9]);
}
</script>
