<?php
// header('Content-Type: application/json'); // return JSON for AJAX

include 'db_connect.php'; // make sure this path is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and get form data
    $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
    $budget = isset($_POST['budget']) ? $conn->real_escape_string($_POST['budget']) : '';
    $message = isset($_POST['message']) ? $conn->real_escape_string($_POST['message']) : '';

    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
        exit;
    }

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, budget, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $budget, $message);

    if ($stmt->execute()) {
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