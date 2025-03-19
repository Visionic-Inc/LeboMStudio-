<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json'); // Ensure the content type is JSON

// Debugging: Log incoming POST data
file_put_contents('php://stdout', "Received POST data:\n" . print_r($_POST, true) . "\n", FILE_APPEND);

if (empty($_POST['name']) || empty($_POST['number']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || empty($_POST['subject']) || empty($_POST['message'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Please fill all fields correctly.']);
    exit();
}

$name = strip_tags(htmlspecialchars($_POST['name']));
$number = strip_tags(htmlspecialchars($_POST['number']));
$email = strip_tags(htmlspecialchars($_POST['email']));
$m_subject = strip_tags(htmlspecialchars($_POST['subject']));
$message = strip_tags(htmlspecialchars($_POST['message']));

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true;
    $mail->Username = 'ml.lunga.ntuli@gmail.com'; 
    $mail->Password = 'natp wqgi ltbd zcft'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Recipients
    $mail->setFrom($email, $name);
    $mail->addAddress('leboganghleza@gmail.com'); 

    // Content
    $mail->isHTML(true);
    $mail->Subject = "$m_subject: $name";
    $mail->Body = "You have received a new message from your website contact form.<br><br>
                   <strong>Name:</strong> $name<br>
                   <strong>Phone Number:</strong> $number<br>
                   <strong>Email:</strong> $email<br>
                   <strong>Subject:</strong> $m_subject<br>
                   <strong>Message:</strong> $message";

    $mail->send();

    // Send success response
    echo json_encode(['status' => 'success', 'message' => 'Message sent successfully!']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error', 
        'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo
    ]);
}
?>
