<?php
session_start();

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['staff', 'admin'])) {
    header("Location: ../index.html");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "trackit_bc");

$requestId = intval($_POST['request_id'] ?? 0);
$action = $_POST['action'] ?? '';

if (!$requestId || !in_array($action, ['approve', 'decline'])) {
    header("Location: ../rentalRequests.php?error=invalid");
    exit;
}

// Get the equipment ID linked to this request
$get = mysqli_prepare($conn, "SELECT equipment_id FROM rental_requests WHERE id = ?");
mysqli_stmt_bind_param($get, "i", $requestId);
mysqli_stmt_execute($get);
$result = mysqli_stmt_get_result($get);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    header("Location: ../rentalRequests.php?error=notfound");
    exit;
}

$equipmentId = $row['equipment_id'];

if ($action === 'approve') {
    // Check if equipment is already booked
    $check = mysqli_prepare($conn, "SELECT currently_booked FROM equipment WHERE id = ?");
    mysqli_stmt_bind_param($check, "i", $equipmentId);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);
    $equipment = mysqli_fetch_assoc($result);

    if ($equipment && $equipment['currently_booked']) {
        // Already booked – don’t approve
        header("Location: ../rentalRequests.php?error=already_booked");
        exit;
    }

    // 1. Approve request
    $update = mysqli_prepare($conn, "UPDATE rental_requests SET status = 'approved' WHERE id = ?");
    mysqli_stmt_bind_param($update, "i", $requestId);
    mysqli_stmt_execute($update);

    // 2. Mark item as booked
    $book = mysqli_prepare($conn, "UPDATE equipment SET currently_booked = 1 WHERE id = ?");
    mysqli_stmt_bind_param($book, "i", $equipmentId);
    mysqli_stmt_execute($book);
} elseif ($action === 'decline') {
    // Just decline the request
    $decline = mysqli_prepare($conn, "UPDATE rental_requests SET status = 'declined' WHERE id = ?");
    mysqli_stmt_bind_param($decline, "i", $requestId);
    mysqli_stmt_execute($decline);
}

header("Location: ../rentalRequests.php?success=1");
exit;
