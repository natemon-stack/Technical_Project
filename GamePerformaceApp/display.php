<?php

include "connectdb.php";

// SQL Query
$sql = "SELECT user_systems.id, users.username, cpus.name AS cpu, gpus.name AS gpu, user_systems.ram_amount FROM user_systems
JOIN users
ON user_systems.user_id = users.id
JOIN cpus
ON user_systems.cpu_id = cpus.id
JOIN gpus
ON user_systems.gpu_id = gpus.id
";

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
    echo "<th>CPU</th>";
    echo "<th>GPU</th>";
    echo "<th>RAM</th>";
    echo "<th>Action</th>";
    echo "</tr>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "<td>" . $row["cpu"] . "</td>";
        echo "<td>" . $row["gpu"] . "</td>";
        echo "<td>" . $row["ram_amount"] . "GB</td>";
        echo "<td>
        <a href='edit.php?id=" . $row["id"] . "'>
        Edit
        </a>
        </td>";

        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No systems found";
}

?>

<br><br>
<a href="index.php">HomePage</a>

</body>
</html>