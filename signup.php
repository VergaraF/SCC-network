<?php
    require_once('./Predefined/init.php');
    session_start();
    $userController = new UserController();

    if (isset($_GET['redirect'])) {
        header('Location: ' . $_GET['redirect']);
    }
    if(isset($_POST["signup"])){
        $userController->processSignupForm($_POST);
    }
?>
<html>
    <head>
        <title>The SCC-Network - Signup</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="./Styles/main.css">
        <link rel="stylesheet" type="text/css" href="./Styles/joiningSite.css">
        <script type="text/javascript" src="Scripts/validation.js"></script>
    </head>
<?php
    require_once('./Predefined/header.php'); 
    require_once('./Predefined/sideMenu.php');

    Helper::displayMessage();
?>
    <div class="col-6">
        <div class="row main-content">
            <div class="col-12">
                <h2>Sign up</h2>
            </div>
            <div class="row padded-row">
                <form name ="signup" method="POST" action="">
                    <div class="col-3"><label class="firstname-label" for="firstname"><b>Firstname : </b></label></div>
                    <div class="col-9"><input type="text"  name="firstname" class="firstname" id="firstname" placeholder="Firstname" required/></div>

                    <div class="col-3"><label class="lastname-label" for="lastname"><b>Lastname : </b></label></div>
                    <div class="col-9"><input type="text"  name="lastname" class="lastname" id="lastname" placeholder="Lastname" required/></div>

                    <div class="col-3"><label class="username-label" for="username"><b>Username : </b></label></div>
                    <div class="col-9">
                        <input type="text"  name="username" class="username" id="username" placeholder="Username" onchange="validateUsername(this, 5)" required/>
                    </div>

                    <div class="col-3"><label class="email-label" for="email"><b>Email : </b></label></div>
                    <div class="col-9">
                        <input type="text"  name="email" class="email" id="email" placeholder="email@example.com" required/>
                    </div>

                    <div class="col-3"><label class="password-label" for="password"><b>Password : </b></label></div>
                    <div class="col-9">
                        <input type="password" name="password" class="password" id="password" placeholder="Password" onchange="checkPassword(this)" required/>
                    </div>

                    <div class="col-3"><label class="password-label" for="cPassword"><b>Password : </b></label></div>
                    <div class="col-9">
                        <input type="password" name="cPassword" class="password" id="cPassword" placeholder="Re-enter password" onchange="comparePasswords(document.getElementById('password'), this)" required/>
                    </div>

                    <div class="col-3"><label class="profession-label" for="profession"><b>Profession : </b></label></div>
                    <div class="col-9">
                        <select name="profession" id="profession" class="profession-drop-down">
                            <option value="engineer">Engineer</option>
                            <option value="student">Student</option>
                            <option value="doctor">Doctor</option>
                            <option value="politician">Politician</option>
                            <option value="teacher">Teacher</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="col-3"><label class="dob-label" for="dob"><b>Birthdate : </b></label></div>
                    <div class="col-9">
                        <input type="text"  name="dob" class="dob" id="dob" placeholder="YYYY-MM-DD (Example : 1990-01-01)" onchange="checkDateOfBirth(this)" required/>
                    </div>

                    <div class="col-12"><label class="error">Error : Invalid username or password!</label></div>
                    <div class="col-12"><input name="signup" type="submit" value="Join now!" class="signup btn" onclick="return validateSignupForm()"/></div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
