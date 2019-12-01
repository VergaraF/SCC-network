
<head>
  <title>The SCC-Network</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="./Styles/main.css">
</head>
<body>
    <div class="header">
        <h1>The SCC-Network</h1>
    </div>

    <div class = "row welcome-nav-bar">
        <div class="col-8">

    <?php
        include('./Backend/Controllers/DatabaseController.php');
        include('./Backend/Controllers/UserController.php');

        $dbController = new DatabaseController();
        $userController = new UserController();

        $userRole = $userController->getUserRoleInSystem($_SESSION['USERNAME']);

        if ($userController->isLoggedIn()){
            echo "Welcome, " +  $_SESSION['USERNAME'] + "!";
    ?>
            </div>
            <div class="col-2 no-padding">
                <button class="welcome-nav-bar-btn">Profile</button>
            </div>

            <div class="col-2 no-padding">
                <button class="welcome-nav-bar-btn">Messages</button>
            </div>
    <?php
    
            if(strcmp($userRole, Helper::ADMIN_USER_ROLE_ID) === 0){

            }

        }else{
            echo "Welcome, visitor!";
    ?>
            <div class="col-2 no-padding">
                <button class="welcome-nav-bar-btn">Login</button>
            </div>

            <div class="col-2 no-padding">
                <button class="welcome-nav-bar-btn">Signup</button>
            </div>
    <?php
        }
    ?>     
    </div>