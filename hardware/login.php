<?php
session_start();
include "connectdb.php";

$username = $password = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    if (empty($username)) {
        $errors[] = "Username is required";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    }
    if (empty($errors)) {
        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row["password"])) {
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["username"] = $row["username"];
                header("Location: index.php");

                exit();

            } else {
                $errors[] = "Incorrect password";
            }

        } else {
            $errors[] = "User not found";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

</head>
<body>

<h2>Login</h2>

<?php
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
}
?>
<form method="POST" action="">
    <label>Username:</label><br>
    <input 
        type="text" 
        name="username"
        value="<?php echo $username; ?>"
    >

    <br><br>
    <label>Password:</label><br>
    <input 
        type="password" 
        name="password"
    >
    <br><br>
    <button type="submit">
        Login
    </button>
</form>

<br>
<a href="signup.php">
    Create an account

</a>

</body>
</html>
