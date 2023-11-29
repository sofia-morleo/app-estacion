<?php 

	$tpl = new Primel('views/detalleView.html');

	session_start();

	session_unset();

  	session_destroy();

  	header('Location: https://mattprofe.com.ar/alumno/11991/app-estacion/login');

	


	$tpl->printToScreen();
 ?>