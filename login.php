<?php
include('header.php');
include("classes/user.class.php");
include("classes/UUID.class.php");
?>
<body>
    <?php
    // Check if a task was created, then show confirmation3
    if (!empty($_GET['create'])) {
        echo '<div id="taskCreated" class="taskCreated">';
            echo '<p>Je hebt succesvol een account aangemaakt.</p> <i onclick="deleteObject()" id="cross" class="fas fa-times-circle"></i>';
        echo '</div>';
        }
    
    ?>
    <div class="content">
        
    <?php
    // Connect to the database
    $db = database::connect();

    echo '<h2 class="pageTitle"> Inloggen </h2>';
    echo '<form class="RegisterForm" method="POST">';
        echo '<input type="text" value="" name="email1" placeholder="E-mailadres"> </br>';
        echo '<input type="password" value="" name="password1" placeholder="Wachtwoord"> </br>';
        echo '<a href="register" class="register">Heb je nog geen accout? Registreer je! </a> </br>';
        echo '<a href="forgot_password" class="register">Wachtwoord vergeten? Reset hier je wachtwoord! </a> </br>';
        echo '<button type="submit" name="submitLogin">Inloggen</button>';
    echo '</form>';
    // Login code
    if (isset($_POST["submitLogin"])) {
        $_POST['email1'] = database::validate($_POST['email1']);
        $_POST['password1'] = database::validate($_POST['password1']);

        $email = $_POST["email1"];
        $password = $_POST["password1"];
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($db, $sql);
        
        $rowcount = mysqli_num_rows($result);
        if ($rowcount >= 1) {
            $result = $result->fetch_array();
            if (password_verify($password, $result['password'])) {
                // succesvol login.
                session_start();
                $_SESSION['active'] = 'true';
                $_SESSION['admin'] = $result['admin'];
                $_SESSION['type'] = $result['type_id'];
                $_SESSION['user_id'] = $result['ID'];
                // print_r($_SESSION);
                header('location: index');
            } else {
                echo '<script>alert("E-mailadres of wachtwoord onjuist.");</script>';
            }
        } else {
            echo 'E-mailadres of wachtwoord is onjuist.';
        }

    }
    ?>
    </div>
</body>
</html>