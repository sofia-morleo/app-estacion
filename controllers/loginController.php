<?php
session_start();

if (isset($_SESSION['email'])) {
    header("Location: https://mattprofe.com.ar/alumno/11991/app-estacion/panel");
    exit;
}

$tpl = new Primel('views/loginView.html');

$response = array("errno" => 0, "error" => "");


	//varibles de los datos de usurio
	 $ipUsuario = $_SERVER['REMOTE_ADDR'];
	 $sistemaOperativo = php_uname('s');
	 $navegadorUsuario = $_SERVER['HTTP_USER_AGENT'];



include './phpmiler/phpmiler.php';

if (isset($_POST['btn_submit'])) {
    $email = $_POST["email"];
    $contrasena = $_POST["pass"];

    $usuarioObj1 = new User($email);
    $token = trim($usuarioObj1->token($email));
    $activo = $usuarioObj1->activo($email);
    $bloqueado = $usuarioObj1->bloqueado($email);
    $recupero = $usuarioObj1->recupero($email);
    
    // Verificar las credenciales (puedes adaptar esto según tu lógica de autenticación)
    $veri= verificarCredenciales($email, $contrasena,$activo,$bloqueado,$recupero);
    if ($veri == 200) {
        // Inicio de sesión exitoso
            $_SESSION['email'] = $email;
            enviarCorreo($email, "Inicio de sesion exitoso", "IP: $ipUsuario<br>Sistema Operativo: $sistemaOperativo<br>Navegador: $navegadorUsuario<br>Si no has iniciado sesión, haz clic en el siguiente boton para bloquear tu cuenta:<br><br><center><a href='https://mattprofe.com.ar/alumno/11991/app-estacion/blocked/$token' style='padding: 10px; background-color: lightblue; color: white; border-radius: 10px; font-size:10pt; text-decoration: none;'>Bloquear</a><br></center>");
             $response = array("errno" => 200, "error" => "Se inicio sesion");
                 header("Location: https://mattprofe.com.ar/alumno/11991/app-estacion/panel");
                exit;
        
    } 
    if ($veri == 400) {

            $response= array("errno" => 400, "error" => "El usuario no existe");
    }
    if ($veri == 403) {
           enviarCorreo($email, "Se quiso Inicio de sesion ", "IP: $ipUsuario<br>Sistema Operativo: $sistemaOperativo <br> Navegador: $navegadorUsuario<br><br><center><a href='https://mattprofe.com.ar/alumno/11991/app-estacion/“blocked/$token' style='padding: 10px; background-color: lightblue; color: white; border-radius: 10px; font-size:10pt; text-decoration: none;'>No fui yo, bloquear cuenta</a><br></center>");
             $response = array("errno" => 403, "error" => "credenciales no válidas");
    }

    if ($veri == 404) {
        $response = array("errno" => 404, "error" => "Su usuario aún no se ha validado, revise su casilla de correo");
    }
    if ($veri == 405) {
       $response = array("errno" => 405, "error" => "Su usuario está bloqueado, revise su casilla de correo");
    }
    
    
    
}




function verificarCredenciales($email, $contrasena, $activo, $bloqueado,$recupero) {
    $usuarioObj = new User($email);
    $response = $usuarioObj->login($contrasena , $activo, $bloqueado,$recupero);


    if ($response["errno"] == 200) {
        return 200;
    } 
    if ($response["errno"] == 400) {
        return 400;
    }
    if ($response["errno"] == 403) {
        return 403;
    }
    if ($response["errno"] == 404) {
        return 404;
    }
    if ($response["errno"] == 405) {
        return 405;
    }

}

$tpl->assign("MSG", $response["error"]);

$tpl->printToScreen();


?>
