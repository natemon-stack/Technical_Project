<?php
include "connectdb.php";

// SQL Query
$sql = "SELECT user_systems.id AS id,users.username,cpus.name AS cpu_id, gpus.name AS gpu_id, games.game_name, user_systems.ram_amount 
        FROM user_systems LEFT JOIN users 
        ON user_systems.user_id = users.id LEFT JOIN cpus 
        ON user_systems.cpu_id = cpus.id LEFT JOIN gpus 
        ON user_systems.gpu_id = gpus.id LEFT JOIN games 
        ON user_systems.game_id = games.game_id";

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
    echo "<table border='1'>";

    echo "<tr>";
    echo "<th>System ID</th>";
    echo "<th>Username</th>";
    echo "<th>Game</th>";
    echo "<th>CPU</th>";
    echo "<th>GPU</th>";
    echo "<th>RAM</th>";
    echo "<th>Action</th>";
    echo "</tr>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "<td>" . $row["game_name"] . "</td>";
        echo "<td>" . $row["cpu_id"] . "</td>";
        echo "<td>" . $row["gpu_id"] . "</td>";
        echo "<td>" . $row["ram_amount"] . "GB</td>";
        echo "<td>
       
        <a href='edit.php?id=".$row["id"] . "'> Edit</a>
        </td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No systems found";
}
?>
<br><br>
<a href="index.php">Homepage</a>

</div>
</body>
</html>
