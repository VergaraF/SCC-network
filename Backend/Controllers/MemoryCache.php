<?php
    class MemoryCache{
        private $mapWithUsernameAsKey;
        private static $instance = null;

        function __construct(){
            $this->mapWithUsernameAsKey = Array();
        }

        public static function getInstance()
        {
            if (self::$instance == null)
            {
                self::$instance = new MemoryCache();
            }

            return self::$instance;
        }

        public function getUserIdByUsername($username){
            $result =  $mapWithUsernameAsKey[$username];
            if ($result == null){
                return Helper::USER_NOT_IN_APP_CACHE;
            }

            return $result;
        }

        public function setUserInCache($username, $uid){
            $mapWithUsernameAsKeyp[$username] = $uid;
        }
    }
?>