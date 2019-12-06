<?php
session_start();
include('./Predefined/init.php');

$userController = new UserController();
$contentController = new ContentController();
$adminManagerController = new AdminManagementController();

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
$mailController = new MailController();
?>
<div class="col-10 no-padding">
    <?php
    if (isset($_SESSION['MESSAGE'])) {
        echo $_SESSION['MESSAGE'];
        unset($_SESSION['MESSAGE']);
    }
    ?>
    <div class="row main-content">
        <form name="createForm" method="POST" action="createConvo.php">
            <input id="sort" type="submit" name="create" class="create-convo-btn" value="Create Conversation">
        </form>
        <div id="convo">
            <p><b>Your conversations with... </b></p>
            <table>
                <?php
                $user_id = $_SESSION['IDENTIFIER'];
                $convoArray = $mailController->displayConversations($user_id);
                for ($row = 0; $row < count($convoArray); $row++) {
                    $userIdsArray = $mailController->getUserIdsForConvo($convoArray[$row]['conversationId']);
                    $otherUsername = $mailController->getUsernameForConvo($userIdsArray, $user_id);
                    ?>
                    <form name='conversationDelete' method='POST' action=''>
                        <tr>
                            <td> <?php echo "<p id='uName'> <b>></b> " . $otherUsername . "<p>" ?> </td>
                            <input type="hidden" name="c_id" value=" <?php echo $convoArray[$row]['conversationId']; ?>">
                            <td><input id='showM' type='submit' name='show' value='Show messages'></td>
                            <td><input id='del' type='submit' name='deleteConvo' value='delete'></td>
                        </tr>
                    </form>
                <?php  } ?>
            </table>
        </div>
        <p><b>Messages :</b></p>
        <div id="chat-box">
            <?php
            if (isset($_POST['show'])) {
                $messages = $mailController->displayMessages($_POST['c_id']);
                ?>
                <table>
                    <?php
                        for ($row = 0; $row < count($messages); $row++) {
                            ?>
                        <form name='deleteMessage' method='POST' action=''>
                            <tr>
                                <td> <?php echo "<div class='message-box message-box-in-chat chat-box-message'><b>" . $userController->getUsername($messages[$row]['sender_user_id']) . "</b> : " . $messages[$row]['content']; ?> </div>
        </td>
        <input type="hidden" name="cr_id_toDeleteMessage" value="<?php echo $messages[$row]['messageId'] ?>">
        <td><input id='del' type='submit' name='deleteMess' value='X'></td>
        </tr>
        </form>
    <?php } ?>
    </table>
    <form name='sendMessage' method='POST' action=''>
        <div id="sendMess">
            <input type="hidden" name="c_id_toSendMessage" value="<?php echo $_POST['c_id']; ?>">
            <textarea id="messageInput" class="input-mail" name="message"></textarea>
            <input name="sendMessage" class="send-btn" type="submit" value="Send">
        </div>
    </form>
<?php
} elseif (isset($_POST['sendMessage'])) {
    $mailController->sendMessage($_POST['c_id_toSendMessage'], $_POST['message'], $user_id);
} elseif (isset($_POST['deleteMess'])) {
    $mailController->deleteMessage($_POST['cr_id_toDeleteMessage']);
} elseif (isset($_POST['deleteConvo'])) {
    $mailController->deleteConversation($_POST['c_id']);
    header("location: messenger.php");
}
?>
    </div>
</div>
</div>

</body>

</html>