<html>
		<head>
			<link rel="stylesheet" type="text/css" href="../static/css/css.css">
		</head>
		<body>
		<h1>Reset</h1>
<?php 

	$tpl = new Primel('views/resetView.html');
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
	$tok= trim($usuario->veri_tok($get));
	// var_dump($tok);
	if ($tok == false) 
	{
		$response = array("errno" => 111, "error" => "El Token no es valido");
	}
	else{
		echo '
			<form action="" method="POST">
			        <label>Contraseña:</label>
			        <input type="password" name="pass" placeholder="Ingrese la contraseña" required>
			        <br>
			        <label>Repetir Contraseña:</label>
			        <input type="password" name="rep_pass" placeholder="Ingrese de nuevo" required>
			        <br>
			        <input type="submit" name="btn_submit" value="Acceder" id="btnSubmit">
		    	</form>

		';
    	if(isset($_POST["btn_submit"])){
			if($_POST['pass'] == $_POST['rep_pass']){

				$email= $usuario->reset($_POST['pass']);
				if ($email) {
					enviarCorreo($email, "Se restablecio su contraseña", "se restablecio la contraseña <br><br><center><a href='https://mattprofe.com.ar/alumno/11991/app-estacion/blocked/$tok' style='padding: 10px; background-color: lightblue; color: white; border-radius: 10px; font-size:10pt; text-decoration: none;'>No fui yo, bloquear cuenta</a><br><br></center>Muchas gracias");

					$response = array("errno" => 100, "error" => "Se restablecio la contrar");
					header('Location: https://mattprofe.com.ar/alumno/11991/app-estacion/login');
					exit;
				}
			}
		}
	}
	

	$tpl->assign("MSG", $response["error"]);
	
	$tpl->printToScreen();
 ?>
</body>
</html>