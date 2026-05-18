<?php
session_start();
include 'connectdb.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

$result = $conn->query("SELECT * FROM user_systems WHERE user_id = $userId ORDER BY id DESC LIMIT 1");

$selectedCPU = "";
$selectedGPU = "";
$selectedRAM = "";

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $selectedCPU = $row['cpu_id'];
    $selectedGPU = $row['gpu_id'];
    $selectedRAM = $row['ram_amount'];

}
?>

<!DOCTYPE html>
<html>
<head>
<title>PC Upgrade Advisor</title>
<link rel="stylesheet" href="style.css">

</head>
<body>
<body>

<div class="container">
<div class="centerslide">

    <img src="images/gamepic1.jpg">
    <img src="images/gamepic2.jpg">
    <img src="images/gamepic3.jpg">
    <img src="images/gamepic4.jpg">
</div>

    <a href="display.php">View Saved Systems</a>
    <br><br>

    <a href="logout.php">Logout</a>
    
    <h1>PC Upgrade Advisor</h1>
    <form id="hardwareForm" class="card">

    <label>Game:</label>
        <select name="game_id" required>
        <option value="">Select Game</option>

        <?php
            $games = $conn->query("SELECT * FROM games");
            while($row = $games->fetch_assoc()) {
        ?>

        <option value="<?php echo $row['game_id']; ?>">
            <?php echo $row['game_name']; ?>
            </option>

            <?php
            }
            ?>

        </select>
        <br>

    
    <label>CPU:</label>
        <select name="cpu_id" required>
        <option value="">Select CPU</option>

        <?php
            $cpus = $conn->query("SELECT * FROM cpus");
            while($row = $cpus->fetch_assoc()) {
        ?>

        <option 
             value="<?php echo $row['id']; ?>"
            
            <?php
            if ($row['id'] == $selectedCPU) {
                echo "selected";
            }
            ?>
            >
        <?php echo $row['name']; ?>
        </option>

        <?php
         }
        ?>

        </select>
        <br>

    <label>GPU:</label>
        <select name="gpu_id" required>
        <option value="">Select GPU</option>

        <?php
            $gpus = $conn->query("SELECT * FROM gpus");
            while($row = $gpus->fetch_assoc()) {
        ?>

        <option 
            value="<?php echo $row['id']; ?>"

        <?php
            if ($row['id'] == $selectedGPU) {
                echo "selected";
            }
            ?>
            >
            <?php echo $row['name']; ?>
            </option>

            <?php
            }
            ?>
        </select>
        <br>

    <label>RAM:</label>
        <select name="ram_amount" required>
        <option value="">Select RAM</option>

            <option value="8"
                <?php if($selectedRAM == 8){ echo "selected"; } ?>>
                8GB
            </option>

            <option value="16"
                <?php if($selectedRAM == 16){ echo "selected"; } ?>>
                16GB
            </option>

            <option value="24"
                <?php if($selectedRAM == 24){ echo "selected"; } ?>>
                24GB
            </option>

            <option value="32"
                <?php if($selectedRAM == 32){ echo "selected"; } ?>>
                32GB
            </option>

            <option value="48"
                <?php if($selectedRAM == 48){ echo "selected"; } ?>>
                48GB
            </option>

            <option value="64"
                <?php if($selectedRAM == 64){ echo "selected"; } ?>>
                64GB
            </option>

        </select>
        <br><br>

        <button type="submit">Analyze System</button>

    </form>
    <div id="result" class="card"></div>
</div>

<script>
document.getElementById("hardwareForm").addEventListener("submit", function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    fetch("analyze.php", {method: "POST", body: formData})

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
