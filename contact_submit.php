<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
    $contact = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : ''; // now can be email or phone
    $budget = isset($_POST['budget']) ? $conn->real_escape_string($_POST['budget']) : '';
    $message = isset($_POST['message']) ? $conn->real_escape_string($_POST['message']) : '';

    if (empty($name) || empty($contact) || empty($message)) {
        echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
        exit;
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, budget, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $contact, $budget, $message);

    if ($stmt->execute()) {

        // ===== Admin Notification =====
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'websitespixelforge@gmail.com';
            $mail->Password = 'hylqxxkbkczvzhkg';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('websitespixelforge@gmail.com', 'PixelForge');
            $mail->addAddress('websitespixelforge@gmail.com', 'Sara Pojaku');

            $mail->isHTML(true);
            $mail->Subject = 'New Contact Form Submission';
            $mail->Body = "<b>Name:</b> $name<br>
                           <b>Contact (email/phone):</b> $contact<br>
                           <b>Budget:</b> $budget<br>
                           <b>Message:</b> $message";

            $mail->send();
            
            // Thank-you email to sender
            $mail2 = new PHPMailer(true);
            $mail2->isSMTP();
            $mail2->SMTPAuth = true;
            $mail2->Host = 'smtp.gmail.com';
            $mail2->Password = 'hylqxxkbkczvzhkg';
            $mail2->Username = 'websitespixelforge@gmail.com';
            $mail2->SMTPSecure = 'tls';
            $mail2->Port = 587;
            
            $mail2->setFrom('websitespixelforge@gmail.com', 'PixelForge');
            $mail2->addAddress($contact); // corrected
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

