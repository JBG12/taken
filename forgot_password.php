<?php
include('header.php');
include("classes/user.class.php");
include("classes/mail.class.php");

?>
<body>
    <div class="content">
        
    <?php
    $db = database::connect();

    echo '<h2 class="pageTitle"> Wachtwoord Vergeten </h2>';
    echo '<form class="ResetForm" method="POST">';
        echo '<input type="text" value="" name="email" placeholder="E-mailadres"> </br>';
        echo '<button type="submit" name="submitForget">Verzoek versturen</button>';
    echo '</form>';

    // Check if the form was submitted
    if (isset($_POST["submitForget"])) {
        if (isset($_POST["email"])) {
            // Get submitted email.
            $to = $_POST["email"];

            // Check if email exists          
            $query = "SELECT id FROM users WHERE email = '$to'";
            $result = $db->query($query);

            if ($result->num_rows > 0) {
                // Get the row with the matching email
                $row = $result->fetch_assoc();
                $user_uuid = $row['uuid'];
            } else {
                echo "Vul een geldig e-mailadres in.";
            }
            $link = 'http://localhost/taken/reset_pass.php?id='. $user_uuid;
            // Mail data
            $subject = 'Wachtwoord Reset';
            $body = 
            '<p>Er is een wachtwoord reset aanvraag gedaan voor je account, als dit niet jij was kun je deze mail negeren. <br> Klik op deze link om je wachtwoord te resetten: </p><br>
            <a href="' . $link . '">Wachtwoord Resetten</a>';

            // Send reset password request email.
            $mail = mail::sendEmail($to, $subject, $body);

        } else {
            echo 'Vul een geldig e-mailadres in.';
        }
        
    }

    ?>