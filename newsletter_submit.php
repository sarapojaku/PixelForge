<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];

    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {

            // ===== Admin Notification =====
            $adminMail = new PHPMailer(true);
            try {
                $adminMail->isSMTP();
                $adminMail->Host = 'smtp.gmail.com';
                $adminMail->SMTPAuth = true;
                $adminMail->Username = 'pojakusara@gmail.com';
                $adminMail->Password = 'kxnxpezhrshioqmx';
                $adminMail->SMTPSecure = 'tls';
                $adminMail->Port = 587;

                $adminMail->setFrom('pojakusara@gmail.com', 'PixelForge');
                $adminMail->addAddress('pojakusara@gmail.com', 'Sara Pojaku');

                $adminMail->isHTML(true);
                $adminMail->Subject = 'New Newsletter Subscription';
                $adminMail->Body = "New subscriber: <b>$email</b>";

                $adminMail->send();

            } catch (Exception $e) {
                // Admin email failed, still continue
            }

            // ===== Thank-you Email to Subscriber =====
            $subscriberMail = new PHPMailer(true);
            try {
                $subscriberMail->isSMTP();
                $subscriberMail->Host = 'smtp.gmail.com';
                $subscriberMail->SMTPAuth = true;
                $subscriberMail->Username = 'pojakusara@gmail.com';
                $subscriberMail->Password = 'kxnxpezhrshioqmx';
                $subscriberMail->SMTPSecure = 'tls';
                $subscriberMail->Port = 587;

                $subscriberMail->setFrom('pojakusara@gmail.com', 'PixelForge');
                $subscriberMail->addAddress($email);

                $subscriberMail->isHTML(true);
                $subscriberMail->Subject = 'Thank you for subscribing!';
                $subscriberMail->Body = "Hello,<br><br>Thank you for subscribing to the PixelForge newsletter!<br><br>Best regards,<br>PixelForge Team";

                $subscriberMail->send();

                echo "✅ Subscribed successfully! Emails sent.";

            } catch (Exception $e) {
                echo "✅ Subscribed successfully! But thank-you email failed: {$subscriberMail->ErrorInfo}";
            }

        } else {
            echo "⚠️ Error: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "❌ Please enter a valid email.";
    }
}
$conn->close();
?>
