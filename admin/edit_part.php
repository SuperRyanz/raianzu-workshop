<?php
include 'includes/auth.php';
include '../database/config.php';
include 'includes/csrf.php';

// Ambil id
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: parts.php');
    exit;
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['_csrf'] ?? '')) {
        $error = 'Permintaan tidak valid (CSRF).';
    } else {
        $nama = trim($_POST['nama'] ?? '');
        $stok = (int)($_POST['stok'] ?? 0);
        $harga = (int)($_POST['harga'] ?? 0);
        $deskripsi = trim($_POST['deskripsi'] ?? '');

        // handle image upload (opsional)
        $newImage = null;
        if (!empty($_FILES['image']['name'])) {
            $img = $_FILES['image'];
            if ($img['error'] === UPLOAD_ERR_OK) {
                if ($img['size'] <= 1024*1024) {
                    $info = getimagesize($img['tmp_name']);
                    if ($info !== false) {
                        $ext = image_type_to_extension($info[2], false);
                        $newImage = uniqid('part_', true) . '.' . $ext;
                        $dest = __DIR__ . '/../assets/img/parts/' . $newImage;
                        move_uploaded_file($img['tmp_name'], $dest);
                        // hapus file lama
                        if (!empty($part['image'])) {
                            $old = __DIR__ . '/../assets/img/parts/' . $part['image'];
                            if (file_exists($old)) @unlink($old);
                        }
                    } else {
                        $error = 'File gambar tidak valid.';
                    }
                } else {
                    $error = 'File gambar terlalu besar (maks 1MB).';
                }
            } else {
                $error = 'Terjadi kesalahan saat upload gambar.';
            }
        }

        if ($nama === '' || $stok < 0 || $harga < 0) {
            $error = 'Pastikan data valid.';
        } else {
            if ($newImage !== null) {
                $stmt = $conn->prepare('UPDATE parts SET nama = ?, deskripsi = ?, stok = ?, harga = ?, image = ? WHERE id = ?');
                $stmt->bind_param('ssiisi', $nama, $deskripsi, $stok, $harga, $newImage, $id);
            } else {
                $stmt = $conn->prepare('UPDATE parts SET nama = ?, deskripsi = ?, stok = ?, harga = ? WHERE id = ?');
                $stmt->bind_param('ssiis', $nama, $deskripsi, $stok, $harga, $id);
            }
            $stmt->execute();
            $stmt->close();
            header('Location: parts.php?updated=1');
            exit;
        }
    }
}

// Ambil data part
$stmt = $conn->prepare('SELECT * FROM parts WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$part = $res->fetch_assoc();
if (!$part) {
    header('Location: parts.php');
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Onderdil - Arman Jaya</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Edit Onderdil</h3>
    <a href="parts.php" class="btn btn-secondary">⬅ Kembali</a>
  </div>

  <?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST">
        <?= csrf_input() ?>
        <div class="mb-3">
          <label class="form-label">Nama</label>
          <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($part['nama']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Deskripsi (opsional)</label>
          <input type="text" name="deskripsi" class="form-control" value="<?= htmlspecialchars($part['deskripsi'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Stok</label>
          <input type="number" name="stok" class="form-control" value="<?= (int)$part['stok'] ?>" min="0" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Harga (Rp)</label>
          <input type="number" name="harga" class="form-control" value="<?= (int)$part['harga'] ?>" min="0" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Ganti Gambar (opsional)</label>
          <input type="file" name="image" accept="image/*" class="form-control">
        </div>
        <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>