<?php
session_start();

// Connect to DB
$conn = mysqli_connect("localhost", "root", "", "trackit_bc");
if (!$conn) {
    exit("Database connection failed: " . mysqli_connect_error());
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Sanitize
$email = trim($email);

// Look for the user
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {
    // Check password
    if (password_verify($password, $user['password'])) {
        // Login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['first_name'] = $user['first_name'];

        echo "success";
        exit;
    }
}

echo "Invalid email or password.";
?>
