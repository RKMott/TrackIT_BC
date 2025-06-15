<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.html");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "trackit_bc");

$name = trim($_POST['name']);
$brand = trim($_POST['brand']);
$serial = trim($_POST['serial_number']);
$unit = trim($_POST['unit_number']);
$location = trim($_POST['location']);
$condition = trim($_POST['condition']);
$desc = trim($_POST['description']);

// Check if any field is empty
if (!$name || !$brand || !$serial || !$unit || !$location || !$condition || !$desc) {
    header("Location: ../manageEquipment.php?error=missing_fields");
    exit;
}

// Insert into DB
$stmt = mysqli_prepare($conn, "
    INSERT INTO equipment (name, brand, serial_number, unit_number, location, `condition`, description)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
mysqli_stmt_bind_param($stmt, "sssssss", $name, $brand, $serial, $unit, $location, $condition, $desc);
mysqli_stmt_execute($stmt);

header("Location: ../manageEquipment.php?success=added");
exit;
