<?php

class mail {
    // Function to send emails using PHPMailer
    public static function sendEmail($to, $subject, $body) {
        // Load in necessary files from PHPMailer
        require('./vendor/autoload.php');
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        // Add SMPT data
        $mail->isSMTP();
        $mail->Host         = '';
        $mail->SMTPAuth     = true;
        $mail->Username     = '';
        $mail->Password     = '';
        $mail->SMTPSecure   = 'tls';
        $mail->Port         = 587;
        // Add sender and reciever
        $mail->setFrom('');
        $mail->addAddress($to);
        
        // Allow HTML in the email
        $mail->isHTML(true);
        $mail->ContentType = 'text/html';
        // Add mail subject and body values.
        $mail->Subject      = $subject;
        $mail->Body         = '<html><body>';
        $mail->Body         .= $body;
        $mail->Body         .= '</body></html>';

        // Send email
        if ($mail->send()) {
            // Mail is sent
        } else {
            echo 'Er is een fout opgetreden, probeer het later opnieuw.';
        }
    }
}
?>