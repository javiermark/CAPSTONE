<?php
// Connect to DB
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "gch_login";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get and sanitize input
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if ($email === '' || $password === '') {
    echo "<script>alert('Email and password are required.'); window.location.href='register.html';</script>";
    exit();
}

// Check if user already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<script>alert('Email already registered.'); window.location.href='register.html';</script>";
    exit();
}
$stmt->close();

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $hashed_password);

if ($stmt->execute()) {
    echo "<script>alert('Registration successful. You can now log in.'); window.location.href='index.html';</script>";
} else {
    echo "<script>alert('Registration failed.'); window.location.href='register.html';</script>";
}

$stmt->close();
$conn->close();
?>
