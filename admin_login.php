<?php
session_start();
include 'db_connect.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Hardcoded credentials (you can hash passwords later)
    if ($username === "Admin" && $password === "Adminpass.24") {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>
<style>
body{font-family:sans-serif; display:flex; justify-content:center; align-items:center; height:100vh; background:#f3f4f6;}
form{background:#fff; padding:2rem; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,0.1);}
input{display:block; width:100%; padding:0.7rem; margin-bottom:1rem; border-radius:8px; border:1px solid #ddd;}
button{padding:0.7rem 1.5rem; background:#2563eb; color:#fff; border:none; border-radius:8px; cursor:pointer; margin-left: 50px;}
.message{color:red; margin-bottom:1rem;}
</style>
</head>
<body>
<form method="post">
<h2>Admin Login</h2>
<?php if($message) echo "<div class='message'>$message</div>"; ?>
<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit">Login</button>
</form>
</body>
</html>
