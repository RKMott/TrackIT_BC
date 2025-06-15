<?php

session_start();
$conn = mysqli_connect("localhost", "root", "", "trackit_bc");
if (!$conn) {
    exit("DB connection failed: " . mysqli_connect_error());
}

$mode = $_POST['mode'] ?? '';

if ($mode === "login") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

	if ($user && password_verify($password, $user['password'])) {
		$_SESSION['user_id'] = $user['id'];
		$_SESSION['role'] = $user['role'];
		$_SESSION['first_name'] = $user['first_name'];
		echo "success"; // JS will now look for this
		exit;
	} else {
        echo "Invalid email or password";
    }
}
elseif ($mode === "register") {
    $fname = trim($_POST['first_name'] ?? '');
    $lname = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'student';

    if ($password !== $confirm) {
        echo "<Passwords do not match.";
        exit;
    }

    // Check if email already exists
    $check_sql = "SELECT id FROM users WHERE email = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $email);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_fetch_assoc($check_result)) {
        echo "An account with that email already exists.";
        exit;
    }

    // Insert new user
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (first_name, last_name, email, password, role)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $fname, $lname, $email, $hashed, $role);

    if (mysqli_stmt_execute($stmt)) {
        echo "Account created successfully!";
    } else {
        echo "Failed to create account.";
    }
}

else {
    echo "<script>alert('Invalid mode.'); window.history.back();</script>";
}
?>
