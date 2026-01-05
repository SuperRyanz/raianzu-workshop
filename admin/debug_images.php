<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'raianzu_workshop';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

echo "<h3>Data Services:</h3>";
$result = $conn->query("SELECT id, nama, image FROM services LIMIT 5");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row['id'] . " | Nama: " . $row['nama'] . " | Image: " . ($row['image'] ?: '[KOSONG/NULL]') . "<br>";
    }
} else {
    echo "Query error: " . $conn->error;
}

echo "<hr>";
echo "<h3>Cek Kolom image di tabel services:</h3>";
$result = $conn->query("SHOW COLUMNS FROM services LIKE 'image'");
if ($result->num_rows > 0) {
    echo "✅ Kolom image ada<br>";
    $col = $result->fetch_assoc();
    echo "Type: " . $col['Type'] . "<br>";
} else {
    echo "❌ Kolom image TIDAK ada! Perlu ditambah.";
}

echo "<hr>";
echo "<h3>Cek folder:</h3>";
$folder = '../assets/img/services';
if (is_dir($folder)) {
    echo "✅ Folder ada: " . realpath($folder) . "<br>";
    echo "Writable: " . (is_writable($folder) ? "✅ YES" : "❌ NO") . "<br>";
    $files = scandir($folder);
    echo "Files: " . count($files) . " (including . dan ..)<br>";
} else {
    echo "❌ Folder tidak ada!";
}

$conn->close();
?>
