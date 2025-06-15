<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Not authorized"]);
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "trackit_bc");
if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$query = "SELECT id, name, brand, description, currently_booked FROM equipment ORDER BY name ASC";
$result = mysqli_query($conn, $query);

$equipment = [];

while ($row = mysqli_fetch_assoc($result)) {
    $equipment[] = [
        "id" => $row['id'],
        "name" => $row['name'],
        "brand" => $row['brand'],
        "description" => $row['description'],
        "available" => $row['currently_booked'] ? false : true
    ];
}

header("Content-Type: application/json");
echo json_encode($equipment);
