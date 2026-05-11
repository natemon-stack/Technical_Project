<?php
session_start();
include 'connectdb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    
    if ($stmt->execute()) {
        echo "<p style='color:green;'>Signup successful! <a href='login.php'>Login here</a></p>";
    } else {
        echo "<p style='color:red;'>Error: Username may already exist.</p>";
    }
}
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Sign Up</h2>
    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>
        <label>Password:</label><br>
    
        <input type="password" name="password" required><br><br>
        <button type="submit">Sign Up</button>
    </form>
    <br>
    <a href="login.php">Already have an account? Login</a>
</div>
</body>
</html>
