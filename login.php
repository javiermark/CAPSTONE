<?php
session_start();
include 'db.php'; // This connects to your gch_login DB

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row['email'];

            // Optional: show message briefly before redirecting
            echo "<script>
                alert('Login successful!');
                window.location.href = 'Admindb.html';
            </script>";
            exit();
        } else {
            echo "<script>alert('Invalid password.'); window.location.href = 'index.html';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Email not found.'); window.location.href = 'index.html';</script>";
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
