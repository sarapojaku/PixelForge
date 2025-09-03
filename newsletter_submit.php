<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];

    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            echo "✅ Subscribed successfully!";
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
