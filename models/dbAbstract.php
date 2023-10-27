<?php 


	/**
	 * 
	 */
	class DBAbstract
	{

		public $db;

		private function connect(){
			$this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		}

		public function query($sql){
			$this->connect();
			return $this->db->query($sql);

			//
		}
	}


 ?>