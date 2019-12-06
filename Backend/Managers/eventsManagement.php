<h3>List of All Events</h3>
<table align="center" border="1">
    <tbody>
        <th>Name</th>
        <th>Storage limit MB</th>
        <th>Bandwidth limit MB</th>
        <th>Storage usage MB</th>
        <th>Bandwidth usage MB</th>
        <th>Status</th>
        <th>Lifetime</th>
        <th>Event admin</th>
        <th>Options </th>
        <?php
        $eventInfo = $contentController->getAllEvents();
        if (count($eventInfo) > 0) {
            for ($row = 0; $row < count($eventInfo); $row++) {
                echo "<tr><td>" . $eventInfo[$row]['event_name'] . "</td>" .
                    "<td>" . $eventInfo[$row]['storage_limit'] . "</td>" .
                    "<td>" . $eventInfo[$row]['bandwith_limit'] . "</td>" .
                    "<td>" . $eventInfo[$row]['storage_usage'] . "</td>" .
                    "<td>" . $eventInfo[$row]['bandwidth_usage'] . "</td>" .
                    "<td>" . $contentController->getEventInstanceStatus($eventInfo[$row]['eventStatus_id'])[0]['name'] . "</td>" .
                    "<td>" . $eventInfo[$row]['lifetime'] . "</td>" .
                    "<td>" . $eventInfo[$row]['adminUsername'] . "</td>";
                ?>
                <form name="displayEvent" method="POST" action="">
                    <input name="hiddenEventInstanceId" type="hidden" value="<?php echo $eventInfo[$row]['event_instanceId']; ?>" />
                    <input name="hiddenEventId" type="hidden" value="<?php echo $eventInfo[$row]['event_id']; ?>" />
                    <input name="hiddenEventStatusId" type="hidden" value="<?php echo $eventInfo[$row]['eventStatus_id']; ?>" />
                    <input name="hiddenEventPageId" type="hidden" value="<?php echo $eventInfo[$row]['page_id']; ?>" />
                    <input name="hiddenEventBillId" type="hidden" value="<?php echo $eventInfo[$row]['bill_id']; ?>" />
                    <input name="hiddenEventManagerId" type="hidden" value="<?php echo $eventInfo[$row]['manager_id']; ?>" />
                    <input name="hiddenEventBankingInfoId" type="hidden" value="<?php echo $eventInfo[$row]['bankingInfo_id']; ?>" />

                    <td>
                        <?php if(strcmp($userRole, Helper::ADMIN_USER_ROLE_ID) === 0){
                            echo "<input name='editEvent' type='submit' value='Edit' />" . 
                                "<input name='activateEvent' type='submit' value='Activate' />" .
                                "<input name='deleteEvent' type='submit' value='Delete' />" .
                                "<input name='seeBill' type='submit' value='Manage bill' />";
                         }?>
                        
                        <input name='seeFees' type='submit' value='Manage fees' />
                        <input name='seeBankingInfo' type='submit' value='Manage banking info'/>
                        <input name='seeManager' type='submit' value='See manager profile' />
                    </td>
                </form>
        <?php

            }
        }
        ?>
    </tbody>
