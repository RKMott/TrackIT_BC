<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

$firstName = $_SESSION['first_name'];
$role = $_SESSION['role'];
$userId = $_SESSION['user_id'];

$conn = mysqli_connect("localhost", "root", "", "trackit_bc");
$query = mysqli_query($conn, "SELECT first_name, last_name, email FROM users WHERE id = $userId");
$user = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Details</title>
    <link rel="stylesheet" href="styles/shared.css">
    <link rel="stylesheet" href="styles/accountDetails.css">
</head>
<body>
	
	<nav>
		<img class="logo" src="images/App Dev Logo.png" alt="TrackIT BC Logo">
	</nav>
	
    <section class="mainContent">
        <div class="boxGreen accountDetails">
            <h2>Account Details</h2>
			
			<?php if (isset($_GET['success']) && $_GET['success'] === 'updated'): ?>
  				<p style="color: green;">Account details updated successfully!</p>
			<?php elseif (isset($_GET['error'])): ?>
    			<?php if ($_GET['error'] === 'empty'): ?>
        			<p style="color: red;">All fields are required.</p>
    			<?php elseif ($_GET['error'] === 'emailtaken'): ?>
        			<p style="color: red;">This email is already in use by another account.</p>
    			<?php endif; ?>
			<?php endif; ?>

			
            <form action="php/updateAccount.php" method="POST">
                <label>First Name</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>

                <label>Last Name</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

                <button type="submit">Save Changes</button>
            </form>

            <div class="section">
                <button id="changePasswordBtn">Change Password</button>
                <div id="changePass" class="hidden">
					
					<?php if (isset($_GET['pass_success'])): ?>
    					<p style="color: green;">Password updated successfully!</p>
					<?php elseif (isset($_GET['pass_error'])): ?>
    					<?php if ($_GET['pass_error'] === 'empty'): ?>
        					<p style="color: red;">All fields are required.</p>
    					<?php elseif ($_GET['pass_error'] === 'nomatch'): ?>
        					<p style="color: red;">New passwords do not match.</p>
    					<?php elseif ($_GET['pass_error'] === 'wrongcurrent'): ?>
        					<p style="color: red;">Current password is incorrect.</p>
    					<?php endif; ?>
					<?php endif; ?>
					
                    <form action="php/changePassword.php" method="POST">
                        <label>Current Password</label>
                        <input type="password" name="current_password" required>

                        <label>New Password</label>
                        <input type="password" name="new_password" required>

                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" required>

                        <button type="submit">Update Password</button>
                    </form>
                </div>
            </div>

            <div class="section">
                <button id="deleteAccountBtn">Delete Account</button>
                <div id="deleteConfirm" class="hidden">
                    <p class="danger-text">
                        WARNING: This action will permanently delete your account and all related data.<br>
                        Are you sure you want to continue?
                    </p>
					
					<?php if (isset($_GET['delete_error'])): ?>
    					<?php if ($_GET['delete_error'] === 'empty'): ?>
        					<p class="danger-text">Please enter your password to confirm.</p>
    					<?php elseif ($_GET['delete_error'] === 'wrong'): ?>
        					<p class="danger-text">Password incorrect. Account not deleted.</p>
    					<?php endif; ?>
					<?php endif; ?>

					
                    <form action="php/deleteAccount.php" method="POST">
                        <label>Enter your password to confirm</label>
                        <input type="password" name="confirm_delete_password" required>
                        <button class="danger-text" type="submit">Confirm Delete</button>
                    </form>
                </div>
            </div>

            <br><br>
            <button onclick="window.location.href='mainMenu.php'">Back to Main Menu</button>
        </div>
    </section>

    <script src="js/accountDetails.js"></script>
</body>
</html>

