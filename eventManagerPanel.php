<?php
session_start();
include('./Predefined/init.php');

$userController = new UserController();

if (!$userController->isLoggedIn()){
    Helper::redirectToLocation("index.php");
}
?>
<html>
<head>
  <title>The SCC-Network - My Events</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="./Styles/main.css">
  <link rel="stylesheet" type="text/css" href="./Styles/joiningSite.css">
  <link rel="stylesheet" type="text/css" href="./Styles/event.css">
</head>
<?php 
  include('./Predefined/header.php'); 
  include('./Predefined/sideMenu.php'); 

?>
<body>
<div class="col-8 no-padding">
<?php
LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User ". $_SESSION['USERNAME'] . "viewing his 'Event Manager Panel' page.");

$events = ContentController::getInstance()->getEventsWhereUserIsManager($_SESSION['IDENTIFIER']);
    if (count($events) > 0){
        ?> 
        <div class="row main-content">
        <h3>Managing...</h3>
        <?php
        foreach($events as $event){
            $statusOfEvent = ContentController::getInstance()->getEventInstanceStatus($event["eventStatus_id"]);
            $eventInfo = ContentController::getInstance()->getEventInfo($event["event_id"]);
            $eventName = $eventInfo[0]['event_name'];
            $eventType = $eventInfo[0]['name'];

            if (strcmp($event['eventStatus_id'], Helper::ACTIVE_EVENT_ID) === 0){
                echo "<div class='col-12 active-event event-box no-padding'>"; 
            }
            else if (strcmp($event['eventStatus_id'], Helper::ARCHIVED_EVENT_ID) === 0){
                echo "<div class='col-12 archived-event event-box no-padding'>";
            }
            else{
                echo "<div class='col-12 undefined-event event-box no-padding'>";
            }
            echo "<div class='row no-padding'>";
            echo "<div class='col-9'>" . $eventName . "</div>";
            echo "<div class='col-3'>" . "Status : " . $statusOfEvent[0]['name']  . "</div>";
            echo "<div class='col-8'> Expires on " . $event['lifetime']   . "</div>";
            echo "<div class='col-4'>" . "Event type : " . $eventType . "</div>"; 
            echo "</div>
                </div>";
        }
        ?>
        </div>
</div>
        <?php
    }
    ?>
</body>
</html>
