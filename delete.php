<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $table = $_GET['table'] ?? 'contact_messages'; // default table

    $allowed_tables = ['contact_messages', 'newsletter_subscribers'];
    if (!in_array($table, $allowed_tables)) {
        die('Invalid table');
    }

    $stmt = $conn->prepare("DELETE FROM $table WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: admin_dashboard.php");
exit;
?>
