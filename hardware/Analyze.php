<?php
session_start();

$conn = new mysqli("localhost", "root", "", "hardware_performance");

// Validate request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request");
}

// Validate inputs
if (
    !isset($_POST['cpu_id']) ||
    !isset($_POST['gpu_id']) ||
    !isset($_POST['ram_amount'])
) {
    die("Missing input");
}

// Extract values
$cpu = (int)$_POST['cpu_id'];
$gpu = (int)$_POST['gpu_id'];
$ram = (int)$_POST['ram_amount'];

$userId = $_SESSION['user_id'];

// Store system
$stmt = $conn->prepare(
"
INSERT INTO user_systems
(user_id, cpu_id, gpu_id, ram_amount)
VALUES (?, ?, ?, ?)
"
);

$stmt->bind_param(
"iiii",
$userId,
$cpu,
$gpu,
$ram
);

$stmt->execute();

// Get latest analyzed system
$result = $conn->query(
"
SELECT 

    c.name AS cpu,
    c.overall_performance,
    c.gaming_performance,
    c.tier AS cpu_tier,
    c.gaming_upgrade_class,
    c.overall_upgrade_class,

    g.name AS gpu,
    g.relative_performance,
    g.tier AS gpu_tier,
    g.upgrade_class,

    u.ram_amount,
    u.ram_performance,

    (g.relative_performance - c.gaming_performance) AS gap

FROM user_systems u

JOIN cpus c
ON u.cpu_id = c.id

JOIN gpus g
ON u.gpu_id = g.id

ORDER BY u.id DESC
LIMIT 1
"
);

$row = $result->fetch_assoc();

// Extract CPU values
$cpuName = $row['cpu'];

$cpuOverall = $row['overall_performance'];
$cpuGaming = $row['gaming_performance'];

$cpuTier = $row['cpu_tier'];

$cpuGameClass = $row['gaming_upgrade_class'];
$cpuOverallClass = $row['overall_upgrade_class'];

// Extract GPU values
$gpuName = $row['gpu'];

$gpuPerf = $row['relative_performance'];

$gpuTier = $row['gpu_tier'];

$gpuClass = $row['upgrade_class'];

// Extract RAM values
$ramAmount = $row['ram_amount'];
$ramPerformance = $row['ram_performance'];

// Gap
$gap = $row['gap'];

// Scores
$gpuScore = 0;
$cpuScore = 0;
$ramScore = 0;

$reasons = [];

// GPU Evaluation
if ($gpuPerf < 100) {

    $gpuScore += 2;

    $reasons[] = "GPU below RTX 5070 Ti baseline";

}
elseif ($gpuClass === 'Marginal Upgrade') {

    $gpuScore += 1;

    $reasons[] = "GPU only marginal improvement tier";
}

// CPU Evaluation
if ($cpuGaming < 100) {

    $cpuScore += 2;

    $reasons[] = "CPU gaming below 7800X3D baseline";

}
elseif ($cpuGameClass === 'Marginal Upgrade') {

    $cpuScore += 1;

    $reasons[] = "CPU gaming only marginal";
}

// RAM Evaluation
if ($ramPerformance < 50) {

    $ramScore += 3;

    $reasons[] = "Very low RAM capacity";

}
elseif ($ramPerformance < 100) {

    $ramScore += 1;

    $reasons[] = "RAM below 32GB baseline";
}

// SMART BOTTLENECK DETECTION

if ($gap > 20) {

    // Only count as real CPU bottleneck
    // if CPU is actually weak

    if (
        $cpuGaming < 100 ||
        $cpuTier === 'Lower Tier' ||
        $cpuTier === 'Midrange'
    ) {

        $cpuScore += 2;

        $reasons[] = "CPU bottleneck detected";
    }

}
elseif ($gap < -20) {

    // Only count as real GPU bottleneck
    // if GPU is actually weak

    if (
        $gpuPerf < 100 ||
        $gpuTier === 'Lower Tier' ||
        $gpuTier === 'Midrange'
    ) {

        $gpuScore += 2;

        $reasons[] = "GPU bottleneck detected";
    }
}

// Tier Awareness
if (
    $cpuTier === 'Lower Tier' &&
    $gpuPerf > 100
) {

    $cpuScore += 1;

    $reasons[] = "Low-tier CPU with strong GPU";
}

if (
    $gpuTier === 'Lower Tier' &&
    $cpuGaming > 100
) {

    $gpuScore += 1;

    $reasons[] = "Low-tier GPU with strong CPU";
}

// Final Decision

if (
    $cpuScore === 0 &&
    $gpuScore === 0 &&
    $ramScore === 0
) {

    $priority = "System is extremely high-end and balanced";

}
elseif (
    $ramScore > $gpuScore &&
    $ramScore > $cpuScore
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

// OUTPUT

echo "<h2>System Analysis</h2>";

echo "<strong>CPU:</strong> $cpuName<br>";
echo "Gaming: {$cpuGaming}% | Overall: {$cpuOverall}%<br>";
echo "Tier: $cpuTier<br>";
echo "Upgrade Class: $cpuGameClass / $cpuOverallClass<br><br>";

echo "<strong>GPU:</strong> $gpuName<br>";
echo "Performance: {$gpuPerf}%<br>";
echo "Tier: $gpuTier<br>";
echo "Upgrade Class: $gpuClass<br><br>";

echo "<strong>RAM:</strong> {$ramAmount}GB<br>";
echo "Relative RAM Performance: {$ramPerformance}%<br><br>";

echo "<h3>Performance Summary</h3>";

echo "CPU Gaming Performance: {$cpuGaming}%<br>";
echo "GPU Performance: {$gpuPerf}%<br>";
echo "RAM Performance: {$ramPerformance}%<br><br>";

echo "<h3>Recommendation</h3>";

echo "<strong>$priority</strong><br><br>";

if (!empty($reasons)) {

    echo "<h4>Reasons:</h4>";

    echo "<ul>";

    foreach ($reasons as $reason) {

        echo "<li>$reason</li>";
    }

    echo "</ul>";
}

$conn->close();
?>