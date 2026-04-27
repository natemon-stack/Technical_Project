<?php
include 'connectdb.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>PC Upgrade Advisor</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <h1>PC Upgrade Advisor</h1>

    <form id="hardwareForm" class="card">

        <label>CPU:</label>
        <select name="cpu_id" required>
            <?php
            $cpus = $conn->query("SELECT id, name FROM cpus");
            while($row = $cpus->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select>

        <label>GPU:</label>
        <select name="gpu_id" required>
            <?php
            $gpus = $conn->query("SELECT id, name FROM gpus");
            while($row = $gpus->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select>

        <button type="submit">Analyze System</button>
    </form>

    <div id="result" class="card result-box"></div>

</div>

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
