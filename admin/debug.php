<?php
echo "<h1>DEBUG LOGIN</h1>";

include "../database/config.php";

echo "<h3>1. Cek koneksi DB</h3>";
if ($conn->connect_error) {
    die("❌ Koneksi gagal: " . $conn->connect_error);
} else {
    echo "✔ Koneksi berhasil<br>";
}

echo "<h3>2. Ambil user admin</h3>";

$q = $conn->query("SELECT * FROM users WHERE username='admin' LIMIT 1");

if ($q->num_rows == 0) {
    die("❌ Username 'admin' TIDAK ADA di database");
}

$user = $q->fetch_assoc();

echo "✔ Username ditemukan<br>";
echo "Password hash di database:<br><pre>";
print_r($user['password']);
echo "</pre>";

echo "<h3>3. Test password_verify()</h3>";

$test = password_verify("admin123", $user['password']);

if ($test) {
    echo "<h2 style='color:green'>✔ PASSWORD BENAR — LOGIN HARUSNYA BISA</h2>";
} else {
    echo "<h2 style='color:red'>❌ PASSWORD SALAH — HASH TIDAK COCOK!</h2>";
}

echo "<h3>4. Info tambahan</h3>";
echo "Hash length: " . strlen($user['password']);
