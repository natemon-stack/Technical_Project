<?php
include "connectdb.php";
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if (!isset($_GET["id"])) die("System ID not provided");

$id = (int)$_GET["id"];
$stmt = $conn->prepare("SELECT * FROM user_systems WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Hardware</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Edit Hardware System</h1>
    <form action="update.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
        <label>CPU:</label>
        <select name="cpu_id">
            <?php
            $cpus = $conn->query("SELECT * FROM cpus");
            while($cpu = $cpus->fetch_assoc()) {
                $selected = ($cpu['id'] == $row['cpu_id']) ? "selected" : "";
                echo "<option value='" . htmlspecialchars($cpu['id']) . "' $selected>" . htmlspecialchars($cpu['name']) . "</option>";
            }
            ?>
        </select>
        <label>GPU:</label>
        <select name="gpu_id">
            <?php
            $gpus = $conn->query("SELECT * FROM gpus");
            while($gpu = $gpus->fetch_assoc()) {
                $selected = ($gpu['id'] == $row['gpu_id']) ? "selected" : "";
                echo "<option value='" . htmlspecialchars($gpu['id']) . "' $selected>" . htmlspecialchars($gpu['name']) . "</option>";
            }
            ?>
        </select>
        <label>RAM:</label>
        <input type="number" name="ram_amount" value="<?php echo htmlspecialchars($row['ram_amount']); ?>">
        <label>Game:</label>
        <select name="game_id">
            <?php
            $games = $conn->query("SELECT * FROM games ORDER BY name");
            while($game = $games->fetch_assoc()) {
                $selected = ($game['id'] == $row['game_id']) ? "selected" : "";
                echo "<option value='" . htmlspecialchars($game['id']) . "' $selected>" . htmlspecialchars($game['name']) . "</option>";
            }
            ?>
        </select>
        <button type="submit">Update System</button>
    </form>
</div>
</body>
</html>
