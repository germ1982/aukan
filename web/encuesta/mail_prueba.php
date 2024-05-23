<?php
require_once('../../PHPMailer/class.phpmailer.php');  
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 2;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = 'c0720217.ferozo.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'informarica@cncnqn.com.ar';                     // SMTP username
    $mail->Password   = '@3rCeP5yF';                               // SMTP password
    $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('palacios.soraya@gmail.com', 'Mailer');
    $mail->addAddress('palacios.soraya@hotmail.com', 'Joe User');     // Add a recipient
    $mail->addAddress('sistemas@homecaresa.com.ar');               // Name is optional
    $mail->addAddress('palacios.soraya@gmail.com');
    $mail->addAddress('informarica@cncnqn.com.ar');
    $mail->addReplyTo('informarica@cncnqn.com.ar', 'Information');

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
