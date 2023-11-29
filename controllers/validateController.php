<?php 

	$tpl = new Primel('views/validateView.html');
	session_start();

	if (isset($_SESSION['email'])) {
	    header("Location: https://mattprofe.com.ar/alumno/11991/app-estacion/panel");
	    exit;
	}

	$response = array("errno" => 0, "error" => "");

	// capturo por get el token_action
	$get = trim($_SECTION[1]);
	
	include './phpmiler/phpmiler.php';

	$usuario = new User();
	$ac= $usuario->token_action_veri($get);

	if ($ac != false) {
		enviarCorreo($ac, "usuario activo ", "usuario ya activado <br><br><center><a href='https://mattprofe.com.ar/alumno/11991/app-estacion/login' style='padding: 10px; background-color: lightblue; color: white; border-radius: 10px; font-size:10pt; text-decoration: none;'>Iniciar sesion</a><br><br></center>Muchas gracias");
			header('Location: https://mattprofe.com.ar/alumno/11991/app-estacion/login');
			exit;
	}
	else{
		$response = array("errno" => 100, "error" =>"El token no corresponde a un usuario");
	}


	$tpl->assign("MSG", $response["error"]);
	
	$tpl->printToScreen();
 ?>