<?php 

	$tpl = new Primel('views/recoveryView.html');
	session_start();

	if (isset($_SESSION['email'])) {
    header("Location: https://mattprofe.com.ar/alumno/11991/app-estacion/panel");
    exit;
	}

	$response = array("errno" => 0, "error" => "");

	
	include './phpmiler/phpmiler.php';
	
	if (isset($_POST['btn_submit'])) {
		$email = $_POST['email'];
		$usuario = new User($email);
		$token_action= trim($usuario->recovery($email));

		if ($token_action != false) {
			enviarCorreo($email, "restablecimiento de contraseña", "se inició el proceso de restablecimiento de contraseña<br><br><center><a href='https://mattprofe.com.ar/alumno/11991/app-estacion/reset/$token_action' style='padding: 10px; background-color: lightblue; color: white; border-radius: 10px; font-size:10pt; text-decoration: none;'>Click aquí para restablecer contraseña</a><br><br></center>Muchas gracias");
			header('Location: https://mattprofe.com.ar/alumno/11991/app-estacion/login');
			exit;
		}
		else{
			$response = array("errno" => 400, "error" => "El email no se encuentra registrado <a href='https://mattprofe.com.ar/alumno/11991/app-estacion/register'> Registrarse</a>");
		}
    }

	

	$tpl->assign("MSG", $response["error"]);
	
	$tpl->printToScreen();
 ?>