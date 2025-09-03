<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<style>
body{font-family:sans-serif; padding:2rem; background:#f3f4f6;}
table{width:100%; border-collapse:collapse; margin-bottom:2rem;}
th, td{padding:0.8rem; border:1px solid #ddd; text-align:left;}
th{background:#2563eb; color:#fff;}
a.delete{color:red; text-decoration:none;}
a.logout{float:right; margin-bottom:1rem; background:#2563eb; color:#fff; padding:0.5rem 1rem; border-radius:6px;}
</style>
</head>
<body>
<h1>Admin Dashboard</h1>
<a href="admin_logout.php" class="logout">Logout</a>

<h2>Contact Messages</h2>
<table>
<tr>
<th>ID</th><th>Name</th><th>Email</th><th>Budget</th><th>Message</th><th>Date</th><th>Action</th>
</tr>
<?php
$result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
while($row = $result->fetch_assoc()){
    echo "<tr>
    <td>{$row['id']}</td>
    <td>{$row['name']}</td>
    <td>{$row['email']}</td>
    <td>{$row['budget']}</td>
    <td>{$row['message']}</td>
    <td>{$row['created_at']}</td>
    <td><a class='delete' href='delete.php?id={$row['id']}&table=contact_messages' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
    </tr>";
}
?>
</table>

<h2>Newsletter Subscribers</h2>
<table>
<tr>
<th>ID</th><th>Email</th><th>Date</th><th>Action</th>
</tr>
<?php
$result = $conn->query("SELECT * FROM newsletter_subscribers ORDER BY created_at DESC");
while($row = $result->fetch_assoc()){
    echo "<tr>
    <td>{$row['id']}</td>
    <td>{$row['email']}</td>
    <td>{$row['created_at']}</td>
    <td><a class='delete' href='delete.php?id={$row['id']}&table=newsletter_subscribers' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
    </tr>";
}
?>
</table>

</body>
</html>
