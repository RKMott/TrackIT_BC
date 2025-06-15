<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Unauthorized";
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "trackit_bc");
if (!$conn) {
    http_response_code(500);
    echo "Database connection failed.";
    exit;
}

$userId = $_SESSION['user_id'];
$itemIds = $_POST['item_ids'] ?? [];
$rentalDays = $_POST['rental_days'] ?? [];

if (empty($itemIds)) {
    http_response_code(400);
    echo "No items submitted.";
    exit;
}

date_default_timezone_set("Europe/London"); // Change to your timezone
$today = date("Y-m-d");

// Insert each item request
foreach ($itemIds as $equipmentId) {
    $equipmentId = intval($equipmentId);
    $days = intval($rentalDays[$equipmentId] ?? 1);

    $startDate = $today;
    $returnDate = date("Y-m-d", strtotime("+$days days"));

    $stmt = mysqli_prepare($conn, "
        INSERT INTO rental_requests (user_id, equipment_id, status, start_date, return_date)
        VALUES (?, ?, 'pending', ?, ?)
    ");
    mysqli_stmt_bind_param($stmt, "iiss", $userId, $equipmentId, $startDate, $returnDate);
    mysqli_stmt_execute($stmt);
}

echo "Rental request submitted.";
