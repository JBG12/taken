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

        $query = "SELECT * FROM users WHERE uuid = '$user_uuid'";
        $result = $db->query($query);
        // Check if uuid is a valid one:
        if (mysqli_num_rows($result) >= 1) {
            // true
        } else {
            header('location: login');
        }

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
                echo '<p>Je kan niet je bestaande wachtwoord gebruiken!</p>';
            } else {
                $password = $new_password;
                // Hash user password
                $hashed_password = user::hashPassword($password);
                // Update user
                $update = user::updateUserPassword($user_uuid, $hashed_password);
                echo '<div class="resetC">';
                echo '<p>Wachtwoord succesvol aangepast.</p>';
                    echo '<a href="login" class="register">Inloggen</a>';
                echo '</div>';
            }

        }

    }
    if (isset($_SESSION['active'])) {
        header('location: index');
    }
    ?>
    </div>
</body>
</html>