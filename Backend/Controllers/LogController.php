<?php
class LogController{
    private $db_controller;
    private static $instance = null;

    public function __construct() {
        $this->db_controller = new DatabaseController();
    }
    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new LogController();
        }

        return self::$instance;
    }

   public function LogAction($userId, $action){
       $this->db_controller->executeSqlQuery("INSERT INTO UserActionLog (user_id, actionPerformed) VALUES ('$userId', '$action')");
   }
}
?>