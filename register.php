<?php
include('header.php');
include("classes/user.class.php");
include("classes/UUID.class.php");
?>
<body>
    <div class="content">
        
    <?php
    $db = database::connect();

    echo '<h2 class="pageTitle"> Registreren </h2>';
    echo '<form class="RegisterForm" method="POST">';
        echo '<input type="text" value="" name="email" placeholder="E-mailadres"> </br>';
        echo '<input type="password" value="" name="password" placeholder="Wachtwoord"> </br>';
        echo '<a href="login" class="register">Heb je al een account? Log dan in! </a> </br>';
        echo '<button type="submit" name="submitRegister">Registreren</button>';
    echo '</form>';
    // Register code
    if (isset($_POST["submitRegister"])) {
        $uuid = UUID::generateUUID();
        if (!empty($_POST["email"]) || !empty($_POST["password"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];
            // hash (encrypt) password in database for security
            $password_enq = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (ID, uuid, email, password, type_id) VALUES ('', '$uuid', '$email', '$password_enq', '3');";
            $result = mysqli_query($db, $sql);
            header("Location:login?create=true");
        } else {
            echo '<script>alert("Vul beide velden in met correcte gegevens.");</script>';
        }

    }

    ?>
    </div>
</body>
</html>