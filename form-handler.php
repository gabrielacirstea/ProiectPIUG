<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405); exit('Method Not Allowed');
}

$name    = trim($_POST['name']   ?? '');
$email   = trim($_POST['email']  ?? '');
$subject = trim($_POST['subject']?? 'Website contact');
$message = trim($_POST['mesaj']  ?? '');

if ($name === '' || $email === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header('Location: contact.html?sent=0&err=invalid'); exit;
}

// prevent header injection
if (preg_match('/[\r\n]/', $email) || preg_match('/[\r\n]/', $subject)) {
  header('Location: contact.html?sent=0&err=inject'); exit;
}

$to          = 'alexandragabriela988@gmail.com';           
$fromAddress = 'alexandra.cirstea@student.upt.ro';        
$emailSubject = 'New Form Submission: ' . $subject;

$body  = "User Name:    $name\r\n";
$body .= "User Email:   $email\r\n";
$body .= "Subject:      $subject\r\n";
$body .= "User Message:\r\n$message\r\n";

$headers = [
  "From: SugarBloom <{$fromAddress}>",
  "Reply-To: {$email}",
  "MIME-Version: 1.0",
  "Content-Type: text/plain; charset=UTF-8"
];

$ok = @mail($to, $emailSubject, $body, implode("\r\n", $headers));
header('Location: contact.html?sent=' . ($ok ? '1' : '0'));
exit;
