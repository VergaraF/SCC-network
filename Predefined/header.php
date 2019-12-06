<body>
    <div class="header">
        <h1>The SCC-Network</h1>
    </div>
    <div class="row welcome-nav-bar">
        <div class="col-9">
            <?php
            require_once('init.php');
            $userController = new UserController();

            if ($userController->isLoggedIn()) {
                echo "Welcome, <b>" .  $_SESSION['USERNAME'] . "</b>!";

                if (!$userController->isUserDeactivated($_SESSION['IDENTIFIER'])) {
                    ?>
        </div>
        <div class="col-1 no-padding">
            <button class="welcome-nav-bar-btn" onClick="window.location.href='profile.php'">Profile</button>
        </div>
        <div class="col-1 no-padding">
            <button class="welcome-nav-bar-btn" onClick="window.location.href='mail.php'">Messages</button>
        </div>
    <?php
        }
        ?>

    <div class="col-1 no-padding">
        <button class="welcome-nav-bar-btn" onClick="window.location.href='logout.php'">Logout</button>
    </div>
<?php
} else {
    echo "Welcome, <b>visitor</b>!";
    ?>
    </div>
    <div class="col-2 no-padding">
        <button class="welcome-nav-bar-btn" onClick="window.location.href='login.php'">Login</button>
    </div>
    <div class="col-1 no-padding">
        <button class="welcome-nav-bar-btn" onClick="window.location.href='signup.php'">Signup</button>
    </div>
<?php
}
?>
</div>