<?php
    session_start();
    require_once('./Predefined/init.php');
    $userController = new UserController();

    if (isset($_GET['redirect'])) {
        header('Location: ' . $_GET['redirect']);
    }
    if(isset($_POST["login"])){
        $userController->processLoginForm($_POST);
    }
?>
<html>
    <head>
        <title>The SCC-Network - Log in</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="./Styles/main.css">
        <link rel="stylesheet" type="text/css" href="./Styles/joiningSite.css">
    </head>
<?php
    require_once('./Predefined/header.php'); 
    require_once('./Predefined/sideMenu.php');

    Helper::displayMessage();
?>
    <div class="col-6">
        <div class="row main-content">
            <div class="col-12">
                <h2>Login</h2>
            </div>
            <div class="row padded-row">
                <form name ="login" method="POST" action="">
                    <div class="col-3"><label class="username-label" for="username"><b>Username : </b></label></div>
                    <div class="col-9"><input type="text"  name="username" class="username" id="username" placeholder="Username" required/></div>
                    <div class="col-3"><label class="password-label" for="password"><b>Password : </b></label></div>
                    <div class="col-9"><input type="password" name="password" class="password" id="password" placeholder="Password" required/></div>
                    <div class="col-12"><label class="error"><?php Helper::displayMessage();?></label></div>
                    <div class="col-12"><input name="login" type="submit" value="Login" class="login btn"/></div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

