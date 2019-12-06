<?php
require_once('./predefined/init.php');
$userController = new UserController();
?>
<div class="col-8 no-padding">
    <?php
    if (!$userController->isLoggedIn()) {
        ?>
        <div class="main-content">
            <h1>Share, Contribute, Comment... Connect. The Network.</h1>
            <p><b>Sign up now</b> to be part of the <b>overgrowing community</b> of participants in a whole variety of events. </br> </br>
                <b>Become</b> a member of the family, <b>choose</b> whether to participate in an event or organize a new inedit one! <b>Join</b> existing groups in their respective events
                composed by other participants or <b>be</b> the sole all mighty by creating your own! </br>
                <b>Comment</b>, <b>contribute</b> and <b>share</b> content, and chat with other people alike sharing your same own interests and assisting your same own events! </br></br></br>

                <b><i>Because great minds think alike.</i></b>
            </p>
        </div>
</div>
<?php
} else {
    LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User " . $_SESSION['USERNAME'] . " is in the index, checking his newsfeed");
    if (!$userController->isUserDeactivated($_SESSION['IDENTIFIER'])) {
        $events = ContentController::getInstance()->getEventsWhereUserIsParticipant($_SESSION['IDENTIFIER']);
        if (count($events) > 0) {
            $contents = ContentController::getInstance()->getNewContentForUser($_SESSION['IDENTIFIER']);
            ?>
        <div class="row main-content">
            <h3>Newsfeed : </h3>
        <?php
                    if (count($contents) > 0) {
                        foreach ($contents as $content) {
                            echo "<div class='col-12 event-content-box'>";
                            echo "<div class='row'>";
                            echo "<div class='col-9 no-padding'>" . "Event name " . $content["event_instance_id"] .  "</div>";
                            echo "<div class='col-3 no-padding'>" . "Author : " . $content["event_instance_content_author_participant_id"] .  "</div>";
                            if (strcmp($content["contentType"], "Comment") === 0) {
                                echo "<div class='col-12 no-padding'><p>" . $content["value"] .  "</p></div>";
                            } else {
                                echo "<div class='col-2 no-padding'> This is a video/picture. Click on the following link to display it :  </div>";
                                echo "<div class='col-10 no-padding'> : " .  $content["value"] . " </div>";
                            }
                            echo "<div class='col-5 no-padding'> Published at " . $content["postedAt"] . "</div>";
                            echo "</div>
                     </div>";
                        }
                    } else {
                        echo "<div class='col-12 event-content-box'>";
                        echo "<div class='row'>";
                        echo "<div class='col-12'>" . "There is nothing new! You have already seen all content available since your last connection..." .  "</div>";
                    }
                }
            } else {
                ?>
        <div class="main-content">
            <h1>Your account has been deactivated.</h1>
            <p>We are sorry to see you go but it seems that you have lost access to your account as it has been deactivated. </br> </br></br>
                If you want to know the reason as of why your account was deactivated, one of our administrator left this message :
            </p></br></br>

            <?php
                    $deactivatedUser = $userController->getDeactivatedUser($_SESSION['IDENTIFIER']);
                    echo "<p><b>'" . $deactivatedUser[0]["description"] . "'</b></p>";
                    ?>
        </div>
    <?php
        }
        ?>
        </div>
        </div>
    <?php
    }
    ?>