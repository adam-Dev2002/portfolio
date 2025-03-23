<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load Composer's autoloader
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form inputs
    $name    = strip_tags(trim($_POST["name"]));
    $email   = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = strip_tags(trim($_POST["message"]));

    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo '<div class="alert alert-danger" role="alert">Please complete the form correctly and try again.</div>';
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'adamromas0634@gmail.com';  // Your Gmail
        $mail->Password   = 'vpkomdpnepwthtzm';         // Your App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('adamromas0634@gmail.com', 'Adam Romas');
        $mail->addAddress('adamromas0634@gmail.com');

        // Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = "From: $name\nEmail: $email\n\nMessage:\n$message";

        $mail->send();

        http_response_code(200);
        echo '<div class="alert alert-success" role="alert">Thank you! Your message has been sent.</div>';

    } catch (Exception $e) {
        http_response_code(500);
        echo '<div class="alert alert-danger" role="alert">Oops! Something went wrong. Mailer Error: ' . $mail->ErrorInfo . '</div>';
    }
} else {
    http_response_code(403);
    echo '<div class="alert alert-danger" role="alert">There was a problem with your submission. Please try again.</div>';
}
?>
