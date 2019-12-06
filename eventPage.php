<?php
session_start();
include('./Predefined/init.php');

$userController = new UserController();
$contentController = new ContentController();
$adminManagerController = new AdminManagementController();
$userRole = $userController->getUserRoleInSystem($_SESSION['USERNAME']);

if (!$userController->isLoggedIn() || $userController->isUserDeactivated($_SESSION['IDENTIFIER'])) {
    Helper::redirectToLocation("index.php");
}
?>
<html>

<head>
    <title>The SCC-Network - Event main page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./Styles/main.css">
    <link rel="stylesheet" type="text/css" href="./Styles/joiningSite.css">
    <link rel="stylesheet" type="text/css" href="./Styles/event.css">
    <link rel="stylesheet" type="text/css" href="./Styles/chat.css">

    <script type="text/javascript" src="Scripts/inputBasedStyles.js"></script>
</head>
<?php
include('./Predefined/header.php');
include('./Predefined/sideMenu.php');
$mailController = new MailController();

if (isset($_POST['ev_in_id'])) {
    $event_instance_id = $_POST['ev_in_id'];
}
if (isset($_POST['ev_id'])) {
    $event_id = $_POST['ev_id'];
}
if (isset($_POST['ev_st'])) {
    $event_status_id = $_POST['ev_st'];
}
if (isset($_POST['ev_ma_u_id'])) {
    $event_manager_id = $_POST['ev_ma_u_id'];
}
?>

<div class="col-10 no-padding">
    <div class="row main-content">
        <?php
        if (strcmp($event_manager_id, $_SESSION['IDENTIFIER'] === 0) || strcmp($userRole, Helper::ADMIN_USER_ROLE_ID) === 0) {
?>
 <div class="col-3">
            <form name="inviteUser" method="POST" action="eventAction.php">
                <input type="hidden" name="ev_in_id" value="<?php echo $event_instance_id; ?>">
                <input type="hidden" name="ev_ma_u_id" value="<?php echo $event_manager_id; ?>">
                <input type="hidden" name="ev_id" value="<?php echo $event_id; ?>">
                <input id="invite" type="submit" name="invite" class="invite-btn" value="Invite user">
            </form>
        </div>
        <div class="col-3">
            <form name="payBill" method="POST" action="eventAction.php">
            <input type="hidden" name="ev_in_id" value="<?php echo $event_instance_id; ?>">
            <input type="hidden" name="ev_ma_u_id" value="<?php echo $event_manager_id; ?>">
            <input type="hidden" name="ev_st'" value="<?php echo $event_status_id; ?>">
            <input type="hidden" name="ev_id" value="<?php echo $event_id; ?>">

                <input id="payBill" type="submit" name="payBill" class="pay-bill-btn" value="Pay bill">
            </form>
        </div>
<?php
        }
        ?>
       
        <div class="col-3">
            <form name="seeGroups" method="POST" action="eventAction.php">
            <input type="hidden" name="ev_in_id" value="<?php echo $event_instance_id; ?>">
            <input type="hidden" name="ev_id" value="<?php echo $event_id; ?>">
                <input id="seeGroups" type="submit" name="seeGroups" class="see-group-btn" value="See groups">
            </form>
        </div>

        <div class="col-3">
            <form name="createGroup" method="POST" action="eventAction.php">
            <input type="hidden" name="ev_in_id" value="<?php echo $event_instance_id; ?>">
            <input type="hidden" name="ev_id" value="<?php echo $event_id; ?>">
                <input id="createGroup" type="submit" name="createGroup" class="create-group-btn" value="Create a group">
            </form>
        </div>

        <div class="col-3">
            <form name="seeParticipants" method="POST" action="eventAction.php">
            <input type="hidden" name="ev_in_id" value="<?php echo $event_instance_id; ?>">
            <input type="hidden" name="ev_id" value="<?php echo $event_id; ?>">
                <input id="seeParticipants" type="submit" name="seeParticipants" class="see-participants-btn" value="See participants">
            </form>
        </div>

        <div class="col-3">
            <form name="enterChat" method="POST" action="chat.php">
                <input id="enterChat" type="submit" name="enterChat" class="see-participants-btn" value="Enter chat">
            </form>
        </div>

        <div class="col-12">
        <p><b>Event content :</b></p>
        <div id="chat-box">
        <?php
        $eventContent = ContentController::getInstance()->getContentForEventInstance($event_instance_id);
        if (count($eventContent) === 0) {
            echo "There is no content to display as of now...";
        }
        foreach($eventContent as $content){
            $author = $userController->getUserIdAndNameFromParticipantId($content['event_instance_content_author_participant_id'])[0]['username'];
            echo "<div class='message-box message-box-in-chat chat-box-message'><b>" . $author. "</b> : " . $content['value'] . "<p id='small'>". $content['postedAt']. "</div>"; 
        }
        ?>

        <form name='addContent' method='POST' action=''>
            <div id="sendMess">
                <input type="hidden" name="c_id_toSendMessage" value="<?php echo $_POST['c_id']; ?>">
                <textarea id="messageInput" class="input-mail" name="message">Type your message here...</textarea>
                <input name="addContent" class="send-btn" type="submit" value="Send">
            </div>
        </form>
    </div>
        <?php

        if (isset($_POST['sendMessage'])) {
            $mailController->sendMessage($_POST['c_id_toSendMessage'], $_POST['message'], $user_id);
        } 
        ?>
    </div>
</div>
</div>

</body>

</html>