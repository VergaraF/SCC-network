<?php
session_start();
include('./Predefined/init.php');

$userController = new UserController();
$adminManagerController = new AdminManagementController();

if (!$userController->isLoggedIn()){
    Helper::redirectToLocation("index.php");
}else{
    LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User ". $_SESSION['USERNAME'] . " opened the admin panel");
    $userRole = $userController->getUserRoleInSystem($_SESSION['USERNAME']);
    if(strcmp($userRole, Helper::ADMIN_USER_ROLE_ID) !== 0){
        Helper::redirectToLocation("index.php");
    }
}
?>
<html>
<head>
  <title>The SCC-Network - Admin panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="./Styles/main.css">
  <link rel="stylesheet" type="text/css" href="./Styles/joiningSite.css">
  <link rel="stylesheet" type="text/css" href="./Styles/event.css">
  <script type="text/javascript" src="Scripts/inputBasedStyles.js"></script>
</head>
<?php 
  include('./Predefined/header.php'); 
  include('./Predefined/sideMenu.php'); 

?>
<body>
<div class="col-10 no-padding">
<?php
$events = ContentController::getInstance()->getEventsWhereUserIsParticipant($_SESSION['IDENTIFIER']);
    if (count($events) > 0){
        ?> 
        <div class="row main-content">
            <h2>Actions:</h2>
            <div class="col-2 no-padding">
                <div class="row no-padding">
                    <div class="col-12 no-padding">
                        <button class="action-btn" id="manageUsers" onClick="window.location.href='adminPanel.php?action=manageUsers'">Manage users</button>
                    </div> 
                    <div class="col-12 no-padding">
                        <button class="action-btn" id="manageEvents" onClick="window.location.href='adminPanel.php?action=manageEvents'">Manage events</button>
                    </div> 
                    <div class="col-12 no-padding">
                        <button class="action-btn" id="manageEventsManagers" onClick="window.location.href='adminPanel.php?action=manageEventsManagers'">Manage events managers</button>
                    </div> 
                    <div class="col-12 no-padding">
                        <button class="action-btn" id="manageGroups" onClick="window.location.href='adminPanel.php?action=manageGroups'">Manage groups</button>
                    </div> 
                    <div class="col-12 no-padding">
                        <button class="action-btn" id="manageContent" onClick="window.location.href='adminPanel.php?action=manageContent'">Manage content</button>
                    </div> 
                    <div class="col-12 no-padding">
                        <button class="action-btn" id="validateUsers" onClick="window.location.href='adminPanel.php?action=validateUsers'">Validare users</button>
                    </div> 
                    <div class="col-12 no-padding">
                        <button class="action-btn" id="validateEvents" onClick="window.location.href='adminPanel.php?action=validateEvents'">Validate events requests</button>
                    </div> 
                    <div class="col-12 no-padding">
                        <button class="action-btn" id="reporting" onClick="window.location.href='adminPanel.php?action=reporting'">Reporting and statistics tools</button>
                    </div> 

                </div>
            
            </div>
            <div class="col-10 no-upper-padding">
                <div class="row">
<?php 
                if (isset($_GET['action'])){
                    $actionToPerform = $_GET['action'];
                    echo "<script type='text/javascript'> removeButtonFromClassOnClicked('$actionToPerform'); </script>";

                    switch ($actionToPerform) {
                        case 'manageUsers':{
                            LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User ". $_SESSION['USERNAME'] . " is managing users in the admin panel");
                            include('./Backend/Managers/userManagement.php');
                        break;
                        }
                        case 'manageEvents' : {
                            LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User ". $_SESSION['USERNAME'] . " is managing events in the admin panel");

                        break;
                        }
                        case 'manageEventsManagers' : {
                            LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User ". $_SESSION['USERNAME'] . " is managing events managers in the admin panel");

                        break;
                        }
                        case 'manageGroups' : {
                            LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User ". $_SESSION['USERNAME'] . " is managing groups in the admin panel");

                        break;
                        }
                        case 'manageContent' : {
                            LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User ". $_SESSION['USERNAME'] . " is managing managing content in the admin panel");

                        break;
                        }
                        case 'validateUsers' : {
                            LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User ". $_SESSION['USERNAME'] . " is validating users in the admin panel");

                        break;
                        }
                        case 'validateEvents' : {
                            LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User ". $_SESSION['USERNAME'] . " is validating events in the admin panel");

                        break;
                        }
                        case 'reporting' : {
                            LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User ". $_SESSION['USERNAME'] . " is viewing the reporting and statistics tools in the admin panel");

                        break;
                        }

                            ?> 
                          
                        <?php 
                        
                    }
                }

?>
            
                    

                    </div>

                </div>
            </div>
        </div>
</div>
        <?php
    }
    ?>
</body>
</html>
