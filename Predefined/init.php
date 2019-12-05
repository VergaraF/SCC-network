<?php
require_once('./Backend/Controllers/LogController.php');
require_once('./Backend/Controllers/DatabaseController.php');
require_once('./Backend/Controllers/UserController.php');
require_once('./Backend/Controllers/ContentController.php');
require_once('./Backend/Controllers/MemoryCache.php');
require_once('./Backend/Controllers/Helper.php');
require_once('./Backend/Controllers/AdminManagementController.php');

$dbController = new DatabaseController();
?>