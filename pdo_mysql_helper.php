<?php
	//The Mysqli Helper 2015 - Jake Siegers
	//MIT License (See included LICENSE file)

	class pdo_mysql_helper{

		//======THESE ARE ALL REQUIRED, EITHER IN CRED FILE, OR IN ARRAY FORM!====
		public $server;	//server ip or hostname
		public $port;	//server port
		public $user;
		public $password;
		public $database;
		//========================================================================

		public $pdo;
		public $preparedStatement;

		public $error;
		public $errorDesc;
		private $errorPrefix = 'pdo_mysql_helper FATAL ERROR: ';

		public $debugMode;

		//accepts string file location of creds, or array of creds.

		function __construct($creds = NULL){

			if($creds === NULL){
				$this->throwError("Tried to create a pdo_mysql_helper without database creds!");
				return false;
			}

			if(is_array($creds)){
				if(!isset($creds['server']) || !isset($creds['user']) || !isset($creds['password']) || !isset($creds['database']) || !isset($creds['port'])){
					$this->throwError("Missing Server, User, Password or Database in config array.");
					return false;
				}
				$this->server = $creds['server'];
				$this->user = $creds['user'];
				$this->password = $creds['password'];
				$this->port = $creds['port'];
				$this->database = $creds['database'];
			}else{
				require_once($creds);
				if(!isset($server) || !isset($user) || !isset($password) || !isset($database) ||  !isset($port)  ){
					$this->throwError("Missing Server, User, Password or Database in config file.");
					return false;
				}
				$this->server = $server;
				$this->user = $user;
				$this->password = $password;
				$this->port = $port;
				$this->database = $database;
			}

			try {
				$this->pdo = new PDO('mysql:dbname='.$this->database.';host='.$this->server.';port='.$this->port,$this->user,$this->password);
			} catch (PDOException $e) {
				$this->throwError($e->getMessage());
				return false;
			}
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}

		private function throwError($msg){
			$this->error = true;
			$this->errorDesc = $this->errorPrefix.$msg;
			throw new Exception($this->errorDesc);
		}

		private function show_stack(){

		}

		// ==============================================================================================================
		// PUBLIC FUNCTIONS BELOW
		// ==============================================================================================================

		//Use ? in your query, then pass values in array $params in the same order.
		function query($query,$params=null){
			$this->preparedStatement = $this->pdo->prepare($query);
			try {
				if(is_null($params)){
					$this->preparedStatement->execute();
				}else{
					$this->preparedStatement->execute($params);
				}
			} catch (PDOException $e) {
				$this->throwError($e->getMessage());
				return false;
			}
		}

		function select_db($new_database){
			$new_database = addslashes($new_database);
			$this->query("USE {$new_database}");
		}
		
		function insert_id(){}
		function affected_rows(){}
		function num_rows(){}
		function fetch_array(){}
		function fetch_all_array(){}

		function rowCount($query = null,$params = null){
			if($query !== null){
				$this->query($query,$params);
			}
			return $this->preparedStatement->rowCount();
		}

		function last_error(){
			return $this->errorDesc;
		}

		function fetch_assoc($query = null,$params = null){
			if($query !== null){
				$this->query($query,$params);
			}
			return $this->preparedStatement->fetch(PDO::FETCH_ASSOC);
		}

		function fetch_all_assoc($query = null,$params = null){
			if($query !== null){
				$this->query($query,$params);
			}
			$result = array();
			while($row = $this->preparedStatement->fetch(PDO::FETCH_ASSOC)){
				$result[] = $row;
			}
			return $result;
		}

		function fetch_column($query = null,$params = null){
			if($query !== null){
				$this->query($query,$params);
			}
			return $this->preparedStatement->fetchColumn();
		}

	}

?>