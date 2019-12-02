<div class="row">
    <div class="col-2 menu">
    <ul>
        <li>Home page</li>
        <?php
            $dbController = new DatabaseController();
            $userController = new UserController();

            $userRole = $userController->getUserRoleInSystem($_SESSION['USERNAME']);

            if ($userController->isLoggedIn()){
        ?>
                <li>My events</li>
                <li>My groups</li>
                <?php
                    if(strcmp($userRole, Helper::ADMIN_USER_ROLE_ID) === 0){
                ?>
                        <li>Admin panel</li>
                    <?php
                    }
                    else if (strcmp($userRole, Helper::CONTROLLER_USER_ROLE_ID) === 0){
                    ?>
                            <li>Controller panel</li>
                    <?php
                    }
                    if ($userController->isUserAnEventManager($_SESSION['IDENTIFIER'])){
                    ?>
                        <li>Event manager panel </li>
                    <?php
                    }
            }
            ?>
    </ul>
</div>