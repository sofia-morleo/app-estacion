<?php 

	$tpl = new Primel('views/detalleView.html');

	 $tpl->assign("CHIPID",$_SECTION[1]);
	 session_start();

	 if (!isset($_SESSION['email'])) {
	    header("Location: https://mattprofe.com.ar/alumno/11991/app-estacion/login");
	    exit;
	}

	


	$tpl->printToScreen();
 ?>