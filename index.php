<?php
include 'connectdb.php';

<?php
include 'db.php';

if (isset($_POST['save'])) {
    $cpu = $_POST['cpu'];
    $gpu = $_POST['gpu'];

    $conn->query("INSERT INTO user_systems (cpu_id, gpu_id) VALUES ($cpu, $gpu)");
}

$cpus = $conn->query("SELECT * FROM cpus");
$gpus = $conn->query("SELECT * FROM gpus");

$analysis = $conn->query("SELECT * FROM bottleneck_analysis");
$recommendations = $conn->query("SELECT * FROM system_recommendation");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hardware Analyzer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>PC Hardware Analyzer</h1>

<!-- FORM -->
<form method="POST">
    <h2>Select Your System</h2>

    <select name="cpu">
        <?php while($row = $cpus->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>">
                <?= $row['name'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <select name="gpu">
        <?php while($row = $gpus->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>">
                <?= $row['name'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <button name="save">Save System</button>
</form>

<hr>

<!-- BOTTLENECK -->
<h2>Bottleneck Analysis</h2>
<?php while($row = $analysis->fetch_assoc()): ?>
    <p>
        <?= $row['cpu'] ?> + <?= $row['gpu'] ?> → <?= $row['bottleneck'] ?>
    </p>
<?php endwhile; ?>

<hr>

<!-- RECOMMENDATIONS -->
<h2>Recommendations</h2>
<?php while($row = $recommendations->fetch_assoc()): ?>
    <p>
        <?= $row['cpu'] ?> + <?= $row['gpu'] ?> → <?= $row['recommendation'] ?>
    </p>
<?php endwhile; ?>

</body>
</html>
