<?php
include "connectdb.php";
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_id'])) die("Not logged in");
if ($_SERVER["REQUEST_METHOD"] != "POST") die("Invalid request");
if (!isset($_POST["id"]) || !isset($_POST["cpu_id"]) || !isset($_POST["gpu_id"]) || !isset($_POST["ram_amount"])) {
    die("Missing required fields");
}

$id = (int)$_POST["id"];
$cpu_id = (int)$_POST["cpu_id"];
$gpu_id = (int)$_POST["gpu_id"];
$ram_amount = (int)$_POST["ram_amount"];

$stmt = $conn->prepare("UPDATE user_systems SET cpu_id=?, gpu_id=?, ram_amount=? WHERE id=?");
$stmt->bind_param("iiii", $cpu_id, $gpu_id, $ram_amount, $id);

if ($stmt->execute()) {
    echo "<p>System updated successfully</p>";
    echo "<a href='display.php'>Back to System List</a>";
} else {
    echo "<p>Error updating system: " . htmlspecialchars($conn->error) . "</p>";
}
?>
