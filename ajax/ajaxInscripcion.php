<?php
	$actionsRequired=true;
	require_once "../controllers/cursoclaveController.php";

	$insVideo = new cursoclaveController();

	

	if(isset($_POST['clave']) && isset($_POST['cursoclave'])){
		echo $insVideo->add_inscripcion_controller();
	}

	