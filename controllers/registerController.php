<?php 

	session_start();

	if (isset($_SESSION['email'])) {
	    header("Location: https://mattprofe.com.ar/alumno/11991/app-estacion/panel");
	    exit;
	}
	$tpl = new Primel('views/registerView.html');

	$response = array("errno" => 0, "error" => "");


	include './phpmiler/phpmiler.php';

	if(isset($_POST["btn_submit"])){
		if($_POST['pass'] == $_POST['rep_pass']){
			$usuario = new User($_POST['email']);
			$email = $_POST['email'];
			$response = $usuario->register($_POST['pass'], $email);
			$token_action= trim($usuario->token_action($email));

			if($response["errno"]==200){

				enviarCorreo($email, "Activar usuario", "Bienvenido: <br><br><center><a href='https://mattprofe.com.ar/alumno/11991/app-estacion/validate/" . strval($token_action) . "' style='padding: 10px; background-color: lightblue; color: white; border-radius: 10px; font-size:10pt; text-decoration: none;'>Click aquí para activar tu usuario</a><br></center>");
				header('Location: https://mattprofe.com.ar/alumno/11991/app-estacion/login');
				exit;
			}

		}
		
	}





	$tpl->assign("MSG", $response["error"]);



	$tpl->printToScreen();
 ?>