<?php
	class DatabaseController {

        public $connection;
        
		public function createConnection(){
			$servername = "localhost";
			$username = "root";
			$password = "rootpassword";
			$db = "SCCNetwork";
			// Create connection
			$this->connection = new mysqli($servername, $username, $password, $db);
			// Check connection
			if ($this->connection->connect_error) {
			    die("Connection failed: " . $this->connection->connect_error);
			}
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
			if($this->createConnection()->query($sqlQuery) === TRUE){
				echo "SUCCESS-";
			}else{
				echo "Error: " . $this->connection->error;
			}
		}

		//this method is used to return a resultSet of a SELECT statement
		public function getResultSetOf($query){
			$rs = mysqli_query($this->createConnection(), $query) or die($this->createConnection()->error);
			return $rs;
		}

		public function getResultSetAsArray($query){
			$rs = mysqli_query($this->createConnection(), $query) or die($this->connection->error);
			if($rs->num_rows > 0){
				$index = 0;
				while($row = $rs->fetch_assoc()){
					foreach ($row as $key => $value) {
						$arrayAsResult[$index][$key] = $value;
					}
					$index++;
				}
				return $arrayAsResult;
			}
			return null;
		}
	}
?>