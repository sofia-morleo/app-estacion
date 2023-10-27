<?php 
	// clase
		// atributos
		// metodos


	/**
	 * 
	 */
	class User extends DBAbstract
	{

		// atributos
		public $email;
		public $pass;
		public $status="offline";
		public $register=true; // deshabilita el logueo
		
		/**
		 * 
		 * Crea el objeto user solicitando email
		 * 
		 * */
		function __construct($email)
		{

			$sql = "SELECT * FROM `users` WHERE email='$email'";

			$response = $this->query($sql);

			// email existe
			if($response->num_rows>0){

				$row = $response->fetch_all(MYSQLI_ASSOC);

				$this->register = false; // habilita el logueo
				$this->pass = $row[0]["pass"];
			}

			$this->email = $email;
		}


		/**
		 * 
		 * Realiza el login verificando el password ingresado
		 * 
		 * */

		public function login($pass){
			
			if(!$this->register){

				if($this->pass==md5($pass)){

					$this->status = "online";

					return array("errno" => 200, "error" => "Se logueo correctamente");
				}else{
					return array("errno"=> 403,"Credenciales incorrectas");
				}

			}else{
				return array("errno" => 400, "error" => "El usuario no existe");	
			}	
		}


		/**
		 * 
		 * Crea usuarios
		 * 
		 * */
		public function register($pass){

			$pass = md5($pass);

			if($this->register){
				$sql ="INSERT INTO `users` ( `email`, `pass`) VALUES ('$this->email', '$pass');";

				$this->query($sql);

				return array("errno" => 200, "error" => "Se creo el usuario correctamente");
			}

			return array("errno" => 400, "error" => "El usuario ya existe");
		}
	}
	




 ?>