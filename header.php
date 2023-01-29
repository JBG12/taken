<?php
session_start();
?>
<!DOCTYPE html>
<head>
    <link href="./CSS/css.css" rel="stylesheet">
    <link href="./CSS/css-dark.css" rel="stylesheet">
    <!-- <link href="./CSS/css-pixel.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link id="style-awesome-css" rel="stylesheet" href="//use.fontawesome.com/releases/v5.8.1/css/all.css?ver=5.8.1" type="text/css" media="all">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="classes/javascript.class.js"></script>
</head>
<body onload="toggleDarkMode('onload')">
<div class="header">
    <a class="mainTitle" href="./index">To-Do</a>
    <?php
    if (isset($_SESSION['active'])) {
        if ($_SESSION['admin'] == true) {
            echo '<div class="adminBox">';
                echo '<a class="panel" href="./admin">Admin Paneel</a>';
            echo '</div>';
        }
    }
    // Dark / lightmode switch
    echo '<div class="switchBox">';
    echo '<a class="switch" onclick="toggleDarkMode()" name="switch">Thema wisselen</a>';
    echo '</div>';

    if (isset($_SESSION['active'])) {
        echo '<form method="POST" class="logoutBox">';
            echo '<button type="submit" name="logout">Uitloggen</button>';
        echo '</form>';
    }
    if (isset($_POST["logout"])) {
        session_unset();
        session_destroy();
        header('location: login');
    }
    
    ?>
</div>