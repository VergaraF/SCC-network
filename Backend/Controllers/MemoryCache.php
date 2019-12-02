<?php
    class MemoryCache{
        private $mapWithUsernameAsKey;
        private static $instance = null;

        function __construct(){
            $this->mapWithUsernameAsKey = new map();
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
            return $mapWithUsernameAsKey->get($username, Helper::USER_NOT_IN_APP_CACHE);
        }

        public function setUserInCache($username, $uid){
            $mapWithUsernameAsKeyp->put($username, $uid);
        }
    }
?>