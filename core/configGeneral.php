<?php
	define('SERVERURL', 'http://localhost/sistema_aula/'); 
	define('SERVER_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
	define('USER_ADMIN', 'Administrador');
    define('USER_ESTUDIANTE', 'Estudiante');


	const COMPANY = "ACTUALIZACION EDUFIN";
	//const COMPANY = "EduFinCoop";

	/*====================================
	=            Zona horaria            =
	====================================*/
	date_default_timezone_set("America/Guayaquil");

	/**
		Zonas horarias:
		- America/El_Salvador
		- America/Costa_Rica
		- America/Guatemala
		- America/Puerto_Rico
		- America/Panama
		- Europe/Madrid

		Más zonas, visita http://php.net/manual/es/timezones.php

	/*=====  End of Zona horaria  ======*/