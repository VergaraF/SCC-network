<?php
    class Helper{

        public const ADMIN_USER_ROLE_ID = 1;
        public const CONTROLLER_USER_ROLE_ID = 2;
        public const REGULAR_USER_ROLE_ID = 3;

		public static function setSessionVariable($sessionVar, $message){
			$_SESSION[$sessionVar] = $message;
		    session_write_close();
        }
        
        public static function redirectToLocation($location){
            header("Location: $location");
            exit();
        }
    }
?>