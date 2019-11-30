<body>
    <div class="header">
        <h1>The SCC-Network</h1>
    </div>

    <?php
        include('./Backend/Controllers/DatabaseController.php');
        include('./Backend/Controllers/UserController.php')

    
    ?>

    <div class = "row welcome-nav-bar">
        <div class="col-8">
            Welcome, {username}!
        </div>

        <div class="col-2 no-padding">
            <button class="welcome-nav-bar-btn">Profile</button>
        </div>

        <div class="col-2 no-padding">
            <button class="welcome-nav-bar-btn">Messages</button>
        </div>
    </div>