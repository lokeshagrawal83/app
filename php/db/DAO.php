<?php
	
	require_once('log/Logger.php');

	class DAO
	{
		private $host = 'localhost';
		private $user = 'root';
		private $password = 'Lokesh123';
		private $db = 'mis';

		protected $con;

		public function __construct()
		{
			$this->con = mysqli_connect($this->host, $this->user, $this->password, $this->db);	
			Logger::log(Logger::INFO, "DAO database connection is created!", false);
		}
	/*
		public static function createSuperConnection()
		{
			$dao = new DAO();
			$dao->user = 'root';
			$dao->password = 'Lokesh123'
		}

		public static function createAppConnection()
		{
			$dao = new DAO();
			$dao->user = 'appUser';
			$dao->password = 'appPassword'
		}
	*/
		public function escapeParameter($param)
		{
			return mysqli_real_escape_string($this->con, trim($param));
		}

		public function __destruct()
		{
			if($this->con)
			{
				Logger::log(Logger::INFO, "DAO database connection is closed.", false);
				mysqli_close($this->con);
			}
		}
	}
?>