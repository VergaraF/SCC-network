<?php
	class UserController extends DatabaseController{
		public $memoryCache;

		function __construct(){
			$this->memoryCache = MemoryCache::getInstance();
		}

		public function processSignupForm($post){
			$passwordLength = 6;
			$firstName 	 = parent::getEscaped($post['firstname']);
			$lastName 	 = parent::getEscaped($post['lastname']);
			$email 		 = parent::getEscaped($post['email']);
			$username 	 = strtolower(parent::getEscaped($post['username']));
			$password 	 = parent::getEscaped($post['password']);
			$confirmPass = parent::getEscaped($post['cPassword']);
			$profession  = parent::getEscaped($post['profession']);
			$dateOfBirth = parent::getEscaped($post['dob']);
			$roleinSCCId = 3;

			$curentDate = new DateTime();
			$age = date_create($dateOfBirth)->diff($curentDate)->y;

			$res = $this->getSpecificUser($username);
			//this if statement executes when the passwords are identical
			if(strcmp($password, $confirmPass) === 0){
				if(count($res) == 0){
					$salt = $this->generateSalt($password, $username);
					$saltedPassword = $this->hashPassword($password, $salt);
					$newUser = "INSERT INTO User (username, password, salt, firstname, lastname, email, 
												 age, profession, dateOfBirth, roleInSCC_id) VALUES 
												 ('$username', '$saltedPassword', '$salt', '$firstName', '$lastName', '$email', 
												 '$age', '$profession', '$dateOfBirth', '$roleinSCCId')";
					if (!$this->insertUser($newUser)){
						Helper::setSessionVariable("MESSAGE", "There was an error creating your user. Try again at a later time.");
					}
					parent::closeConnection();
					Helper::redirectToLocation("signup.php?redirect=login.php");
					return;
				}
				else{
					Helper::setSessionVariable("MESSAGE", "The username is already taken! Please take another one");
				}

			}
			else{
				Helper::setSessionVariable("MESSAGE", "The password confirmation failed! Please try again");
			}

			Helper::redirectToLocation("signup.php");
		}

		public function processLoginForm($post){
			$username = strtolower(parent::getEscaped($post['username']));
			$password = parent::getEscaped($post['password']);			

			$getInfo = parent::getResultSetAsArray("SELECT * FROM User WHERE username = '$username'");
			if(count($getInfo) > 0){
				for($row = 0; $row < count($getInfo); $row++){
					$db_userid   = $getInfo[$row]['userId'];
					$db_username = $getInfo[$row]['username'];
					$db_password = $getInfo[$row]['password'];
					$db_salt 	 = $getInfo[$row]['salt'];
					
					if (!strcmp($db_username, $username) === 0){
						//if for some reason we got a username that is not the same as the one submited, we skip it
						continue;
					}

					//if the salt is 'test', don't hash
					if ((strcmp($db_salt, "test") === 0 && strcmp($db_password, $password) === 0) 
					   || (strcmp($db_password, $this->hashPassword($password, $db_salt)) === 0)){
						   $this->memoryCache->setUserInCache($username, $db_userId);

						   Helper::setSessionVariable("USERNAME", $username);
						   Helper::setSessionVariable("IDENTIFIER", $db_userId);

						   Helper::redirectToLocation("login.php?redirect=index.php");
						
						return;
					}

					Helper::setSessionVariable("MESSAGE" ,"The username or password is wrong! Please try again");
				}

			}
			else{
				Helper::setSessionVariable("MESSAGE", "The username does not exist in the database! Do you really have an account?");
			}
		}

		public function getUserId($username){
			$u_id = $this->memoryCache->getUserIdFromCache($username);
			if ($u_id != Helper::USER_NOT_IN_APP_CACHE){
				return $u_id;
			}

			$u_id = $this->getUserIdFromDatabase($username);

			if ($u_id != Helper::USER_NOT_IN_APP_CACHE){
				$this->memoryCache->setUserInCache($username, $u_id);
				return $u_id;
			}

			return Helper::USER_NOT_IN_APP_CACHE;
		}

		private function getUserIdFromCache($username){
			return $this->memoryCache->getUserIdByUsername($username);
		}

		private function getUserIdFromDatabase($username){
			$selectUserId = "SELECT userId FROM User WHERE username = '" . $username . "'";
			$rs = mysqli_query(parent::createConnection(), $selectUserId);
			if ($rs->num_rows > 0) {
				while($row = $rs->fetch_assoc()) {
					$user_id = $row['userId'];
				}

				return $user_id;
			}

			return Helper::USER_NOT_IN_APP_CACHE;
		}

		public function getUserRoleInSystem($username){
			$roleArray = $this->getSpecificUser($username);
			$roleId = "";
			for ($row=0; $row < count($roleArray); $row++) { 
				$roleId = $roleArray[$row]['roleInSCC_id'];
			}

			return $roleId;
		}

		public function getSpecificUser($username){
			return parent::getResultSetAsArray("SELECT * FROM User WHERE username = '$username'");
		}

		public static function isLoggedIn(){
			return (isset($_SESSION["USERNAME"]) && $_SESSION["USERNAME"] != null);
		}

		public function isUserAnEventManager($userId){
			$query = "SELECT * FROM event_manager AS e_m 
					  INNER JOIN Manager AS ma ON e_m.manager_id = ma.managerId
					  WHERE ma.user_id = '$userId'";

			$array = parent::getResultSetAsArray($query);
			if (count($array) > 0){
				return true;
			}

			return false;
		}

		public function insertUser($query){
			if (parent::executeSqlQuery($query)) {
				Helper::setSessionVariable("MESSAGE", "Your account has been created! You may login now");
				return true;
			}

			return false;
		}

		public function getAllUsers(){
			return parent::getResultSetAsArray("SELECT * FROM User");
		}

		public function getUsersProfileInfo(){
			return parent::getResultSetAsArray("SELECT * FROM User WHERE username = '" . $_SESSION['USERNAME'] . "'");
		}

		public function updateProfile($post){
			$user_id 	 = $this->getUserId();
			$first_name  = parent::getEscaped($post['firstname']);
			$last_name   = parent::getEscaped($post['lastname']);
			$email 		 = parent::getEscaped($post['email']);
			$age 		 = parent::getEscaped($post['age']);
			$profession  = parent::getEscaped($post['profession']);
			$dateOfBirth = parent::getEscaped($post['dob']);
 
			$update = "UPDATE User SET firstname = '$first_name', lastname = '$last_name',
									   email = '$email', age = '$age', profession = '$profession',
									   dateOfBirth = '$dateOfBirth'
									WHERE userId = '$user_id'";

			parent::executeSqlQuery($update);
		}

		public function logout(){
			unset($_SESSION['USERNAME']);
			unset($_SESSION['ROLE']);
			unset($_SESSION['IDENTIFIER']);
			header("location: index.php");
			session_start();
			session_unset();
			session_destroy();
		}

		public function hashPassword($password, $salt){
			return crypt($password, $salt);
		}

		public function generateSalt($password, $username){
			return hash('sha256', uniqid(mt_rand(), true) . $password . strtolower($username));
		}

		public function resetPassword($old, $new, $confirmNew){
			if(strcmp($new, $confirmNew) === 0){
				$query = "SELECT password, salt FROM user WHERE username = '" . $_SESSION['USERNAME'] . "'";
				$arrayValues = parent::getResultSetAsArray($query);
				for ($row = 0; $row < count($arrayValues); $row++) {
					$db_password = $arrayValues[$row]['password'];
					$db_salt = $arrayValues[$row]['salt'];
				}

				$hashedOld = $this->hashPassword($old, $db_salt);
				if(strcmp($hashedOld, $db_password) === 0){
					$newSalt = $this->generateSalt($new, $_SESSION['USERNAME']);
					$newHashedPass = $this->hashPassword($new, $newSalt);
					$updatePass = "UPDATE user SET password = '$newHashedPass', salt = '$newSalt' WHERE username = '" . $_SESSION['USERNAME'] . "'";
					parent::executeSqlQuery($updatePass);
					echo "Password changed successfully";
				}

				else{
					echo "Your old password is incorrect! Please retype it";
				}

			}
			else{
				echo "Confirmation failed";
			}
		}
	}
?>