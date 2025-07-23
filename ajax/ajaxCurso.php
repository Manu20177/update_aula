<?php
	$actionsRequired=true;
	require_once "../controllers/cursoController.php";

	$insVideo = new cursoController();

	

	if(isset($_POST['cursoCode'])){
		echo $insVideo->delete_curso_controller($_POST['cursoCode']);
	}

	if(isset($_POST['upid'])){
		echo $insVideo->update_curso_controller();
	}else if(isset($_POST['title'])){
		echo $insVideo->add_curso_controller();
	}