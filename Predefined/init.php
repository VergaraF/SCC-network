<?php
session_start();
require_once('./Backend/Controllers/DatabaseController.php');
require_once('./Backend/Controllers/UserController.php');
require_once('./Backend/Controllers/MemoryCache.php');
require_once('./Backend/Controllers/Helper.php');

$dbController = new DatabaseController();
?>