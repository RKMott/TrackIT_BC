<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "trackit_bc");

$items = mysqli_query($conn, "SELECT * FROM equipment ORDER BY name ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add/Remove Equipment</title>
    <link rel="stylesheet" href="styles/shared.css">
    <link rel="stylesheet" href="styles/manageEquipment.css">
</head>
<body>
    <nav>
        <img class="logo" src="images/App Dev Logo.png" alt="TrackIT BC Logo">
    </nav>

    <section class="mainContent">
        <div class="boxGreen tableScroll manageEquipment">
            <h2>Add New Equipment</h2>
            <form action="php/addEquipment.php" method="POST" class="addForm">
                <input type="text" name="name" placeholder="Name" required>
                <input type="text" name="brand" placeholder="Brand" required>
                <input type="text" name="serial_number" placeholder="Serial Number" required>
                <input type="text" name="unit_number" placeholder="Unit Number" required>
                <input type="text" name="location" placeholder="Location" required>
                <input type="text" name="condition" placeholder="Condition" required>
                <textarea name="description" placeholder="Description" required></textarea>
                <button type="submit">Add Equipment</button>
            </form>

            <h2>Current Equipment List</h2>
			
			<div class="tablescroll">
            	<table>
            	    <thead>
            	        <tr>
            	            <th>Name</th>
            	            <th>Brand</th>
            	            <th>Serial No.</th>
            	            <th>Location</th>
            	            <th>Condition</th>
            	            <th>Actions</th>
            	        </tr>
            	    </thead>
            	    <tbody>
            	        <?php while ($row = mysqli_fetch_assoc($items)): ?>
            	            <tr>
            	                <td><?= htmlspecialchars($row['name']) ?></td>
            	                <td><?= htmlspecialchars($row['brand']) ?></td>
            	                <td><?= htmlspecialchars($row['serial_number']) ?></td>
            	                <td><?= htmlspecialchars($row['location']) ?></td>
            	                <td><?= htmlspecialchars($row['condition']) ?></td>
            	                <td>
            	                    <form action="php/deleteEquipment.php" method="POST" onsubmit="return confirm('Delete this item?');">
            	                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
            	                        <button type="submit" class="danger">Delete</button>
            	                    </form>
            	                </td>
            	            </tr>
            	        <?php endwhile; ?>
            	    </tbody>
            	</table>
			</div>

            <br>
            <button onclick="window.location.href='mainMenu.php'">Go Back</button>
        </div>
    </section>
</body>
</html>
