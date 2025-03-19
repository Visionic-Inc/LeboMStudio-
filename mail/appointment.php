<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

// Debugging: Log incoming POST data
file_put_contents('php://stdout', "Received POST data:\n" . print_r($_POST, true) . "\n", FILE_APPEND);

// Ensure all required fields are provided
if (empty($_POST['name']) || empty($_POST['number']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || empty($_POST['service']) || empty($_POST['date']) || empty($_POST['time'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Please fill all fields correctly.']);
    exit();
}

$name = strip_tags(htmlspecialchars($_POST['name']));
$number = strip_tags(htmlspecialchars($_POST['number']));
$email = strip_tags(htmlspecialchars($_POST['email']));
$service = strip_tags(htmlspecialchars($_POST['service']));
$date = strip_tags(htmlspecialchars($_POST['date']));
$time = strip_tags(htmlspecialchars($_POST['time']));

// Create PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  // Gmail SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'ml.lunga.ntuli@gmail.com';  // Your Gmail address
    $mail->Password = 'kibr ecjv afhn cfda';  // Use an App Password (DO NOT hardcode password in production)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Enable TLS encryption
    $mail->Port = 587;  // TCP port to connect to

    // Recipients
    $mail->setFrom($email, $name);  // Sender's email and name
    $mail->addAddress('leboganghleza@gmail.com');  // Recipient's email (your Gmail)

    // Content
    $mail->isHTML(true);
    $mail->Subject = "Appointment Request: $name for $service";
    $mail->Body = "
        You have received a new appointment request from your website.<br><br>
        <strong>Name:</strong> $name<br>
        <strong>Phone Number:</strong> $number<br>
        <strong>Email:</strong> $email<br>
        <strong>Service:</strong> $service<br>
        <strong>Date:</strong> $date<br>
        <strong>Time:</strong> $time
    ";

    // Send the email
    if ($mail->send()) {
        echo json_encode(['status' => 'success', 'message' => 'Appointment request sent successfully!']);
    } else {
        throw new Exception('Mailer could not send the email.');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error', 
        'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo
    ]);
}
?>
