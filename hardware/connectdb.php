<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hardware_performance"; 

//Creating an SQL connection
$conn = new mysqli($servername, $username, $password, $dbname);

//Testing SQL Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



?>
