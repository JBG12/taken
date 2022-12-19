<?php
include('header.php');
include("classes/user.class.php");
include("classes/UUID.class.php");
?>
<body>
    <div class="content">
        
    <?php
    // Connect to the database
    $db = database::connect();

    echo '<h2 class="pageTitle"> Inloggen </h2>';
    echo '<form class="RegisterForm" method="POST">';
        echo '<input type="text" value="" name="email1" placeholder="E-mailadres"> </br>';
        echo '<input type="password" value="" name="password1" placeholder="Wachtwoord"> </br>';
        echo '<a href="register.php" class="register">Heb je nog geen accout? Registreer je! </a> </br>';
        echo '<button type="submit" name="submitLogin">Inloggen</button>';
    echo '</form>';
    // Login code
    if (isset($_POST["submitLogin"])) {
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
                // print_r($_SESSION);
                header('location: index.php');
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