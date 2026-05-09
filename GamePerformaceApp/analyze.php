<?php
include 'connectdb.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Invalid request");
}

if (
    !isset($_POST['cpu_id']) ||
    !isset($_POST['gpu_id']) ||
    !isset($_POST['ram_amount']) ||
    !isset($_POST['game_id'])
) {
    die("Missing input");
}

$cpu = (int)$_POST['cpu_id'];
$gpu = (int)$_POST['gpu_id'];
$ram = (int)$_POST['ram_amount'];
$gameId = (int)$_POST['game_id'];

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("INSERT INTO user_systems(user_id, cpu_id, gpu_id, ram_amount, game_id) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iiiii", $userId, $cpu, $gpu, $ram, $gameId);
$stmt->execute();


$result = $conn->query("
SELECT c.name AS cpu,c.overall_performance, c.gaming_performance,c.tier AS cpu_tier,c.gaming_upgrade_class,c.overall_upgrade_class, g.name AS gpu, g.relative_performance,g.tier AS gpu_tier,g.upgrade_class, u.ram_amount,u.ram_performance,(g.relative_performance - c.gaming_performance) AS gap FROM user_systems u

JOIN cpus c
ON u.cpu_id = c.id

JOIN gpus g
ON u.gpu_id = g.id

ORDER BY u.id DESC
LIMIT 1
");

$row = $result->fetch_assoc();

$gameQuery = $conn->prepare(" SELECT * FROM games WHERE id = ?");

$gameQuery->bind_param("i", $gameId);
$gameQuery->execute();
$gameResult = $gameQuery->get_result();
$game = $gameResult->fetch_assoc();

// CPU
$cpuName = $row['cpu'];
$cpuOverall = $row['overall_performance'];
$cpuGaming = $row['gaming_performance'];
$cpuTier = $row['cpu_tier'];
$cpuGameClass = $row['gaming_upgrade_class'];
$cpuOverallClass = $row['overall_upgrade_class'];

// GPU
$gpuName = $row['gpu'];
$gpuPerf = $row['relative_performance'];
$gpuTier = $row['gpu_tier'];
$gpuClass = $row['upgrade_class'];

// RAM
$ramAmount = $row['ram_amount'];
$ramPerformance = $row['ram_performance'];

// Gap
$gap = $row['gap'];

// Scores
$cpuScore = 0;
$gpuScore = 0;
$ramScore = 0;

$reasons = [];

// GPU check
if ($gpuPerf < 100) {
    $gpuScore += 2;
    $reasons[] = "GPU performance is low";
}
elseif ($gpuClass == 'Marginal Upgrade') {
    $gpuScore += 1;
    $reasons[] = "GPU is only a small upgrade";
}

// CPU check
if ($cpuGaming < 100) {
    $cpuScore += 2;
    $reasons[] = "CPU gaming performance is low";
}
elseif ($cpuGameClass == 'Marginal Upgrade') {
    $cpuScore += 1;
    $reasons[] = "CPU is only a small gaming upgrade";
}

// RAM check
if ($ramPerformance < 50) {
    $ramScore += 3;
    $reasons[] = "RAM amount is very low";
}
elseif ($ramPerformance < 100) {
    $ramScore += 1;
    $reasons[] = "RAM is below recommended amount";
}

// Bottleneck check
if ($gap > 20) {
    if (
        $cpuGaming < 100 ||
        $cpuTier == 'Lower Tier' ||
        $cpuTier == 'Midrange'
    ) {
        $cpuScore += 2;
        $reasons[] = "CPU bottleneck found";
    }
}
elseif ($gap < -20) {
    if (
        $gpuPerf < 100 ||
        $gpuTier == 'Lower Tier' ||
        $gpuTier == 'Midrange'
    ) {

        $gpuScore += 2;
        $reasons[] = "GPU bottleneck found";
    }
}

// Extra checks
if ($cpuTier == 'Lower Tier' && $gpuPerf > 100) {
    $cpuScore += 1;
    $reasons[] = "CPU is weaker than GPU";
}

if ($gpuTier == 'Lower Tier' && $cpuGaming > 100) {
    $gpuScore += 1;
    $reasons[] = "GPU is weaker than CPU";
}

// Final result
if (
    $cpuScore == 0 &&
    $gpuScore == 0 &&
    $ramScore == 0
) {
    $priority = "System is balanced and high-end";
}
elseif (
    $ramScore > $cpuScore &&
    $ramScore > $gpuScore
) {
    $priority = "Upgrade RAM first";

}
elseif ($gpuScore > $cpuScore) {
    $priority = "Upgrade GPU first";
}
elseif ($cpuScore > $gpuScore) {
    $priority = "Upgrade CPU first";

}
else {
    $priority = "System is balanced";

}

// GAME PERFORMANCE CHECK
$gameMessage = "";
if (
    $cpuGaming >= $game['recommended_cpu_score'] &&
    $gpuPerf >= $game['recommended_gpu_score'] &&
    $ramAmount >= $game['recommended_ram']
) {
    $gameMessage = "Your PC can run ".$game['game_name']." on high settings.";
}
elseif (
    $cpuGaming >= $game['minimum_cpu_score'] &&
    $gpuPerf >= $game['minimum_gpu_score'] &&
    $ramAmount >= $game['minimum_ram']
) {
    $gameMessage = "Your PC can run ".$game['game_name']." on medium settings.";

}
else {
    $gameMessage = "Your PC may struggle to run ".$game['game_name'];

}

// OUTPUT
echo "<h2>System Analysis</h2>";

echo "<strong>CPU:</strong> $cpuName<br>";
echo "Gaming: {$cpuGaming}% | Overall: {$cpuOverall}%<br>";
echo "Tier: $cpuTier<br><br>";
echo "<strong>GPU:</strong> $gpuName<br>";
echo "Performance: {$gpuPerf}%<br>";
echo "Tier: $gpuTier<br><br>";
echo "<strong>RAM:</strong> {$ramAmount}GB<br>";
echo "RAM Performance: {$ramPerformance}%<br><br>";

echo "<h3>Selected Game</h3>";
echo $game['game_name']."<br><br>";

echo "<h3>Game Result</h3>";
echo "<strong>$gameMessage</strong><br><br>";

echo "<h3>Recommendation</h3>";
echo "<strong>$priority</strong><br><br>";

echo "<h4>Reasons:</h4>";
echo "<ul>";

foreach ($reasons as $r) {
    echo "<li>$r</li>";

}
echo "</ul>";

$conn->close();
?>