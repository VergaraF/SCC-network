<?php
	class UserController extends DatabaseController{

		public function processSignupForm($post){
			$passwordLength = 8;
			$firstName 	 = parent::getEscaped($post['firstName']);
			$lastName 	 = parent::getEscaped($post['lastName']);
			$email 		 = parent::getEscaped($post['email']);
			$username 	 = parent::getEscaped($post['username']);
			$password 	 = parent::getEscaped($post['password']);
			$confirmPass = parent::getEscaped($post['cPassword']);
			$age 		 = parent::getEscaped($post['age']);
			$profession  = parent::getEscaped($post['profession']);
			$dateOfBirth = parent::getEscaped($post['dob']);
			$roleinSCCId = 3;

			$res = $this->getSpecificUser($username);
			//this if statement executes when the passwords are identical
			if(strcmp($password, $confirmPass) === 0){
				if(count($res) == 0){
					$salt = $this->generateSalt($password, $username);
					$saltedPassword = $this->hashPassword($password, $salt);
					$newUser = "INSERT INTO User VALUES ('$username', '$saltedPassword', 
										'$salt', '$firstName', '$lastName', '$email', '$age', '$profession', '$dateOfBirth', '$roleinSCCId')";
					$this->insertUser($newUser);
					parent::closeConnection();
					return;
				}
				else{
					Helper::setSessionVariable("MESSAGE", "The username is already taken! please take another one");
				}

			}
			else{
				Helper::setSessionVariable("MESSAGE", "The password confirmation failed! Please try again");
			}

			Helper::redirectToLocation("signUp.php");
		}

		public function processLoginForm($post){
			$username = parent::getEscaped($post['username']);
			$password = parent::getEscaped($post['password']);

			$getInfo = parent::getResultSetAsArray("SELECT * FROM User WHERE username = '$username'");
			if(count($getInfo) > 0){
				for($row = 0; $row < count($getInfo); $row++){
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
						Helper::setSessionVariable("USERNAME", $username);
						Helper::setSessionVariable("ROLE", $this->getRoleInSystem($db_username));
						Helper::redirectToLocation("index.php");
						return;
					}

					Helper::setSessionVariable("MESSAGE" ,"The username or password is wrong! Please try again");
				}

			}else{
				Helper::setSessionVariable("MESSAGE", "The username does not exist in the database! Do you really have an account?");
			}

			Helper::redirectToLocation("login.php");
		}

		public function getRoleInSystem($username){
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

		public function insertUser($query){
			if (parent::createConnection()->query($query) === TRUE) {
				Helper::setSessionVariable("MESSAGE", "Your account has been created! You may login now");
				Helper::redirectToLocation("login.php");
			}
		}

		public function getAllUsers(){
			return parent::getResultSetAsArray("SELECT * FROM User");
		}

		public function getUserId(){
			$selectUserId = "SELECT userId FROM User WHERE username = '" . $_SESSION['USERNAME'] . "'";
			$rs = mysqli_query(parent::createConnection(), $selectUserId);
			if ($rs->num_rows > 0) {
				while($row = $rs->fetch_assoc()) {
					$user_id = $row['userId'];
				}

				return $user_id;
			}

			return null;
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
			header("location: index.php");
			session_start();
			session_unset();
			session_destroy();
		}

		public function displayMessage(){
			if(isset($_SESSION['MESSAGE'])) {
				echo "<p>" . $_SESSION['MESSAGE'] . "</p>";
				unset($_SESSION['MESSAGE']);
			}
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
					echo "Your old pass is wrong! please retype it";
				}
			}else{
				echo "Confirmation failed";
			}
		}
	}
?>