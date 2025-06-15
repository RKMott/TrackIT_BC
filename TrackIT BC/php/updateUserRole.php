<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.html");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "trackit_bc");
$userId = intval($_POST['user_id'] ?? 0);
$newRole = $_POST['role'] ?? '';

if (!in_array($newRole, ['student', 'staff', 'admin'])) {
    header("Location: ../userAccounts.php?error=invalidrole");
    exit;
}

$update = mysqli_prepare($conn, "UPDATE users SET role = ? WHERE id = ?");
mysqli_stmt_bind_param($update, "si", $newRole, $userId);
mysqli_stmt_execute($update);

header("Location: ../userAccounts.php?success=role_updated");
exit;
