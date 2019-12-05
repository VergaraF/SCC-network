<?php
session_start();
LogController::getInstance()->LogAction($_SESSION['IDENTIFIER'], "User ". $_SESSION['USERNAME'] . " logged out.");
unset($_SESSION['USERNAME']);
unset($_SESSION['IDENTIFIER']);
session_destroy();
require('./Predefined/header.php');
require('./Predefined/sideMenu.php');
?>
<html>
    <head>
        <title>The SCC-Network - Log out</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="./Styles/main.css">
        <link rel="stylesheet" type="text/css" href="./Styles/joiningSite.css">
    </head>
    
    <body>
        <div class="col-6">
            <div class="row main-content">
                <div class="col-12">
                    <h2>Good bye!</h2>
                </div>
                <div class="row padded-row">
                    <p>You have been succesfully logged out. We hope to you see soon!</p>
                </div>
            </div>
        </div>
    </body>
</html>

