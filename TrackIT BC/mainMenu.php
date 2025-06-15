<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

$role = $_SESSION['role'] ?? 'student';
$firstName = $_SESSION['first_name'] ?? 'User';
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>TrackIT BC Main Menu</title>
    <link rel="stylesheet" href="styles/shared.css">
    <link rel="stylesheet" href="styles/mainMenu.css">
</head>
<body>
    <nav>
        <img class="logo" src="images/App Dev Logo.png" alt="TrackIT BC Logo">
    </nav>

    <section class="mainContent">
        <div class="boxGreen menu">
            <h2>Welcome back, <?php echo htmlspecialchars($firstName); ?>!</h2>

            <button onclick="location.href='equipmentList.php'">Equipment / Furniture List</button>
            <button onclick="location.href='rentalHistory.php'">Rental History</button>
            <button onclick="location.href='accountDetails.php'">Account Details</button>

            <?php if ($role === 'staff' || $role === 'admin'): ?>
                <button onclick="location.href='rentalRequests.php'">Rental Requests</button>
            <?php endif; ?>

            <?php if ($role === 'admin'): ?>
                <button onclick="location.href='userAccounts.php'">User Accounts</button>
                <button onclick="location.href='manageEquipment.php'">Add / Remove Product</button>
            <?php endif; ?>

            <button onclick="location.href='php/logout.php'">Sign Out</button>
        </div>
    </section>
</body>
</html>
