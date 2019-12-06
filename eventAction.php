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
if (strcmp($event_manager_id, $_SESSION['IDENTIFIER'] !== 0) && strcmp($userRole, Helper::ADMIN_USER_ROLE_ID) !== 0) {
    Helper::redirectToLocation("index.php");
}
?>
<html>

<head>
    <title>The SCC-Network - Event action</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./Styles/main.css">
    <link rel="stylesheet" type="text/css" href="./Styles/joiningSite.css">
    <link rel="stylesheet" type="text/css" href="./Styles/event.css">
    <link rel="stylesheet" type="text/css" href="./Styles/chat.css">

    <script type="text/javascript" src="Scripts/inputBasedStyles.js"></script>
    <script type="text/javascript">
        $(document).ready(
            function() {
                $("#frmCSVImport").on(
                    "submit",
                    function() {

                        $("#response").attr("class", "");
                        $("#response").html("");
                        var fileType = ".csv";
                        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" +
                            fileType + ")$");
                        if (!regex.test($("#file").val().toLowerCase())) {
                            $("#response").addClass("error");
                            $("#response").addClass("display-block");
                            $("#response").html(
                                "Invalid File. Upload : <b>" + fileType +
                                "</b> Files.");
                            return false;
                        }
                        return true;
                    });
            });
    </script>
</head>
<?php
include('./Predefined/header.php');
include('./Predefined/sideMenu.php');
$mailController = new MailController();


?>

<div class="col-10 no-padding">
    <div class="row main-content">
        <div class="col-4">
            <form name="goBack" method="POST" action="eventPage.php">
                <input type="hidden" name="ev_in_id" value="<?php echo $event_instance_id; ?>">
                <input type="hidden" name="ev_ma_u_id" value="<?php echo $event_manager_id; ?>">
                <input type="hidden" name="ev_st'" value="<?php echo $event_status_id; ?>">
                <input type="hidden" name="ev_id" value="<?php echo $event_id; ?>">
                <input id="goBack" type="submit" name="invite" class="go-back-btn" value="back">
            </form>
        </div>
        <div class="col-4">
            <form name="loadCsv" method="POST" action="">
                <input type="hidden" name="ev_in_id" value="<?php echo $event_instance_id; ?>">
                <input type="hidden" name="ev_ma_u_id" value="<?php echo $event_manager_id; ?>">
                <input type="hidden" name="ev_st'" value="<?php echo $event_status_id; ?>">
                <input type="hidden" name="ev_id" value="<?php echo $event_id; ?>">
                <input id="loadCsv" type="submit" name="loadCsv" class="go-back-btn" value="Load csv file">
            </form>
        </div>

        <div class="col-4">
            <form name="inviteOne" method="POST" action="">
                <input type="hidden" name="ev_in_id" value="<?php echo $event_instance_id; ?>">
                <input type="hidden" name="ev_ma_u_id" value="<?php echo $event_manager_id; ?>">
                <input type="hidden" name="ev_st'" value="<?php echo $event_status_id; ?>">
                <input type="hidden" name="ev_id" value="<?php echo $event_id; ?>">
                <input id="inviteOne" type="inviteOne" name="invite" class="go-back-btn" value="Invite one person">
            </form>
        </div>

        <div class="col-12">
            <?php

            if (isset($_POST('inviteOne'))){
                
            }
            if (isset($_POST['loadCsv'])) {
                ?>
                <form action="" method="post" name="uploadCSV" enctype="multipart/form-data">
                    <div class="input-row">
                        <label class="col-4 control-label">Choose CSV File</label> <input type="file" name="file" id="file" accept=".csv">
                        <button type="submit" id="submit" name="import" class="btn-submit">Load CSV file</button>
                        <br />

                    </div>
                    <div id="labelError"></div>
                </form>
            <?php
            }

            if (isset($_POST["import"])) {

                $fileName = $_FILES["file"]["tmp_name"];
                $count = 0;
                if ($_FILES["file"]["size"] > 0) {

                    $file = fopen($fileName, "r");
                    $readingHeaders = false;

                    while (($buffer = fgets($handle, 4096)) !== false) {
                        if (strpos(fgets($file), '+---') !== false) {
                            $readingHeaders = true;
                            continue;
                        }
                        if ($readingHeaders) {
                            $count = $count + 1;
                            $readingHeaders = false;
                            continue;
                        }
                        $data[] = str_getcsv($buffer, '|');
                        if ($count == 1) {
                            $query = "INSERT User(userId, username, password, firstname, lastname) 
                        VALUES ('" . $data[3] . "','" . $data[1] . $data[0] . "','" . $data[4] . "','" . $data[1] . "','" . $data[0] . "')";
                            $userController->executeSqlQuery($query);
                        } else if ($count == 2) {
                            $query = "INSERT INTO Manager(managerId, user_id ) VALUES ( '$data[4]', '$data[4]')";
                            $userController->executeSqlQuery($query);

                            $query2 = "INSERT INTO Event(eventId, event_name) VALUES ( '$data[1]', '$data[0]')";
                            $userController->executeSqlQuery($query2);

                            $query3 = "INSERT INTO event_instance(event_instanceId, event_id, lifetime) VALUES ('$data[1]', '$data[1]', '$data[3]')";
                            $userController->executeSqlQuery($query3);

                            $query4 = "INSERT INTO event_manager(event_id, manager_id, event_instance_id) VALUES ('$data[1]', '$data[4], $data[1]')";
                            $userController->executeSqlQuery($query4);
                        } else if ($count == 3) {
                            $query5 = "INSERT INTO Participant(participantId, user_id) VALUES ('$data[0]', '$data[0]')";
                            $userController->executeSqlQuery($query5);

                            $query6 = "INSERT INTO event_participants (event_instance_id, event_participant_id) VALUES ('$data[1]', '$data[0]')";
                            $result = $userController->executeSqlQuery($query6);

                            if (!empty($result)) {
                                $type = "success";
                                $message = "CSV Data Imported into the Database";
                            } else {
                                $type = "error";
                                $message = "Problem in Importing CSV Data";
                            }
                        }
                    }
                }
            }
            ?>



        </div>
    </div>
</div>

</body>

</html>