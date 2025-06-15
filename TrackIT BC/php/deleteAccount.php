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
$password = $_POST['confirm_delete_password'] ?? '';

if (empty($password)) {
    header("Location: ../accountDetails.php?delete_error=empty");
    exit;
}

// Get user's password hash
$result = mysqli_query($conn, "SELECT password FROM users WHERE id = $userId");
$row = mysqli_fetch_assoc($result);

if (!password_verify($password, $row['password'])) {
    header("Location: ../accountDetails.php?delete_error=wrong");
    exit;
}

// Delete user
mysqli_query($conn, "DELETE FROM users WHERE id = $userId");

// Destroy session
session_destroy();

// Redirect to login screen
header("Location: ../index.html");
exit;
?>
