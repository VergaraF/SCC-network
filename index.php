<?php
session_start();
include('./Predefined/init.php');
?>
<html>
<head>
  <title>The SCC-Network</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="./Styles/main.css">
  <link rel="stylesheet" type="text/css" href="./Styles/joiningSite.css">
  <link rel="stylesheet" type="text/css" href="./Styles/event.css">
</head>
<?php 
  include('./Predefined/header.php'); 
  include('./Predefined/sideMenu.php');  
  include('homePage.php')
?>
</body>
</html>

