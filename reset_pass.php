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

    if (isset($_GET['id'])) {
        $user_uuid = $_GET['id'];
        $user = user::ExistsAccountByUUID($user_uuid);
        
        echo '<h2 class="pageTitle"> Wachtwoord Resetten </h2>';
        echo '<form class="RegisterForm" method="POST">';
            echo '<input type="password" value="" name="password" placeholder="Wachtwoord"> </br>';
            echo '<button type="submit" name="submitReset">Versturen</button>';
        echo '</form>';

        if (isset($_POST["submitReset"])) {
            // Check if submitted password is not the same as current one
            $new_password = $_POST['password'];
            $current_password = user::getPasswordByUUID($user_uuid);
            if (password_verify($new_password, $current_password)) {
                echo 'Je kan niet je bestaande wachtwoord gebruiken!';
            } else {
                $password = $new_password;
                // Hash user password
                $hashed_password = user::hashPassword($password);
                // Update user
                $update = user::updateUserPassword($user_uuid, $hashed_password);
                echo '<p>Wachtwoord succesvol aangepast.</p>';
            }

        }

    }
    if (isset($_SESSION['active'])) {
        header('location: index.php');
    } else {
        header('location: login.php');
    }

    ?>
    </div>
</body>
</html>