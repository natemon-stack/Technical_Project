<?php
include "connectdb.php";

$id = $_POST["id"];
$cpu_id = $_POST["cpu_id"];
$gpu_id = $_POST["gpu_id"];
$ram_amount = $_POST["ram_amount"];

$sql = "UPDATE user_systems SET cpu_id='$cpu_id',gpu_id='$gpu_id', ram_amount='$ram_amount' WHERE id='$id'";

if($conn->query($sql) === TRUE){
    echo "System updated successfully<br>";
    echo "<a href='display.php'>Back to System List</a>";
}else{
    echo "Error updating system: " . $conn->error;
}

?>
