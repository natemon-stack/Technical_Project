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
                ?>
                <option value="<?php echo $row['id']; ?>">
                    <?php echo $row['name']; ?>
                </option>
                <?php
            }
            ?>
        </select>

        <br>

        <label>GPU:</label>
        <select name="gpu_id" required>
            <?php
            $gpus = $conn->query("SELECT id, name FROM gpus");

            while($row = $gpus->fetch_assoc()) {
                ?>
                <option value="<?php echo $row['id']; ?>">
                    <?php echo $row['name']; ?>
                </option>
                <?php
            }
            ?>
        </select>

        <br>

        <button type="submit">Analyze System</button>

    </form>

    <div id="result" class="card"></div>

</div>

<script>
document.getElementById("hardwareForm").addEventListener("submit", function(e) {

    e.preventDefault();

    var formData = new FormData(this);

    fetch("analyze.php", {
        method: "POST",
        body: formData
    })
    .then(function(res) {
        return res.text();
    })
    .then(function(data) {
        document.getElementById("result").innerHTML = data;
    })
    .catch(function(err) {
        document.getElementById("result").innerHTML = "Error: " + err;
    });

});
</script>

</body>
</html>
