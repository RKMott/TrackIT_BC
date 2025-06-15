<?php
session_start();

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['staff', 'admin'])) {
    header("Location: index.html");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "trackit_bc");
if (!$conn) {
    die("Database connection failed.");
}

// Fetch all rental requests (you can filter only 'pending' if desired)
$query = "
    SELECT rr.id, rr.status, rr.start_date, rr.return_date,
           u.first_name, u.last_name, e.name AS equipment_name
    FROM rental_requests rr
    JOIN users u ON rr.user_id = u.id
    JOIN equipment e ON rr.equipment_id = e.id
    ORDER BY rr.request_date DESC
";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rental Requests</title>
    <link rel="stylesheet" href="styles/shared.css">
    <link rel="stylesheet" href="styles/rentalRequests.css">
</head>
<body>
    <nav>
        <img class="logo" src="images/App Dev Logo.png" alt="TrackIT BC Logo">
    </nav>

    <section class="mainContent">
        <div class="boxGreen rentalRequests">
            <h2>Rental Requests</h2>
			
			<?php if (isset($_GET['error']) && $_GET['error'] === 'already_booked'): ?>
    			<p style="color:red; font-weight:bold;">This item is already booked and cannot be approved again.</p>
			<?php endif; ?>

			
            <table>
                <thead>
                    <tr>
                        <th>Equipment</th>
                        <th>Requested By</th>
                        <th>Start Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['equipment_name']) ?></td>
                            <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                            <td><?= htmlspecialchars($row['start_date']) ?></td>
                            <td><?= htmlspecialchars($row['return_date']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td>
                                <?php if ($row['status'] === 'pending'): ?>
                                    <form action="php/processRentalAction.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit">Approve</button>
                                    </form>
                                    <form action="php/processRentalAction.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="action" value="decline">
                                        <button type="submit">Decline</button>
                                    </form>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <br>
            <button onclick="window.location.href='mainMenu.php'">Go Back</button>
        </div>
    </section>
</body>
</html>
