<?php
	$actionsRequired=true;
	require_once "../controllers/encuestaentrevistaController.php";

	$insVideo = new encuestaentrevistaController();

	
//ENCUESTA
	if(isset($_POST['cursoCode'])){
		echo $insVideo->delete_curso_controller($_POST['cursoCode']);
	}

	if(isset($_POST['upid'])){
		echo $insVideo->update_curso_controller();
	}else if(isset($_POST['clase_select'])){
		echo $insVideo->add_evaluacion_controller();
	}