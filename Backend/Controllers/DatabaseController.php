<?php
	class DatabaseController {
		public $connection;
		public function __construct(){}

        public function createConnection(){
			/*$host="127.0.0.1";
			$port=3306;
			$socket="";
			$user="root";
			$password="rootpassword";
			$dbname="sccnetwork";
*/
			$host="prc353.encs.concordia.ca";
			$port=3306;
			$socket="";
			$user="prc353_2";
			$password="D3Ajp5";
			$dbname="prc353_2";
			$this->connection = new mysqli($host, $user, $password, $dbname, $port, $socket) 
				or die ('Could not connect to the database server' . mysqli_connect_error());
            
			$this->connection->set_charset("utf8");
			return $this->connection;
		}

		public function closeConnection(){
			if ($this->connection != null){
				$this->connection->close();
			}
		}

		public function getEscaped($text){
			return $this->createConnection()->real_escape_string($text);
		}

		public function getLastId(){
			return $this->connection->insert_id;
		}

		public function executeSqlQuery($sqlQuery){
			if($this->createConnection()->query($sqlQuery)){
				return true;
			}
			
			return false;
		}

		//this method is used to return a resultSet of a SELECT statement
		public function getResultSetOf($query){
			$resultSet = mysqli_query($this->createConnection(), $query) or die($this->connection->error);
			return $resultSet;
		}

		public function getResultSetAsArray($query){
			$resultSet = mysqli_query($this->createConnection(), $query) or die($this->connection->error);
			if($resultSet->num_rows > 0){
				$index = 0;
				while($row = $resultSet->fetch_assoc()){
					foreach ($row as $key => $value) {
						$arrayAsResult[$index][$key] = $value;
					}
					$index++;
                }
                
				return $arrayAsResult;
            }
            
			return array();
		}
	}
?>