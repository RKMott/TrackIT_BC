<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.html");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "trackit_bc");
$id = intval($_POST['id'] ?? 0);

if ($id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM equipment WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}

header("Location: ../manageEquipment.php?success=deleted");
exit;
