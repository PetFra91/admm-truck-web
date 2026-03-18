<?php
$to = 'info@admm-truck.com';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /'); exit; }
$name    = trim(strip_tags($_POST['name']    ?? ''));
$email   = trim(strip_tags($_POST['email']   ?? ''));
$phone   = trim(strip_tags($_POST['phone']   ?? ''));
$message = trim(strip_tags($_POST['message'] ?? ''));
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($message)) {
    header('Location: /?status=error#kontakt'); exit;
}
$subject = 'Nová poptávka z webu ADMM-Truck';
$body  = "Nová zpráva z poptávkového formuláře\n";
$body .= str_repeat('-',50)."\n\n";
$body .= "Jméno:   ".($name ?: '(neuvedeno)')."\n";
$body .= "E-mail:  ".$email."\n";
$body .= "Telefon: ".($phone ?: '(neuvedeno)')."\n\n";
$body .= "Zpráva:\n".$message."\n\n";
$body .= "Odesláno: ".date('d.m.Y H:i')."\n";
$headers  = "From: noreply@admm-truck.cz\r\n";
$headers .= "Reply-To: ".$email."\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$sent = mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $body, $headers);
header($sent ? 'Location: /?status=ok#kontakt' : 'Location: /?status=error#kontakt');
exit;
