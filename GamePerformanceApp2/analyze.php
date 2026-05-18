<?php
include 'connectdb.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Not logged in");
}
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

$stmt = $conn->prepare("INSERT INTO user_systems (user_id, cpu_id, gpu_id, ram_amount, game_id) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iiiii", $userId, $cpu, $gpu, $ram, $gameId);
$stmt->execute();

// Load user hardware
$result = $conn->query("SELECT c.name AS cpu, c.overall_performance, c.gaming_performance, c.tier AS cpu_tier,g.name AS gpu, g.relative_performance, g.tier AS gpu_tier,u.ram_amount, u.ram_performance FROM user_systems u JOIN cpus c ON u.cpu_id = c.id JOIN gpus g ON u.gpu_id = g.id ORDER BY u.id DESC LIMIT 1");
$row = $result->fetch_assoc();

// Load game requirements
$gameQuery = $conn->prepare( "SELECT gm.game_name, gm.recommended_ram, rc.name AS required_cpu, rc.gaming_performance AS required_cpu_score, rg.name AS required_gpu, rg.relative_performance AS required_gpu_score FROM games gm   JOIN cpus rc ON gm.recommended_cpu_id = rc.id JOIN gpus rg ON gm.recommended_gpu_id = rg.id WHERE gm.game_id = ?");
$gameQuery->bind_param("i", $gameId);
$gameQuery->execute();
$game = $gameQuery->get_result()->fetch_assoc();

// Extract hardware data
$cpuName = $row['cpu'];
$cpuOverall = $row['overall_performance'];
$cpuGaming = $row['gaming_performance'];
$cpuTier = $row['cpu_tier'];
$gpuName = $row['gpu'];
$gpuPerf = $row['relative_performance'];
$gpuTier = $row['gpu_tier'];
$ramAmount = $row['ram_amount'];
$ramPerformance = $row['ram_performance'];

// Extract game requirements
$requiredCPU = $game['required_cpu_score'];
$requiredGPU = $game['required_gpu_score'];
$requiredRAM = $game['recommended_ram'];

// Calculate relative performance
$cpuRelative = round(($cpuGaming / $requiredCPU) * 100, 1);
$gpuRelative = round(($gpuPerf / $requiredGPU) * 100, 1);
$ramRelative = round(($ramAmount / $requiredRAM) * 100, 1);

// Performance rating function
function getStatus($value) {
    if ($value >= 140) return "Excellent";
    if ($value >= 100) return "Good";
    if ($value >= 80) return "Acceptable";
    return "Poor";
}
$cpuStatus = getStatus($cpuRelative);
$gpuStatus = getStatus($gpuRelative);
$ramStatus = getStatus($ramRelative);

// Check for upgrade needs
$cpuScore = 0;
$gpuScore = 0;
$ramScore = 0;
$reasons = [];

if ($cpuRelative < 100) { $cpuScore += 2; $reasons[] = "CPU below recommended"; }
if ($gpuRelative < 100) { $gpuScore += 2; $reasons[] = "GPU below recommended"; }
if ($ramRelative < 100) { $ramScore += 2; $reasons[] = "RAM below recommended"; }

// Check for bottlenecks
$gap = abs($gpuRelative - $cpuRelative);
if ($gap > 40) {
    if ($gpuRelative > $cpuRelative) {
        $cpuScore += 1;
        $reasons[] = "CPU bottleneck detected";
    } else {
        $gpuScore += 1;
        $reasons[] = "GPU bottleneck detected";
    }
}

// Determine upgrade priority
if ($cpuScore == 0 && $gpuScore == 0 && $ramScore == 0) {
    $priority = "System exceeds requirements";
} elseif ($cpuScore > $gpuScore && $cpuScore > $ramScore) {
    $priority = "Upgrade CPU first";
} elseif ($gpuScore > $cpuScore && $gpuScore > $ramScore) {
    $priority = "Upgrade GPU first";
} elseif ($ramScore > $cpuScore && $ramScore > $gpuScore) {
    $priority = "Upgrade RAM first";
} elseif ($cpuScore == $gpuScore && $cpuScore > 0) {
    $priority = "Upgrade CPU first";
} elseif ($gpuScore == $ramScore && $gpuScore > 0) {
    $priority = "Upgrade GPU first";
} elseif ($cpuScore == $ramScore && $cpuScore > 0) {
    $priority = "Upgrade CPU first";
} else {
    $priority = "System is balanced";
}

// Determine game compatibility message
if ($cpuRelative >= 140 && $gpuRelative >= 140 && $ramRelative >= 100) {
    $gameMessage = "Your PC greatly exceeds the recommended requirements.";
} elseif ($cpuRelative >= 100 && $gpuRelative >= 100 && $ramRelative >= 100) {
    $gameMessage = "Your PC meets the recommended requirements.";
} elseif ($cpuRelative >= 80 && $gpuRelative >= 80) {
    $gameMessage = "Your PC is slightly below the recommended requirements.";
} else {
    $gameMessage = "Your PC is well below the recommended requirements.";
}

// Output HTML
echo "<h2>System Analysis</h2><hr>";
echo "<h3>Your Hardware</h3>";
echo "<strong>CPU:</strong> " . htmlspecialchars($cpuName) . "<br>";
echo "Gaming: {$cpuGaming}% | Overall: {$cpuOverall}% | Tier: " . htmlspecialchars($cpuTier) . "<br><br>";
echo "<strong>GPU:</strong> " . htmlspecialchars($gpuName) . "<br>";
echo "Performance: {$gpuPerf}% | Tier: " . htmlspecialchars($gpuTier) . "<br><br>";
echo "<strong>RAM:</strong> {$ramAmount}GB (" . htmlspecialchars($ramPerformance) . "% performance)<br><br><hr>";
echo "<h3>Selected Game:</h3>" . htmlspecialchars($game['game_name']) . "<br><br>";
echo "<h3>Recommended Hardware</h3>";
echo "CPU: " . htmlspecialchars($game['required_cpu']) . " | ";
echo "GPU: " . htmlspecialchars($game['required_gpu']) . " | ";
echo "RAM: {$requiredRAM}GB<br><br><hr>";
echo "<h3>Relative Performance</h3>";
echo "CPU: {$cpuRelative}% ({$cpuStatus}) | GPU: {$gpuRelative}% ({$gpuStatus}) | RAM: {$ramRelative}% ({$ramStatus})<br><br><hr>";
echo "<h3>Game Result</h3><strong>" . htmlspecialchars($gameMessage) . "</strong><br><br>";
echo "<h3>Upgrade Recommendation</h3><strong>" . htmlspecialchars($priority) . "</strong><br><br>";
echo "<h3>Reasons</h3><ul>";
foreach ($reasons as $r) {
    echo "<li>" . htmlspecialchars($r) . "</li>";
}
echo "</ul>";
$conn->close();