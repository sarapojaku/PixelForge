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
            $mail->Username = 'pojakusara@gmail.com';
            $mail->Password = 'kxnxpezhrshioqmx';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('pojakusara@gmail.com', 'PixelForge');
            $mail->addAddress('pojakusara@gmail.com', 'Sara Pojaku');

            $mail->isHTML(true);
            $mail->Subject = 'New Contact Form Submission';
            $mail->Body = "<b>Name:</b> $name<br>
                           <b>Contact (email/phone):</b> $contact<br>
                           <b>Budget:</b> $budget<br>
                           <b>Message:</b> $message";

            $mail->send();

        } catch (Exception $e) {
            // continue even if email fails
        }

        echo json_encode(["status" => "success", "message" => "Message sent successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}

$conn->close();
?>
