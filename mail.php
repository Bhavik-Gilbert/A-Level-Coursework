<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
try {
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Mailer = "smtp";

    $mail->SMTPDebug  = 1;
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = "tls";
    $mail->Port       = 587;
    $mail->Host       = "smtp.sendgrid.net";
    $mail->Username   = "apikey";
    $mail->Password   = "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";

    $mail->SetFrom("bhaviklob@gmail.com", $email);
    $mail->AddAddress("bhaviklob@gmail.com");

    $body = "<p>".$message."</p>";
    $mail->IsHTML(true);
    $mail->Subject = "Photography Booking Site";
    
    $mail->Body = $body;
    $mail->AltBody = strip_tags($body);

    $mail->send();

    $message = "Message sent successfully, we'll get back to you at our earliest convenience";
}
catch (Exception $e) {
    $message = "Message could not be sent, try again later";
}
?>

<script type="text/javascript">
            document.body.innerHTML = '';
</script>