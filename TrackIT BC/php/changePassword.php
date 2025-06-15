<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.html");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "trackit_bc");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$userId = $_SESSION['user_id'];

$current = $_POST['current_password'] ?? '';
$new = $_POST['new_password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (empty($current) || empty($new) || empty($confirm)) {
    header("Location: ../accountDetails.php?pass_error=empty");
    exit;
}

if ($new !== $confirm) {
    header("Location: ../accountDetails.php?pass_error=nomatch");
    exit;
}

// Get current password from DB
$result = mysqli_query($conn, "SELECT password FROM users WHERE id = $userId");
$row = mysqli_fetch_assoc($result);

if (!password_verify($current, $row['password'])) {
    header("Location: ../accountDetails.php?pass_error=wrongcurrent");
    exit;
}

// Hash and update new password
$hashed = password_hash($new, PASSWORD_DEFAULT);
$update = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE id = ?");
mysqli_stmt_bind_param($update, "si", $hashed, $userId);
mysqli_stmt_execute($update);

header("Location: ../accountDetails.php?pass_success=1");
exit;
?>
