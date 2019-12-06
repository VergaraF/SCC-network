<?php
session_start();
include('./Predefined/init.php');

$userController = new UserController();
$contentController = new ContentController();
$adminManagerController = new AdminManagementController();
$mailController = new MailController();

if (!$userController->isLoggedIn()) {
    Helper::redirectToLocation("index.php");
}
?>
<html>
<head>
    <title>The SCC-Network - Mailbox</title>
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
?>
<div class="col-10 no-padding">
    <div class="row main-content">
        <div class="col-10" id="chat-box">
        <form name="sending" method="POST" action=""><br>
            <?php
            if (isset($_SESSION['MESSAGE'])) {
                echo $_SESSION['MESSAGE'];
                unset($_SESSION['MESSAGE']);
            }
            ?>
            <div class="col-9"><label>Send message to the owner: </label></div>
            <div class="col-3">
            <select name="username">
                <option>Select Username</option>
                <?php
                $userArray = $userController->getAllUsers();
                for ($row = 0; $row < count($userArray); $row++) {
                    echo "<option>" . $userArray[$row]['username'] . "</option>";
                }
                ?>
                </select>
                </div>

                <textarea id="messageInput" class="input-mail"name="message" required></textarea>
                <input name="sendMessage" type="submit" class="send-btn" value="Send">
        </form>
            </div>
        <?php
        //the following if statement is execute when the user wants to send the message to the selected user
        if (isset($_POST['sendMessage'])) {
            if (strcmp($_POST['username'], "Select Username") === 0) {
                echo "Please select a username from the list";
            } else {
                $rs = $userController->getSpecificUser($_POST['username']);
                if (count($rs) === 1) {
                    $user_id = $_SESSION['IDENTIFIER'];  //the user who is sending the message
                    $user_two = $rs[0]['userId'];         //the user to who the message will be sent
                    $mailController->createConversation($user_id, $user_two); //using this to get the conversation id for these two users' conversation
                    $convo_id = $mailController->checkConversation($user_id, $user_two);
                    if (count($convo_id) === 1) {
                        $mailController->sendMessage($convo_id[0]['conversationId'], $_POST['message'], $user_id);
                        Helper::setSessionVariable("MESSAGE", "Your message has been sent successfully");
                    }
                } else {
                    echo "the specified user does not exit in the database";
                }
            }
        }
        ?>
    </div>
</div>