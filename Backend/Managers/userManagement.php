<h3>List of All Users</h3>
<table align="center" border="1">
    <tbody>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Options</th>
        <?php
        $profileInfo = $userController->getAllUsers();
        if (count($profileInfo) > 0) {
            for ($row = 0; $row < count($profileInfo); $row++) {
                if (strcmp($profileInfo[$row]['username'], $_SESSION['USERNAME']) !== 0) {
                    $userRole = $userController->getRoleInSCCName($profileInfo[$row]['roleInSCC_id']);
                    echo "<tr><td>" . $profileInfo[$row]['firstname'] . "</td>" .
                        "<td>" . $profileInfo[$row]['lastname'] . "</td>" .
                        "<td>" . $profileInfo[$row]['username'] . "</td>" .
                        "<td>" . $profileInfo[$row]['email'] . "</td>" .
                        "<td> $userRole </td>";
                    ?>
                    <form name="displayUsers" method="POST" action="">
                        <input name="hiddenID" type="hidden" value="<?php echo $profileInfo[$row]['userId']; ?>" />
                        <td>
                            <input name='editUser' type='submit' value='Edit' />
                            <input name='seeUserActions' type='submit' value='See actions' />
                            <input name='deactivateUser' type='submit' value='Deactivate' />
                            <input name='deleteUser' type='submit' value='Delete' />
                        </td>
                    </form>
        <?php
                }
            }
        }
        ?>
    </tbody>
</table>
<?php
if (isset($_POST['editUser'])){ 
    $profileInfo = $userController->getUserById($_POST['hiddenID']);
    if (count($profileInfo) > 0) {
        for($row = 0; $row < count($profileInfo); $row++){
            echo "<table align='center'><form name='updateProfile' method='POST' action=''>" .
                 "<h2>User Profile</h2>" .
                 "<tr><td>First Name : </td>" . "<td><input name='firstname' type='text' value='" 	. $profileInfo[$row]['firstname'] ."'></td></tr>" .
                 "<tr><td>Last Name : </td>"  . "<td><input name='lastname' type='text' value='" 	. $profileInfo[$row]['lastname']  ."'></td></tr>" .
                 "<tr><td>Email : </td>" 	   . "<td><input name='email' type='text' value='" 		. $profileInfo[$row]['email'] 	  ."'></td></tr>" .
                 "<tr><td>Username : </td>"   . "<td><input name='username' type='text' value='" 	. $profileInfo[$row]['username']  ."'></td></tr>" .
                 "<tr><td>Age : </td>"  . "<td><input name='age' type='text' value='" 	. $profileInfo[$row]['age']  ."'></td></tr>" .
                 "<tr><td>Profession : </td>"  . "<td><input name='profession' type='text' value='" 	. $profileInfo[$row]['profession']  ."'></td></tr>" .
                 "<tr><td>Birthdate : </td>"  . "<td><input name='dob' type='text' value='" 	. $profileInfo[$row]['dateOfBirth']  ."'></td></tr>" .
                 "<tr><td>Role : </td><td><select id='role' name='role'>";
             $rolesInSystem = $userController->getAllRolesInSCC();
             
             if (count ($rolesInSystem) > 0){
                for($row2 = 0; $row2 < count($rolesInSystem); $row2++){
                    echo  "<option value='" . $rolesInSystem[$row2]['id'] . "'>" . $rolesInSystem[$row2]['role_name'] . " </option>" ;
                }
             }
             echo "</select></td>" .
                 "<input type='hidden' name='hiddenUserId' value='" . $profileInfo[$row]['userId'] . "'>" . 
                 "<tr><td></td><td><input name='editConfirmation' type='submit' value='Save Changes'></td></tr>";
            echo "</form></table>";
        }
    }
}

else if (isset($_POST['seeUserActions'])){ }
else if (isset($_POST['deactivateUser'])){ }
else if (isset($_POST['deleteUser'])){ }
else if (isset($_POST['editConfirmation'])) {
    echo var_dump($_POST);
    $adminManagerController->updateUser($_POST);
}
else if (isset($_POST['deactivateConfirmation'])) {}
else if (isset($_POST['activateUser'])) {}


?>