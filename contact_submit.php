<?php
include 'database/config.php';
include 'admin/includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

// honeypot anti-spam
if (!empty($_POST['hp'] ?? '')) {
    // silently drop
    header('Location: contact.php?sent=1');
    exit;
}

if (!verify_csrf($_POST['_csrf'] ?? '')) {
    header('Location: contact.php?error=csrf');
    exit;
}

$nama = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$pesan = trim($_POST['pesan'] ?? '');

if ($nama === '' || $email === '' || $pesan === '') {
    header('Location: contact.php?error=empty');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: contact.php?error=email');
    exit;
}

$stmt = $conn->prepare("INSERT INTO messages (nama, email, pesan, created_at) VALUES (?, ?, ?, NOW())");
$stmt->bind_param('sss', $nama, $email, $pesan);
$stmt->execute();
$stmt->close();

header('Location: contact.php?sent=1');
exit;
?>