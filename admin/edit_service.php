<?php
include 'includes/auth.php';
include '../database/config.php';
include 'includes/csrf.php';

if (!isset($_GET['id'])) {
    header('Location: services.php');
    exit;
}
$id = (int) $_GET['id'];

// Ambil data layanan
$stmt = $conn->prepare("SELECT id, nama, deskripsi, harga, image FROM services WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$service = $res->fetch_assoc();
$stmt->close();

if (!$service) {
    header('Location: services.php');
    exit;
}

// Ensure folder exists
if (!is_dir('../assets/img/services')) {
    mkdir('../assets/img/services', 0755, true);
}

$success = '';

if (isset($_POST['save_service'])) {
    if (!verify_csrf($_POST['_csrf'] ?? '')) {
        $success = "⚠️ Permintaan tidak valid (CSRF).";
    } else {
        $nama = trim($_POST['nama']);
        $deskripsi = trim($_POST['deskripsi']);
        $harga = (float) $_POST['harga'];
        $image = $service['image']; // Tetap gunakan gambar lama

        // Handle image upload/replace
        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            $file = $_FILES['image'];
            $allowed = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($file['type'], $allowed)) {
                $success = "⚠️ Format gambar tidak didukung (gunakan JPG, PNG, atau WebP).";
            } elseif ($file['size'] > 1048576) {
                $success = "⚠️ Ukuran gambar terlalu besar (maksimal 1MB).";
            } else {
                // Delete old image
                if ($service['image']) {
                    $oldPath = '../assets/img/services/' . $service['image'];
                    if (file_exists($oldPath)) unlink($oldPath);
                }
                
                $image = 'service_' . uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
                move_uploaded_file($file['tmp_name'], '../assets/img/services/' . $image);
            }
        }

        if (!isset($success) || $success === "") {
            $stmt = $conn->prepare("UPDATE services SET nama = ?, deskripsi = ?, harga = ?, image = ? WHERE id = ?");
            $stmt->bind_param('ssdsi', $nama, $deskripsi, $harga, $image, $id);
            $stmt->execute();
            $stmt->close();

            $success = "✅ Layanan berhasil diperbarui.";
            // Refresh data
            $stmt = $conn->prepare("SELECT id, nama, deskripsi, harga, image FROM services WHERE id = ? LIMIT 1");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $service = $res->fetch_assoc();
            $stmt->close();
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Edit Layanan - Arman Jaya</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Edit Layanan</h3>
    <a href="services.php" class="btn btn-secondary">Kembali</a>
  </div>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <div class="card">
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data">
        <?= csrf_input() ?>
        <div class="mb-3">
          <label class="form-label">Nama Layanan</label>
          <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($service['nama']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Deskripsi</label>
          <input type="text" name="deskripsi" class="form-control" value="<?= htmlspecialchars($service['deskripsi']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Harga (Rp)</label>
          <input type="number" step="0.01" name="harga" class="form-control" value="<?= htmlspecialchars($service['harga']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Gambar Layanan</label>
          <?php if ($service['image']): ?>
            <div class="mb-2">
              <small class="text-muted d-block mb-2">Gambar saat ini:</small>
              <img src="../assets/img/services/<?= htmlspecialchars($service['image']) ?>" style="max-width: 150px; border-radius: 4px;">
            </div>
          <?php endif; ?>
          <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
          <small class="text-muted">Format: JPG, PNG, WebP | Max: 1MB</small>
        </div>
        <button class="btn btn-primary" type="submit" name="save_service">Simpan Perubahan</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>