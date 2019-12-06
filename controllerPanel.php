<?php
session_start();
include('./Predefined/init.php');

$userController = new UserController();
$adminManagerController = new AdminManagementController();
$contentController = new ContentController();

if (!$userController->isLoggedIn()) {
    Helper::redirectToLocation("index.php");
} else {
    LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User " . $_SESSION['USERNAME'] . " opened the controller panel");
    $userRole = $userController->getUserRoleInSystem($_SESSION['USERNAME']);
    if (strcmp($userRole, Helper::ADMIN_USER_ROLE_ID) !== 0 && strcmp($userRole, Helper::CONTROLLER_USER_ROLE_ID) !== 0) {
        Helper::redirectToLocation("index.php");
    }
}
?>
<html>

<head>
    <title>The SCC-Network - Controller panel</title>
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
        <div class="row main-content">
            <h2>Controller actions:</h2>
            <div class="col-2 no-padding">
                <div class="row no-padding">
                    <div class="col-12 no-padding">
                        <button class="action-btn" id="manageDefaultFees" onClick="window.location.href='controllerPanel.php?action=manageDefaultFees'">Manage default fees</button>
                    </div>
                    <div class="col-12 no-padding">
                        <button class="action-btn" id="manageEventsFees" onClick="window.location.href='controllerPanel.php?action=manageEventsFees'">Manage events fees</button>
                    </div>
                    <div class="col-12 no-padding">
                        <button class="action-btn" id="reporting" onClick="window.location.href='controllerPanel.php?action=reportingController'">Reporting and statistics tools</button>
                    </div>
                </div>
            </div>
            <div class="col-10 no-upper-padding">
                <div class="row">
                    <?php
                    if (isset($_GET['action'])) {
                        $actionToPerform = $_GET['action'];
                        echo "<script type='text/javascript'> removeButtonFromClassOnClicked('$actionToPerform'); </script>";
                        switch ($actionToPerform) {
                            case 'manageDefaultFees': {
                                    LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User " . $_SESSION['USERNAME'] . " is managing feed in the controller panel");
                                    break;
                                }
                            case 'manageEventsFees': {
                                    LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User " . $_SESSION['USERNAME'] . " is managing feed in the controller panel");
                                    include('./Backend/Managers/eventsManagement.php');
                                break;
                                }
                            case 'reporting': {
                                    LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User " . $_SESSION['USERNAME'] . " is viewing the reporting and statistics tools in the controller panel");

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
</body>

</html>