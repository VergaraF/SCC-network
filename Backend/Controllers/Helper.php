<?php
    class Helper{

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