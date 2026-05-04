<?php

include "connectdb.php";

$id = $_GET["id"];

// SQL Query
$sql = "SELECT * FROM user_systems WHERE id = '$id'";

$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>

    <title>Edit Hardware</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>

<h1>Edit Hardware System</h1>
<form action="update.php" method="post">
    <input  type="hidden" name="id"value=" <?php echo $row['id']; ?>">

    <label>CPU:</label><br>
    <select name="cpu_id">
        <?php
        $cpus = $conn->query("SELECT * FROM cpus");
        while($cpu = $cpus->fetch_assoc()) {
        ?>
            <option value="<?php echo $cpu['id']; ?>"
                <?php
                if ($cpu['id'] == $row['cpu_id']) {
                    echo "selected";
                }
                ?>
            >
                <?php echo $cpu['name']; ?>
            </option>

        <?php
        }
        ?>
    </select>
    <br><br>

    <label>GPU:</label><br>
    <select name="gpu_id"> 
        <?php
        $gpus = $conn->query("SELECT * FROM gpus");
        while($gpu = $gpus->fetch_assoc()) {
        ?>
            <option 
                value="<?php echo $gpu['id']; ?>"
                <?php
                if ($gpu['id'] == $row['gpu_id']) {
                    echo "selected";
                }
                ?>
            >
                <?php echo $gpu['name']; ?>
            </option>
        <?php
        }
        ?>

    </select>
    <br><br>

    <label>RAM:</label><br>
    <input type="number" name="ram_amount" value="<?php echo $row['ram_amount']; ?>">

    <br><br>
    <input type="submit" value="Update System">

</form>
</body>
</html>
