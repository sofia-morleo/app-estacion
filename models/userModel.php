<?php 
class User extends DBAbstract
{
    // Atributos
    public $email;
    public $pass;
    public $token;
    public $activo;
    public $status = "offline";
    public $register = true; // deshabilita el logueo
    public $nombre; // Agregado para almacenar el nombre del usuario

    /**
     * Crea el objeto user solicitando email.
     */
    public function __construct($email = null)
    {
        if($email){
            $sql = "SELECT * FROM `usu` WHERE email='$email'";
            $response = $this->query($sql);

            if ($response->num_rows > 0) {
                $row = $response->fetch_all(MYSQLI_ASSOC);

                $this->register = false; // habilita el logueo
                $this->pass = $row[0]["contraseña"];
                $this->token = $row[0]["token"];

            }
            else{
                $this->email = trim($email);
            }
        }     

    }
    public function token($email)
    {
        $sql = "SELECT * FROM `usu` WHERE email='$email'";
        $response = $this->query($sql);

        if ($response->num_rows > 0) {
            $row = $response->fetch_all(MYSQLI_ASSOC);

            $token = $row[0]["token"];
            return ($token);
        }

    }
     public function token_action($email = null)
    {
        $sql = "SELECT * FROM `usu` WHERE email='$email'";
        $response = $this->query($sql);

        if ($response->num_rows > 0) {
            $row = $response->fetch_all(MYSQLI_ASSOC);

            $token_action = $row[0]["token_action"];
            return ($token_action);
        }

    }
    public function token_action_veri($token)
    {
        $sql = "SELECT `token_action`, `email` FROM usu WHERE activo = '0'";
        $response = $this->query($sql);
      
        if ($response->num_rows > 0) {
            $rows = $response->fetch_all(MYSQLI_ASSOC);

            foreach ($rows as $row) {

                $email = $row["email"];
                $tok = trim($row["token_action"]);

                if ($token == $tok) {
                    $sql = "UPDATE `usu` SET `activo` = '1', `active_date` = NOW(), `token_action` = NULL WHERE `usu`.`email` = '$email'";
                    $response = $this->query($sql);

                    return $email;
                }
            }
            return false;
        } else {
           
            return false;
        }
    }



    public function activo($email){
        $sql = "SELECT * FROM `usu` WHERE email='$email'";
        $response = $this->query($sql);

        if ($response->num_rows > 0) {
            $row = $response->fetch_all(MYSQLI_ASSOC);

            $activo = $row[0]["activo"];
            if($activo == 1){
                return (true);
            }
            else{
               return (false);
            }
        }
    }
    public function bloqueado($email){
        $sql = "SELECT * FROM `usu` WHERE email='$email'";
        $response = $this->query($sql);

        if ($response->num_rows > 0) {
            $row = $response->fetch_all(MYSQLI_ASSOC);

            $bloque = $row[0]["bloqueado"];
            if($bloque == 0){
                return (true);
            }
            else{
               return (false);
            }
        }
    }
    public function recupero($email){
        $sql = "SELECT * FROM `usu` WHERE email='$email'";
        $response = $this->query($sql);

        if ($response->num_rows > 0) {
            $row = $response->fetch_all(MYSQLI_ASSOC);

            $recupero = $row[0]["recupero"];
            if($recupero == 0){
                return (true);
            }
            else{
               return (false);
            }
        }
    }

    public function blocked($token)
    {   
        $sql = "SELECT `token`, `email`,`bloqueado` FROM usu ";
        $response = $this->query($sql);

        if ($response->num_rows > 0) {
            $rows = $response->fetch_all(MYSQLI_ASSOC);

            foreach ($rows as $row) {
                $email = $row["email"];
                $tok = trim($row["token"]);
                $bloq=$row["bloqueado"];
                // if ($bloq == 0) {
                    if ($token == $tok) {
                    $token_action = $tok;
                    $_SESSION['email']= $email;

                    $sql = "UPDATE `usu` SET `bloqueado` = '1', `blocked_date` = NOW(), `token_action` = '$token_action' WHERE `usu`.`email` = '$email';";

                    $response = $this->query($sql);

                    return $token_action;
                    }
                // }
                // else{
                //     return 222;
                // }
            }

            return false;
        } else {
            return false;
        }
    }

    public function recovery($email){
        if (!$this->register) {
            $token_action= md5($email);
            $sql = "UPDATE `usu` SET `recupero` = '1', `recover_date` = NOW(), `token_action` = '$token_action' WHERE `usu`.`email` = '$email';";

            $response = $this->query($sql);
            return $token_action;
        }
        else{
            return false;
        }
    }

    public function veri_tok($token)
    {   
        $sql = "SELECT `token`, `email`, `token_action` FROM usu ";
        $response = $this->query($sql);

        if ($response->num_rows > 0) {
            $rows = $response->fetch_all(MYSQLI_ASSOC);

            
            foreach ($rows as $row) {
                $tok_ac = $row["token_action"];
                $toke= $row["token"];

                    if ($token == $tok_ac) {
                        $email = $row["email"];
                        $this->email = trim($email);
                        return $toke;
                    }
                } 
        }
         else {
            return false;
        }
    }
    public function reset($pass){
        $email = $this->email;
        $pass_in =md5($pass);
        $sql = "UPDATE `usu` SET `recupero` = '0',`bloqueado`= '0', `contraseña` = '$pass_in', `token_action` = NULL WHERE `usu`.`email` = '$email';";
        $response = $this->query($sql);
        return $email;
    }

    public function login($pass,$activo,$bloq, $recupero)
    {
        if (!$this->register) {
                if($activo){
                    if ($bloq) {
                        if ($recupero) {
                            if ($this->pass != md5($pass)) {
                                return array("errno" => 403, "error" => "Credenciales no válidas");
                            } 
                            else{
                                $this->status = "online";

                                return array("errno" => 200, "error" => "Se logueó correctamente");
                            }
                        }
                        else{
                            return array("errno" => 405, "error" => "Su usuario está recuperacion, revise su casilla de correo");
                        }
                        
                    }
                    else{
                        return array("errno" => 405, "error" => "Su usuario está bloqueado, revise su casilla de correo");
                    }
                    
                }
                else{
                    return array("errno" => 404, "error" => "Su usuario aún no se ha validado, revise su casilla de correo");
                }
        }       
        else{
            return array("errno" => 400, "error" => "El usuario no existe");
        }
    
    }

    /**
     * Crea usuarios.
     */
    public function register($pass, $email)
    {
        $pass = trim(md5($pass));
        $tok = trim(md5($email));

        if ($this->register) {

            $sql = "INSERT INTO `usu` ( `email`, `contraseña`,`activo`, `bloqueado`, `recupero`,`token`,`token_action`) VALUES ('$this->email', '$pass', 0, 0, 0,' $tok', ' $tok');";

            $this->query($sql);
 
            return array("errno" => 200, "error" => "Se creó el usuario correctamente");
        }

        return array("errno" => 400, "error" => "El usuario ya existe <a href='https://mattprofe.com.ar/alumno/11991/app-estacion/login'> Loguearse</a>");
    }
}

 ?>