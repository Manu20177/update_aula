<?php
$actionsRequired = true;
require_once "../controllers/encuestaController.php";
$encuestaController = new encuestaController();

$id_clase = isset($_GET['id_clase']) ? intval($_GET['id_clase']) : 0;

if ($id_clase <= 0) {
    die("<p class='text-center'>Clase no válida</p>");
}

// Cargar preguntas
$preguntas = $encuestaController->cargar_preguntas_controller($id_clase);
// Cargar preguntas
if (!empty($preguntas)) {
    $clase_title = $preguntas[0]['Titulo']; // Accedemos al primer elemento del arreglo
}
?>

<form action="<?php echo SERVERURL; ?>ajax/ajaxEncuesta.php" method="POST" class="ajaxDataForm" data-form="evaluacion">
    <h3 class="text-titles text-center"><i class="zmdi zmdi-equalizer"></i> Encuesta <?php echo $clase_title?></h3>
    
    <!-- Botón para eliminar toda la encuesta -->
    <button type="button" class="btn btn-danger btn-sm delete-btn-all" 
			data-idclase="<?= $id_clase ?>">
		<i class="zmdi zmdi-delete"></i> Eliminar toda la encuesta
	</button>

    <input type="hidden" name="id_clase" value="<?= $id_clase ?>">
    <input type="hidden" name="id_usuario" value="<?= $_SESSION['userKey'] ?>">

    <style>
        .question-group { margin-bottom: 25px; position: relative; }
        .delete-btn {
            position: absolute;
            top: 0;
            right: 0;
        }
    </style>

    <?php foreach ($preguntas as $p): ?>
        <div class="form-group question-group">
            <!-- Botón de eliminar pregunta -->
           <button type="button" class="btn btn-sm btn-warning delete-btn-pregunta" 
				data-idpregunta="<?= $p['id_pregunta'] ?>" 
				data-idclase="<?= $id_clase ?>">
			<i class="zmdi zmdi-close-circle"></i>
		</button>

            <label><?= htmlspecialchars($p['pregunta']); ?></label>

            <?php if ($p['tipo'] == 'multiple'): ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="preguntas[<?= $p['id_pregunta']; ?>]" value="<?= htmlspecialchars($p['opcion1']) ?>" required>
                    <label class="form-check-label"><?= htmlspecialchars($p['opcion1']) ?></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="preguntas[<?= $p['id_pregunta']; ?>]" value="<?= htmlspecialchars($p['opcion2']) ?>">
                    <label class="form-check-label"><?= htmlspecialchars($p['opcion2']) ?></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="preguntas[<?= $p['id_pregunta']; ?>]" value="<?= htmlspecialchars($p['opcion3']) ?>">
                    <label class="form-check-label"><?= htmlspecialchars($p['opcion3']) ?></label>
                </div>

            <?php elseif ($p['tipo'] == 'verdadero_falso'): ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="preguntas[<?= $p['id_pregunta']; ?>]" value="Verdadero" required>
                    <label class="form-check-label">Verdadero</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="preguntas[<?= $p['id_pregunta']; ?>]" value="Falso">
                    <label class="form-check-label">Falso</label>
                </div>

            <?php elseif ($p['tipo'] == 'completar'): ?>
                <input type="text" name="preguntas[<?= $p['id_pregunta']; ?>]" class="form-control" placeholder="Escribe tu respuesta aquí" required>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <div class="full-box form-process"></div>
</form>