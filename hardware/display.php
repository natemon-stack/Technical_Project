<?php
include "connectdb.php";
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT u.id, users.username, c.name AS cpu_id, g.name AS gpu_id, gm.name AS game_name, u.ram_amount 
        FROM user_systems u 
        LEFT JOIN users ON u.user_id = users.id 
        LEFT JOIN cpus c ON u.cpu_id = c.id 
        LEFT JOIN gpus g ON u.gpu_id = g.id 
        LEFT JOIN games gm ON u.game_id = gm.id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Display Systems</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Saved Hardware Systems</h1>
    <?php
    if ($result->num_rows > 0) {
        echo "<table border='1'><tr><th>ID</th><th>User</th><th>Game</th><th>CPU</th><th>GPU</th><th>RAM</th><th>Action</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . htmlspecialchars($row["id"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["game_name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["cpu_id"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["gpu_id"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["ram_amount"]) . "GB</td>";
            echo "<td><a href='edit.php?id=" . htmlspecialchars($row["id"]) . "'>Edit</a></td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No systems found</p>";
    }
    ?>
    <br><a href="index.php">Back to Home</a>
</div>
</body>
</html>