<?php
session_start();

$conn = new mysqli("localhost", "root", "", "hardware_performance");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE username = ?"
    );

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header("Location: index.php");
            exit;

        } else {
            echo "Incorrect password";
        }

    } else {
        echo "User not found";
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

    <button type="submit">Login</button>

</form>

<br>
<a href="signup.php">Create an account</a>