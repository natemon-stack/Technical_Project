<?php
session_start();

$conn = new mysqli("localhost", "root", "", "hardware_performance");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO users (username, password) VALUES (?, ?)"
    );

    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        echo "Signup successful";
    } else {
        echo "Username already exists";
    }
}
?>

<form method="POST">

    Username:<br>
    <input type="text" name="username" required>

    <br><br>

    Password:<br>
    <input type="password" name="password" required>

    <br><br>

    <button type="submit">Sign Up</button>

</form>

<br>
<a href="login.php">Already have an account? Login</a>