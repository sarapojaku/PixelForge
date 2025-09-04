<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $conn->real_escape_string($_POST['name'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $budget = $conn->real_escape_string($_POST['budget'] ?? '');
    $message = $conn->real_escape_string($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, budget, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $budget, $message);

    if ($stmt->execute()) {
        // PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'pojakusara@gmail.com';
            $mail->Password = 'kxnxpezhrshioqmx';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Admin notification
            $mail->setFrom('pojakusara@gmail.com', 'PixelForge');
            $mail->addAddress('pojakusara@gmail.com', 'Sara Pojaku');

            $mail->isHTML(true);
            $mail->Subject = "New Contact Form Message";
            $mail->Body = "
                <b>Name:</b> $name<br>
                <b>Email:</b> $email<br>
                <b>Budget:</b> $budget<br>
                <b>Message:</b> $message
            ";
            $mail->send();

            // Thank-you email to sender
            $mail2 = new PHPMailer(true);
            $mail2->isSMTP();
            $mail2->Host = 'smtp.gmail.com';
            $mail2->SMTPAuth = true;
            $mail2->Username = 'pojakusara@gmail.com';
            $mail2->Password = 'kxnxpezhrshioqmx';
            $mail2->SMTPSecure = 'tls';
            $mail2->Port = 587;

            $mail2->setFrom('pojakusara@gmail.com', 'PixelForge');
            $mail2->addAddress($email);
            $mail2->isHTML(true);
            $mail2->Subject = "Thank you for contacting PixelForge!";
            $mail2->Body = "Hello $name,<br><br>Thank you for reaching out! We received your message and will get back to you soon.<br><br>Best regards,<br>PixelForge Team";
            $mail2->send();

            echo json_encode(["status" => "success", "message" => "Message sent successfully! Emails delivered."]);

        } catch (Exception $e) {
            echo json_encode(["status" => "success", "message" => "Message saved but email failed: {$mail->ErrorInfo}"]);
        }

    } else {
        echo json_encode(["status" => "error", "message" => "Error saving message: {$conn->error}"]);
    }

    $stmt->close();
}
$conn->close();
?>
