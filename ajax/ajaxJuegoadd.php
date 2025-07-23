<?php
	$actionsRequired=true;
	require_once "../controllers/juegosController.php";

	$insVideo = new juegosController();

	
//ENCUESTA


	if(isset($_POST['clase_select'])){
		echo $insVideo->add_juego_controller();
	}else if(isset($_POST['id_juego'])){
		echo $insVideo->delete_juego_controller($_POST['id_juego']);
	}
