<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.html");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "trackit_bc");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$userId = $_SESSION['user_id'];

$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');

if (empty($first_name) || empty($last_name) || empty($email)) {
    header("Location: ../accountDetails.php?error=empty");
    exit;
}

// Check if email is used by another account
$checkEmail = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? AND id != ?");
mysqli_stmt_bind_param($checkEmail, "si", $email, $userId);
mysqli_stmt_execute($checkEmail);
mysqli_stmt_store_result($checkEmail);

if (mysqli_stmt_num_rows($checkEmail) > 0) {
    header("Location: ../accountDetails.php?error=emailtaken");
    exit;
}

// Update user info
$update = mysqli_prepare($conn, "UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?");
mysqli_stmt_bind_param($update, "sssi", $first_name, $last_name, $email, $userId);
mysqli_stmt_execute($update);

// Update session name for UI greeting
$_SESSION['first_name'] = $first_name;

// Redirect with success
header("Location: ../accountDetails.php?success=updated");
exit;
?>
