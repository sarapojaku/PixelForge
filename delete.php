<?php
include 'db_connect.php';

if (isset($_GET['id']) && isset($_GET['table'])) {
    $id = intval($_GET['id']); // force integer
    $table = $_GET['table'];

    // ✅ allow only specific tables
    $allowed_tables = ['contact_messages', 'newsletter_subscribers'];
    if (!in_array($table, $allowed_tables)) {
        die('❌ Invalid table');
    }

    // ✅ prepare the query dynamically (table name cannot be bound, so we whitelist)
    $sql = "DELETE FROM $table WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // optional: show success before redirect
        // echo "✅ Record deleted successfully";
    } else {
        echo "⚠️ Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

// go back to dashboard
header("Location: admin_dashboard.php");
exit;
?>
