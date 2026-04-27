<?php
include 'connectdb.php';

// Validate request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request");
}

if (!isset($_POST['cpu_id']) || !isset($_POST['gpu_id'])) {
    die("Missing input");
}

$cpu = (int)$_POST['cpu_id'];
$gpu = (int)$_POST['gpu_id'];

// Store system
$stmt = $conn->prepare("INSERT INTO user_systems (cpu_id, gpu_id) VALUES (?, ?)");
$stmt->bind_param("ii", $cpu, $gpu);
$stmt->execute();

// Get full system data
$result = $conn->query("
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

    (g.relative_performance - c.gaming_performance) AS gap

FROM user_systems u
JOIN cpus c ON u.cpu_id = c.id
JOIN gpus g ON u.gpu_id = g.id
ORDER BY u.id DESC LIMIT 1
");

$row = $result->fetch_assoc();

// Extract values
$cpuName = $row['cpu'];
$gpuName = $row['gpu'];

$cpuOverall = $row['overall_performance'];
$cpuGaming = $row['gaming_performance'];
$cpuTier = $row['cpu_tier'];

$gpuPerf = $row['relative_performance'];
$gpuTier = $row['gpu_tier'];

$cpuGameClass = $row['gaming_upgrade_class'];
$cpuOverallClass = $row['overall_upgrade_class'];
$gpuClass = $row['upgrade_class'];

$gap = $row['gap'];

// Decision logic
$reasons = [];

$gpuScore = 0;
$cpuScore = 0;

// GPU evaluation
if ($gpuPerf < 100) {
    $gpuScore += 2;
    $reasons[] = "GPU below RTX 5070 Ti baseline";
} elseif ($gpuClass === 'Marginal Upgrade') {
    $gpuScore += 1;
    $reasons[] = "GPU only marginal improvement tier";
}

// CPU evaluation
if ($cpuGaming < 100) {
    $cpuScore += 2;
    $reasons[] = "CPU gaming below 7800X3D baseline";
} elseif ($cpuGameClass === 'Marginal Upgrade') {
    $cpuScore += 1;
    $reasons[] = "CPU gaming only marginal";
}

// Bottleneck detection
if ($gap > 20) {
    $cpuScore += 2;
    $reasons[] = "CPU bottleneck detected";
} elseif ($gap < -20) {
    $gpuScore += 2;
    $reasons[] = "GPU bottleneck detected";
}

// Tier awareness
if ($cpuTier === 'Lower Tier' && $gpuPerf > 100) {
    $cpuScore += 1;
    $reasons[] = "Low-tier CPU with strong GPU";
}

if ($gpuTier === 'Lower Tier' && $cpuGaming > 100) {
    $gpuScore += 1;
    $reasons[] = "Low-tier GPU with strong CPU";
}

// Final decision
if ($gpuScore > $cpuScore) {
    $priority = "Upgrade GPU first";
} elseif ($cpuScore > $gpuScore) {
    $priority = "Upgrade CPU first";
} else {
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

echo "<h3>Performance</h3>";
echo "CPU: {$cpuGaming}%<br>";
echo "GPU: {$gpuPerf}%<br><br>";

echo "<h3>Recommendation</h3>";
echo "<strong>$priority</strong><br><br>";

echo "<h4>Reasons:</h4><ul>";
foreach ($reasons as $r) {
    echo "<li>$r</li>";
}
echo "</ul>";

$conn->close();
?>
