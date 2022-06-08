<?php

    // require_once  "phpmailer/PHPMailerAutoload.php";
    // require_once "phpmailer/class.phpmailer.php";


    // $mail = new PHPMailer();
    // $mail->isSMTP();
    // //$mail->SMTPDebug = 2;
    // $mail->Host = "smtp.hostinger.com";
    // $mail->Port = 587;
    // $mail->SMTPAuth = true;
    // $mail->Username = "support@committi.com";
    // $mail->Password = 'Committi@2022';
    // $mail->setFrom("support@committi.com", "Committi");
    // $mail->Subject = 'Test Email';
    // $mail->addAddress('lovepreet.wartiz@gmail.com');

    // $output= 'Test Email';
    // $mail->msgHTML($output);

    // if (!$mail->send()) {
    //     $error = "Mailer Error: " . $mail->ErrorInfo;
    //     echo '<p id="para">' . $error . "</p>";
    // } else {
    //     //echo true;
    // }



$to      = "lovepreet.wartiz@gmail.com";

$subject = "Test Mail";

$message = "This is a test email";

echo mail($to, $subject, $message);

print_r(error_get_last());


?>