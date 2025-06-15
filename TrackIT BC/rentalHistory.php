<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "trackit_bc");
$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Get search input
$search = trim($_GET['search'] ?? '');

// Base SQL
$sql = "
    SELECT rr.*, e.name AS equipment_name, u.first_name, u.last_name
    FROM rental_requests rr
    JOIN equipment e ON rr.equipment_id = e.id
    JOIN users u ON rr.user_id = u.id
";

// Apply role-based filtering
if ($role !== 'admin') {
    $sql .= " WHERE rr.user_id = $userId";
} else {
    $sql .= " WHERE 1";
}

// Apply search filter
if ($search !== '') {
    $searchSafe = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (
        e.name LIKE '%$searchSafe%' OR
        u.first_name LIKE '%$searchSafe%' OR
        u.last_name LIKE '%$searchSafe%' OR
        rr.status LIKE '%$searchSafe%'
    )";
}

$sql .= " ORDER BY rr.request_date DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rental History</title>
    <link rel="stylesheet" href="styles/shared.css">
    <link rel="stylesheet" href="styles/rentalHistory.css">
</head>
<body>
    <nav>
        <img class="logo" src="images/App Dev Logo.png" alt="TrackIT BC Logo">
    </nav>

    <section class="mainContent">
        <div class="boxGreen rentalHistory">
            <h2>Rental History</h2>

            <form method="GET" style="margin-bottom: 15px;">
                <input type="text" name="search" placeholder="Search by name, status, etc..." value="<?= htmlspecialchars($search) ?>" style="padding: 8px; width: 60%;">
                <button type="submit">Search</button>
                <button type="button" onclick="window.location='rentalHistory.php'">Clear</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Equipment</th>
                        <?php if ($role === 'admin'): ?>
                            <th>User</th>
                        <?php endif; ?>
                        <th>Start Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['equipment_name']) ?></td>
                                <?php if ($role === 'admin'): ?>
                                    <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                                <?php endif; ?>
                                <td><?= htmlspecialchars($row['start_date']) ?></td>
                                <td><?= htmlspecialchars($row['return_date']) ?></td>
                                <td class="status <?= $row['status'] ?>"><?= htmlspecialchars(ucfirst($row['status'])) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="<?= $role === 'admin' ? 5 : 4 ?>">No results found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <br>
            <button onclick="window.location.href='mainMenu.php'">Go Back</button>
        </div>
    </section>
</body>
</html>
