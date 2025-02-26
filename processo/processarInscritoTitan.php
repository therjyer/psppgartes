<?php

require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$sender_email = 'psppgartes@pokkins.com';
$sender_password = '<P~XGX;Pm|/A60J';
$recipient_email = 'thiago.correa@icen.ufpa.br';
$subject = 'Testing email script';
$body = 'This is a test email sent from a PHP script.';

$smtp_server = 'smtp.titan.email';
$smtp_port = 587;

$imap_server = 'imap.titan.email';
$imap_port = 993;

function send_email() {
global $sender_email, $sender_password, $recipient_email, $subject, $body, $smtp_server, $smtp_port, $imap_server, $imap_port;

$mail = new \PHPMailer\PHPMailer\PHPMailer();

try {
$mail->isSMTP();
$mail->Host = $smtp_server;
$mail->Port = $smtp_port;
$mail->SMTPAuth = true;
$mail->Username = $sender_email;
$mail->Password = $sender_password;
$mail->SMTPSecure = 'tls';

$mail->setFrom($sender_email);
$mail->addAddress($recipient_email);
$mail->Subject = $subject;
$mail->Body = $body;

if ($mail->send()) {
echo 'Email sent successfully.';
} else {
echo 'Error sending email: ' . $mail->ErrorInfo;
return;
}

$imap_stream = imap_open("{" . $imap_server . ":" . $imap_port . "/ssl/novalidate-cert}", $sender_email, $sender_password);
if ($imap_stream) {
imap_append($imap_stream, "{" . $imap_server . ":" . $imap_port . "/ssl/novalidate-cert}Sent", $mail->getSentMIMEMessage());
echo 'Email appended to "Sent" folder.';
imap_close($imap_stream);
} else {
echo 'Error appending email to "Sent" folder.';
}
} catch (Exception $e) {
echo 'Error sending email: ' . $e->getMessage();
}
}

send_email();
?>