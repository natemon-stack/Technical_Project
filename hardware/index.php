<?php
session_start();
include 'connectdb.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM user_systems WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$selectedCPU = $selectedGPU = $selectedRAM = "";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $selectedCPU = $row['cpu_id'];
    $selectedGPU = $row['gpu_id'];
    $selectedRAM = $row['ram_amount'];
}
?>
?>

<!DOCTYPE html>
<html>
<head>
    <title>PC Upgrade Advisor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <a href="display.php">View Saved Systems</a> | <a href="logout.php">Logout</a>
    <h1>PC Upgrade Advisor</h1>
    <form id="hardwareForm" class="card">
        <label>Game:</label>
        <select name="game_id" required>
            <option value="">Select Game</option>
            <?php
            $games = $conn->query("SELECT id, name FROM games");
            while($g = $games->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($g['id']) . "'>" . htmlspecialchars($g['name']) . "</option>";
            }
            ?>
        </select>
        <label>CPU:</label>
        <select name="cpu_id" required>
            <option value="">Select CPU</option>
            <?php
            $cpus = $conn->query("SELECT * FROM cpus");
            while($c = $cpus->fetch_assoc()) {
                $selected = ($c['id'] == $selectedCPU) ? "selected" : "";
                echo "<option value='" . htmlspecialchars($c['id']) . "' $selected>" . htmlspecialchars($c['name']) . "</option>";
            }
            ?>
        </select>
        <label>GPU:</label>
        <select name="gpu_id" required>
            <option value="">Select GPU</option>
            <?php
            $gpus = $conn->query("SELECT * FROM gpus");
            while($g = $gpus->fetch_assoc()) {
                $selected = ($g['id'] == $selectedGPU) ? "selected" : "";
                echo "<option value='" . htmlspecialchars($g['id']) . "' $selected>" . htmlspecialchars($g['name']) . "</option>";
            }
            ?>
        </select>
        <label>RAM:</label>
        <select name="ram_amount" required>
            <option value="">Select RAM</option>
            <?php
            $ramOptions = [8, 16, 24, 32, 48, 64];
            foreach($ramOptions as $r) {
                $selected = ($r == $selectedRAM) ? "selected" : "";
                echo "<option value='$r' $selected>{$r}GB</option>";
            }
            ?>
        </select>
        <button type="submit">Analyze System</button>
    </form>
    <div id="result" class="card"></div>
</div>
<script>
document.getElementById("hardwareForm").addEventListener("submit", function(e) {
    e.preventDefault();
    fetch("analyze.php", {method: "POST", body: new FormData(this)})
        .then(res => res.text())
        .then(data => document.getElementById("result").innerHTML = data)
        .catch(err => document.getElementById("result").innerHTML = "Error: " + err);
});
</script>

</script>

</body>
</html>