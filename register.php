<?php
include('header.php');
include("classes/user.class.php");
include("classes/UUID.class.php");
// include("classes/database.class.php");
?>
<body>
    <div class="content">
        
    <?php
    $db = database::connect();

    echo '<h2 id="pageTitle" class="pageTitle"> Registreren </h2>';
    echo '<form class="RegisterForm" method="POST">';
        echo '<input type="email" value="" name="email" placeholder="E-mailadres"> </br>';
        echo '<input type="password" value="" name="password" placeholder="Wachtwoord"> </br>';
        echo '<a href="login" class="register">Heb je al een account? Log dan in! </a> </br>';
        echo '<button type="submit" name="submitRegister">Registreren</button>';
    echo '</form>';
    // Register code
    if (isset($_POST["submitRegister"])) {
        if (!empty($_POST["email"]) || !empty($_POST["password"])) {
            $_POST['email'] = database::validate($_POST['email']);
            $_POST['password'] = database::validate($_POST['password']);
            $post = $_POST;
            $send = '';
            if (((strlen($_POST["email"])) <= 30) && ((strlen($_POST["password"])) <= 30)) {
                // check email
                // $email_check = user::check_email($_POST["email"]);
                // var_dump($mysqli_num_rows ( $email_check ));
                $send = user::create_user($post);
            } else {
                echo '<p id="error" class="error">E-mail of wachtwoord te lang, maximaal aantal karakters is 30.</p>';
                echo '<script>setErrorMsg("error", "pageTitle");</script>';
            }

            if ($send) {
                header("Location:login?create=true");
            }
        } else {
            echo '<p id="error" class="error">Vul beide velden in met correcte gegevens.</p>';
            echo '<script>setErrorMsg("error", "pageTitle");</script>';
        }

    }

    ?>
    </div>
</body>
</html>