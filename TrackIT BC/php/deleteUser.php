<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.html");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "trackit_bc");
$userId = intval($_POST['user_id'] ?? 0);

// Prevent deleting yourself
if ($userId === $_SESSION['user_id']) {
    header("Location: ../userAccounts.php?error=cant_delete_self");
    exit;
}

$update = mysqli_prepare($conn, "UPDATE users SET deleted = 1 WHERE id = ?");
mysqli_stmt_bind_param($update, "i", $userId);
mysqli_stmt_execute($update);

header("Location: ../userAccounts.php?success=user_deleted");
exit;
