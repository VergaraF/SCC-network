<?php
    class Helper{

        public const ADMIN_USER_ROLE_ID = 1;
        public const CONTROLLER_USER_ROLE_ID = 2;
        public const REGULAR_USER_ROLE_ID = 3;
        public const USER_NOT_IN_APP_CACHE = -1;

		public static function setSessionVariable($sessionVar, $message){
			$_SESSION[$sessionVar] = $message;
        }
        
        public static function redirectToLocation($location){
            header("Location: $location");
            exit();
        }

        public static function displayMessage(){
            if(isset($_SESSION['MESSAGE'])) {
				echo "<p>" . $_SESSION['MESSAGE'] . "</p>";
				unset($_SESSION['MESSAGE']);
			}
        }
    }
?>