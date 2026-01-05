<?php
// Debug test upload di folder services
ini_set('display_errors', 1);
error_reporting(E_ALL);

$target_dir = "../assets/img/services/";
$target_file = $target_dir . "debug_" . time() . ".txt";

// Buat file test
$test_content = "Test file dibuat: " . date('Y-m-d H:i:s');
file_put_contents($target_file, $test_content);

echo "<h2>Test Write File:</h2>";
echo "Target: " . $target_file . "<br>";
echo "Exists: " . (file_exists($target_file) ? "✅ YES" : "❌ NO") . "<br>";
echo "Content: " . file_get_contents($target_file) . "<br>";

echo "<hr>";

// Test list files
echo "<h2>Files in services folder:</h2>";
$files = scandir($target_dir);
echo "Total: " . (count($files) - 2) . " files<br>";
foreach ($files as $f) {
    if ($f != '.' && $f != '..') {
        echo "- " . $f . "<br>";
    }
}

echo "<hr>";

// Folder info
echo "<h2>Folder Info:</h2>";
echo "Folder exists: " . (is_dir($target_dir) ? "✅ YES" : "❌ NO") . "<br>";
echo "Is writable: " . (is_writable($target_dir) ? "✅ YES" : "❌ NO") . "<br>";
echo "Absolute path: " . realpath($target_dir) . "<br>";

echo "<hr>";
echo "<a href='services.php'>Back to Services</a>";
?>
