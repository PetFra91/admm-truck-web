<?php
/**
 * ADMM-Truck – zpracování kontaktního formuláře
 * Umístěte tento soubor do kořene webu jako: send.php
 */

// Kam přijde e-mail
$to = 'info@admm-truck.com';

// Ochrana – přijímáme jen POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

// Načtení a vyčištění dat
$name    = trim(strip_tags($_POST['name']    ?? ''));
$email   = trim(strip_tags($_POST['email']   ?? ''));
$phone   = trim(strip_tags($_POST['phone']   ?? ''));
$message = trim(strip_tags($_POST['message'] ?? ''));

// Validace
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: index.html?status=error#kontakt');
    exit;
}
if (empty($message)) {
    header('Location: index.html?status=error#kontakt');
    exit;
}

// Sestavení e-mailu
$subject = 'Nová poptávka z webu ADMM-Truck';

$body  = "Nová zpráva z poptávkového formuláře na admm-truck.cz\n";
$body .= str_repeat('─', 50) . "\n\n";
$body .= "Jméno:    " . ($name  ?: '(neuvedeno)') . "\n";
$body .= "E-mail:   " . $email  . "\n";
$body .= "Telefon:  " . ($phone ?: '(neuvedeno)') . "\n\n";
$body .= "Zpráva:\n" . $message . "\n\n";
$body .= str_repeat('─', 50) . "\n";
$body .= "Odesláno: " . date('d.m.Y H:i') . "\n";

$headers  = "From: noreply@admm-truck.cz\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

$sent = mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, $headers);

if ($sent) {
    header('Location: index.html?status=ok#kontakt');
} else {
    header('Location: index.html?status=error#kontakt');
}
exit;
?>
