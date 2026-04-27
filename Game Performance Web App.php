<?php
$conn = new mysqli("localhost", "root", "", "hardware_performance");
?>

<!DOCTYPE html>
<html>
<head>
    <title>PC Upgrade Advisor</title>
</head>

<body>

<h1>PC Upgrade Advisor</h1>

<form id="hardwareForm">
    <label>CPU:</label>
    <select name="cpu_id" required>
        <?php
        $cpus = $conn->query("SELECT id, name FROM cpus");
        while($row = $cpus->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['name']}</option>";
        }
        ?>
    </select>

    <br><br>

    <label>GPU:</label>
    <select name="gpu_id" required>
        <?php
        $gpus = $conn->query("SELECT id, name FROM gpus");
        while($row = $gpus->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['name']}</option>";
        }
        ?>
    </select>

    <br><br>
    <button type="submit">Analyze System</button>
</form>

<hr>

<div id="result"></div>

<script>
document.getElementById("hardwareForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch("analyze.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        document.getElementById("result").innerHTML = data;
    })
    .catch(err => {
        document.getElementById("result").innerHTML = "Error: " + err;
    });
});
</script>

</body>
</html>