<?php
// Verificar sesión iniciada
if (!isset($_SESSION['userKey'])) {
    die("Usuario no autenticado");
}

// Instanciar controlador de preguntas
require_once "./controllers/encuestaController.php";
$encuestaController = new encuestaController();

// Obtener ID de la clase desde la URL
if (isset($_GET['views'])) {
    $partes = explode("/", $_GET['views']);
    
    if ($partes[0] === 'encuesta' && !empty($partes[1]) && is_numeric($partes[1])) {
        $id_clase = intval($partes[1]);
    } else {
        die("Ruta no reconocida o ID inválido".$partes[1]);
    }
} else {
    die("No se recibió la vista");
}

// Verificar si el usuario ya respondió la evaluación
$yaRespondio = $encuestaController->check_encuesta_respondida_controller($_SESSION['userKey'], $id_clase);

// Cargar preguntas de la clase
$preguntas = $encuestaController->cargar_preguntas_controller($id_clase);
?>

<?php if (is_array($preguntas) && count($preguntas) > 0): ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">

                <?php if ($yaRespondio): ?>
                    <p class="text-center">
                        <a href="<?php echo SERVERURL; ?>cursoclases/" class="btn btn-info btn-raised btn-sm">
                            <i class="zmdi zmdi-long-arrow-return"></i> Volver
                        </a>
                    </p>
                    <div class="alert alert-success text-center" style="margin-top: 25%;" role="alert">
                        Ya has completado esta encuesta. ¡Gracias!
                    </div>
                <?php else: ?>
                    <form action="<?php echo SERVERURL; ?>ajax/ajaxEncuesta.php" method="POST" class="ajaxDataForm" data-form="evaluacion">
                        <h3 class="text-titles text-center"><i class="zmdi zmdi-equalizer"></i> Encuesta</h3>
                        <input type="hidden" name="id_clase" value="<?php echo $id_clase; ?>">
                        <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['userKey']; ?>">
                        
                        <!-- Token CSRF -->

                        <style>
                            .question-group { margin-bottom: 25px; }
                        </style>

                        <?php foreach ($preguntas as $p): ?>
                            <div class="form-group question-group">
                                <label ><?= htmlspecialchars($p['pregunta']); ?></label>

                                <?php if ($p['tipo'] == 'multiple'): ?>
                                    <div class="radio">
                                        <label style="color:black">
                                            <input type="radio" name="preguntas[<?= $p['id_pregunta']; ?>]" value="<?= htmlspecialchars($p['opcion1']) ?>" required>
                                            <?= htmlspecialchars($p['opcion1']) ?>
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label style="color:black">
                                            <input type="radio" name="preguntas[<?= $p['id_pregunta']; ?>]" value="<?= htmlspecialchars($p['opcion2']) ?>">
                                            <?= htmlspecialchars($p['opcion2']) ?>
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label style="color:black">
                                            <input type="radio" name="preguntas[<?= $p['id_pregunta']; ?>]" value="<?= htmlspecialchars($p['opcion3']) ?>">
                                            <?= htmlspecialchars($p['opcion3']) ?>
                                        </label>
                                    </div>

                                <?php elseif ($p['tipo'] == 'verdadero_falso'): ?>
                                    <div class="radio">
                                        <label style="color:black">
                                            <input type="radio" name="preguntas[<?= $p['id_pregunta']; ?>]" value="Verdadero" required>
                                            Verdadero
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label style="color:black">
                                            <input type="radio" name="preguntas[<?= $p['id_pregunta']; ?>]" value="Falso">
                                            Falso
                                        </label>
                                    </div>

                                <?php elseif ($p['tipo'] == 'completar'): ?>
                                    <input style="color:black" type="text" name="preguntas[<?= $p['id_pregunta']; ?>]" class="form-control" placeholder="Escribe tu respuesta aquí" required>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>

                       <p class="text-center">
                            <button type="submit" id="enviarBtn" class="btn btn-success btn-raised" disabled>Enviar Encuesta</button>
                        </p>
                        <div class="full-box form-process"></div>

                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-warning text-center" role="alert">
                    No existen preguntas en esta encuesta.
                </div>
                <p class="text-center">
                    <a href="<?php echo SERVERURL; ?>cursoclases/" class="btn btn-info btn-raised btn-sm">
                        <i class="zmdi zmdi-long-arrow-return"></i> Volver
                    </a>
                </p>
            </div>
        </div>
    </div>
<?php endif; 

		
	
?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const enviarBtn = document.getElementById("enviarBtn");
    const grupos = document.querySelectorAll(".question-group");

    function validarFormulario() {
        let completo = true;

        grupos.forEach(grupo => {
            // Verificar radios
            const radios = grupo.querySelectorAll("input[type='radio']");
            if (radios.length > 0) {
                const seleccionado = Array.from(radios).some(radio => radio.checked);
                if (!seleccionado) completo = false;
            }

            // Verificar campo de texto
            const inputTexto = grupo.querySelector("input[type='text']");
            if (inputTexto && inputTexto.hasAttribute('required')) {
                if (!inputTexto.value.trim()) completo = false;
            }
        });

        enviarBtn.disabled = !completo;
    }

    // Agregar eventos a todos los campos relevantes
    grupos.forEach(grupo => {
        const radios = grupo.querySelectorAll("input[type='radio']");
        const inputTexto = grupo.querySelector("input[type='text']");

        radios.forEach(radio => {
            radio.addEventListener("change", validarFormulario);
        });

        if (inputTexto) {
            inputTexto.addEventListener("input", validarFormulario);
        }
    });

    // Validar inicialmente
    validarFormulario();
});
</script>