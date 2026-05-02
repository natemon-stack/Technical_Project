<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "hardware_performance"
);

$userId = (int)$_SESSION['user_id'];

// Get latest saved system
$latestSystem = $conn->query(
"
SELECT *
FROM user_systems
WHERE user_id = $userId
ORDER BY id DESC
LIMIT 1
"
);

$selectedCPU = "";
$selectedGPU = "";
$selectedRAM = "";

if ($latestSystem->num_rows > 0) {

    $system = $latestSystem->fetch_assoc();

    $selectedCPU = $system['cpu_id'];
    $selectedGPU = $system['gpu_id'];
    $selectedRAM = $system['ram_amount'];
}

?>

<!DOCTYPE html>

<html>
<head>

    <title>PC Upgrade Advisor</title>

</head>

<body>

<a href="logout.php">Logout</a>

<h1>PC Upgrade Advisor</h1>

<form id="hardwareForm">

    <!-- CPU -->

    <label>CPU:</label>

    <select name="cpu_id" required>

        <option value="">Select CPU</option>

        <?php

        $cpus = $conn->query(
            "SELECT id, name FROM cpus"
        );

        while($row = $cpus->fetch_assoc()) {

            $selected = (
                $row['id'] == $selectedCPU
            )
            ? ' selected'
            : '';

            $name = htmlspecialchars(
                $row['name'],
                ENT_QUOTES,
                'UTF-8'
            );

            echo "
            <option value='{$row['id']}'{$selected}>
                {$name}
            </option>
            ";
        }

        ?>

    </select>

    <br><br>

    <!-- GPU -->

    <label>GPU:</label>

    <select name="gpu_id" required>

        <option value="">Select GPU</option>

        <?php

        $gpus = $conn->query(
            "SELECT id, name FROM gpus"
        );

        while($row = $gpus->fetch_assoc()) {

            $selected = (
                $row['id'] == $selectedGPU
            )
            ? ' selected'
            : '';

            $name = htmlspecialchars(
                $row['name'],
                ENT_QUOTES,
                'UTF-8'
            );

            echo "
            <option value='{$row['id']}'{$selected}>
                {$name}
            </option>
            ";
        }

        ?>

    </select>

    <br><br>

    <!-- RAM -->

    <label>RAM:</label>

    <select name="ram_amount" required>

        <option value="">Select RAM</option>

        <?php

        $ramOptions = [8, 16, 24, 32, 48, 64];

        foreach ($ramOptions as $ramOption) {

            $selected = (
                $ramOption == $selectedRAM
            )
            ? ' selected'
            : '';

            echo "
            <option value='{$ramOption}'{$selected}>
                {$ramOption}GB
            </option>
            ";
        }

        ?>

    </select>

    <br><br>

    <button type="submit">
        Analyze System
    </button>

</form>

<hr>

<div id="result"></div>

<script>

document
.getElementById("hardwareForm")
.addEventListener("submit", function(e) {

    e.preventDefault();

    let formData = new FormData(this);

    fetch("analyze.php", {

        method: "POST",

        body: formData

    })

    .then(res => res.text())

    .then(data => {

        document
        .getElementById("result")
        .innerHTML = data;

    })

    .catch(err => {

        document
        .getElementById("result")
        .innerHTML = "Error: " + err;

    });

});

</script>

</body>
</html>