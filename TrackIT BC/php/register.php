<<?php 
// Database connection (using mysqli as in teacher's examples)
$conn = mysqli_connect("localhost", "root", "", "trackit_bc");

// Check connection
if (!$conn) {
    exit("Database connection failed: " . mysqli_connect_error());
}

// Get form data (using teacher's variable naming style)
$vpassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
$vname = trim($_POST['first_name'] ?? '');
$vsurname = trim($_POST['last_name'] ?? '');
$vemail = trim($_POST['email'] ?? '');
$vrole = $_POST['role'] ?? 'student'; // default to student

// Basic validation
if (empty($vname) || empty($vsurname) || empty($vpassword) || empty($vemail)) {
    exit("Error: Name and Surname are required");
}

// Insert user
$sql = "INSERT INTO users (first_name, last_name, password, email, role)
        VALUES ('$vname', '$vsurname', '$vpassword', '$vemail', '$vrole')";

if (mysqli_query($conn, $sql)) {
    echo "Account created successfully.<br>";
    echo "Name: $vname<br>";
    echo "Surname: $vsurname<br>";
    echo "Email: $vemail<br>";
    echo "Role: $vrole";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>