</table>
<?php
if (isset($_POST['editUser'])) {
    $profileInfo = $userController->getUserById($_POST['hiddenID']);
    if (count($profileInfo) > 0) {
        for ($row = 0; $row < count($profileInfo); $row++) {
            echo "<table align='center'><form name='updateProfile' method='POST' action=''>" .
                "<h2>User Profile</h2>" .
                "<tr><td>First Name : </td>" . "<td><input name='firstname' type='text' value='"     . $profileInfo[$row]['firstname'] . "'></td></tr>" .
                "<tr><td>Last Name : </td>"  . "<td><input name='lastname' type='text' value='"     . $profileInfo[$row]['lastname']  . "'></td></tr>" .
                "<tr><td>Email : </td>"        . "<td><input name='email' type='text' value='"         . $profileInfo[$row]['email']       . "'></td></tr>" .
                "<tr><td>Username : </td>"   . "<td><input name='username' type='text' value='"     . $profileInfo[$row]['username']  . "'></td></tr>" .
                "<tr><td>Age : </td>"  . "<td><input name='age' type='text' value='"     . $profileInfo[$row]['age']  . "'></td></tr>" .
                "<tr><td>Profession : </td>"  . "<td><input name='profession' type='text' value='"     . $profileInfo[$row]['profession']  . "'></td></tr>" .
                "<tr><td>Birthdate : </td>"  . "<td><input name='dob' type='text' value='"     . $profileInfo[$row]['dateOfBirth']  . "'></td></tr>" .
                "<tr><td>Role : </td><td><select id='role' name='role'>";
            $rolesInSystem = $userController->getAllRolesInSCC();

            if (count($rolesInSystem) > 0) {
                for ($row2 = 0; $row2 < count($rolesInSystem); $row2++) {
                    echo  "<option value='" . $rolesInSystem[$row2]['id'] . "'>" . $rolesInSystem[$row2]['role_name'] . " </option>";
                }
            }
            echo "</select></td>" .
                "<input type='hidden' name='hiddenUserId' value='" . $profileInfo[$row]['userId'] . "'>" .
                "<tr><td></td><td><input name='editConfirmation' type='submit' value='Save Changes'></td></tr>";
            echo "</form></table>";
            LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User " . $_SESSION['USERNAME'] . " is editing user  " . $profileInfo[$row]['username']);
        }
    }
} else if (isset($_POST['seeUserActions'])) {
    LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User " . $_SESSION['USERNAME'] . " is seeing user actions for user " . $_POST['hiddenUsername']);
    $actions = $adminManagerController->getUserActions($_POST['hiddenUserId']);
    if (count($actions) === 0) {
        echo "<b>No actions found for this user</b>";
    } else {
        echo "<table align='center' border='1'>" .
            "<tbody> <th>Action performed</th><th>Timestamp/th>";
        for ($row3 = 0; $row3 < count($actions); $row3++) {
            echo "<tr><td>" . $actions[$row3]['actionPerformed'] . "</td>" .
                "<td>" . $action[$row3]['actionPerformedAt'] . "</td></tr>";
        }
        echo "</tbody></table>";
    }
} else if (isset($_POST['deactivateUser'])) {
    LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User " . $_SESSION['USERNAME'] . " is deactivating user " . $_POST['hiddenUsername']);
    $profileInfo = $userController->getUserById($_POST['hiddenID']);
    if (count($profileInfo) > 0) {
        for ($row = 0; $row < count($profileInfo); $row++) {
            echo "<table align='center'><form name='updateProfile' method='POST' action=''>";
            echo "<h2>Deactivate User</h2>";
            echo "<tr><td>First Name</td>" . "<td><input name='firstname' type='text' value='" . $profileInfo[$row]['firstname'] . "'></td></tr>" .
                "<tr><td>Last Name</td>" . "<td><input name='lastname' type='text' value='" . $profileInfo[$row]['lastname'] . "'></td></tr>" .
                "<tr><td>Username</td>" . "<td><input name='username' type='text' value='" . $profileInfo[$row]['username'] . "'></td></tr>" .
                "<tr><td>Description</td>" . "<td><textarea name='description' type='text' style='width:175px; height:100px;'></textarea></td></tr>" .
                "<input type='hidden' name='hiddenUserId' value='" . $profileInfo[$row]['userId'] . "'>" .
                "<tr><td></td>" . "<td><input name='deactivateConfirmation' type='submit' value='Deactivate this user'></td></tr>";
            echo "</form></table>";
        }
    }
} else if (isset($_POST['deleteUser'])) {
    LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User " . $_SESSION['USERNAME'] . " is deleting user " . $_POST['hiddenUsername']);
} else if (isset($_POST['editConfirmation'])) {
    LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User " . $_SESSION['USERNAME'] . " edited user " . $_POST['hiddenUsername']);
    $adminManagerController->updateUser($_POST);
} else if (isset($_POST['deactivateConfirmation'])) {
    LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User " . $_SESSION['USERNAME'] . " deactivated  user " . $_POST['hiddenUsername']);
    $adminManagerController->banUser($_POST['hiddenUserId'], $_POST['description'], $_SESSION['IDENTIFIER']);
} else if (isset($_POST['activateUser'])) {
    LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User " . $_SESSION['USERNAME'] . " is activating user " . $_POST['hiddenUsername']);
    if ($adminManagerController->unbanUser($_POST['hiddenID'])) {
        echo "The account for user " . $_POST['hiddenUsername'] . "is active again.";
    }
}


?>