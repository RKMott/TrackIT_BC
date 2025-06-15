<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Equipment / Furniture List</title>
    <link rel="stylesheet" href="styles/shared.css">
    <link rel="stylesheet" href="styles/equipmentList.css">
</head>
<body>
    <nav>
        <img class="logo" src="images/App Dev Logo.png" alt="TrackIT BC Logo">
    </nav>

    <section class="mainContent flex-row">
        <!-- Left: Equipment Table -->
        <div class="boxGreen equipmentSection tableScroll">
            <div class="headerRow">
                <input type="text" id="searchBar" placeholder="Search by name, brand, or description...">
                <button onclick="window.location.href='mainMenu.php'" class="goBackBtn">Go Back</button>
            </div>

            <table id="equipmentTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Manufacturer</th>
                        <th>Availability</th>
                        <th>Description</th>
                        <th></th> <!-- Button column -->
                    </tr>
                </thead>
                <tbody id="equipmentBody">
                    <!-- JavaScript will populate this -->
                </tbody>
            </table>
        </div>

        <!-- Right: Rental Request List -->
        <div class="boxGreen rentalList">
            <h2>Rental Request List</h2>
            <ul id="requestList">
                <!-- Populated by JS -->
            </ul>
            <button id="submitRequestBtn">Submit Request</button>
        </div>
    </section>

    <script src="js/equipmentList.js"></script>
</body>
</html>

