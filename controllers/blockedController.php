<?php 

	$tpl = new Primel('views/blockedView.html');
	session_start();

	$response = array("errno" => 0, "error" => "");

	// capturo por get el token_action
	$get = trim($_SECTION[1]);
	
	include './phpmiler/phpmiler.php';

	$usuario = new User();
	$token_action= $usuario->blocked($get);

	
	// var_dump($email);

	if ($token_action) {
		$email= $_SESSION['email'];
		enviarCorreo($email, "blocked", "usuario bloqueado <br><br><center><a href='https://mattprofe.com.ar/alumno/11991/app-estacion/reset/$token_action' style='padding: 10px; background-color: lightblue; color: white; border-radius: 10px; font-size:10pt; text-decoration: none;'>Click aquí para cambiar contraseña</a><br><br></center>Muchas gracias");
		$response = array("errno" => 102, "error" =>"usuario bloqueado, revise su correo electrónico");
		session_unset();
		session_destroy();
	}
	// if ($token_action == 222) {
	// 	$response = array("errno" => 222, "error" =>"usuario ya bloqueado");
	// }
	else{
		$response = array("errno" => 100, "error" =>"El token no corresponde a un usuario");
	}


	

	$tpl->assign("MSG", $response["error"]);
	
	$tpl->printToScreen();
 ?>