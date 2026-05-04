<?php

include "connectdb.php";

// SQL Query
$sql = "SELECT  u.id,c.name AS cpu, g.name AS gpu,u.ram_amount FROM user_systems u
JOIN cpus c
ON u.cpu_id = c.id
JOIN gpus g
ON u.gpu_id = g.id
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Display Hardware Systems</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>

<h1>Saved Hardware Systems</h1>
<?php

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr>";
    echo "<th>System ID</th>";
    echo "<th>CPU</th>";
    echo "<th>GPU</th>";
    echo "<th>RAM</th>";
    echo "<th>Action</th>";
    echo "</tr>";
 
  while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
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

</body>
</html>
