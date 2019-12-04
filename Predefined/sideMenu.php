<div class="row">
    <div class="col-2 menu">
    <ul>
        <li class="btn" onClick="window.location.href='index.php'">Home page</li>
        <?php
            if ($userController->isLoggedIn()){
                $userRole = $userController->getUserRoleInSystem($_SESSION['USERNAME']);                
        ?>
                <li class="btn" onClick="window.location.href='myEventsPage.php'">My events</li>
                <li class="btn">My groups</li>
                <?php
                    if(strcmp($userRole, Helper::ADMIN_USER_ROLE_ID) === 0){
                ?>
                        <li class="btn" onClick="window.location.href='adminPanel.php'">Admin panel</li>
                        <li class="btn" onClick="window.location.href='controllerPanel.php'">Controller panel</li>
                    <?php
                    }
                    else if (strcmp($userRole, Helper::CONTROLLER_USER_ROLE_ID) === 0){
                    ?>
                            <li class="btn" onClick="window.location.href='controllerPanel.php'">Controller panel</li>
                    <?php
                    }
                    if ($userController->isUserAnEventManager($_SESSION['IDENTIFIER'])){
                    ?>
                        <li class="btn" onClick="window.location.href='eventManagerPanel.php'">Event manager panel </li>
                    <?php
                    }
            }
            ?>
    </ul>
</div>