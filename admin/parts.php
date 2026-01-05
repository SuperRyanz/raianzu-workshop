<?php
include 'includes/auth.php';
include '../database/config.php';
include 'includes/csrf.php';

// --- Tambah onderdil ---
if (isset($_POST['add_part'])) {
    if (!verify_csrf($_POST['_csrf'] ?? '')) {
        $error = "⚠️ Permintaan tidak valid (CSRF).";
    } else {
        $nama = trim($_POST['nama']);
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $stok = (int) $_POST['stok'];
        $harga = (int) $_POST['harga'];

        // handle file upload
        $imageName = null;
        if (!empty($_FILES['image']['name'])) {
            $img = $_FILES['image'];
            if ($img['error'] === UPLOAD_ERR_OK) {
                if ($img['size'] <= 1024*1024) { // 1MB
                    $info = getimagesize($img['tmp_name']);
                    if ($info !== false) {
                        $ext = image_type_to_extension($info[2], false);
                        $imageName = uniqid('part_', true) . '.' . $ext;
                        $dest = __DIR__ . '/../assets/img/parts/' . $imageName;
                        move_uploaded_file($img['tmp_name'], $dest);
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

        // Validasi sederhana
        if (empty($error) && $nama !== '' && $stok >= 0 && $harga >= 0) {
            $stmt = $conn->prepare("INSERT INTO parts (nama, deskripsi, stok, harga, image, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssiis", $nama, $deskripsi, $stok, $harga, $imageName);
            $stmt->execute();
            $stmt->close();
            header("Location: parts.php?success=1");
            exit;
        } elseif (empty($error)) {
            $error = "Pastikan semua field diisi dengan benar!";
        }
    }
}

// --- Hapus onderdil (via POST) ---
if (isset($_POST['delete_part'])) {
    if (!verify_csrf($_POST['_csrf'] ?? '')) {
        $error = "⚠️ Permintaan tidak valid (CSRF).";
    } else {
        $id = (int) $_POST['delete_part'];
        // ambil file image untuk dihapus
        $s = $conn->prepare("SELECT image FROM parts WHERE id = ? LIMIT 1");
        $s->bind_param("i", $id);
        $s->execute();
        $r = $s->get_result()->fetch_assoc();
        $s->close();
        if (!empty($r['image'])) {
            $path = __DIR__ . '/../assets/img/parts/' . $r['image'];
            if (file_exists($path)) @unlink($path);
        }

        $stmt = $conn->prepare("DELETE FROM parts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: parts.php?deleted=1");
        exit;
    }
}

// --- Ambil data onderdil ---
$result = $conn->query("SELECT * FROM parts ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Onderdil - Arman Jaya</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Kelola Onderdil</h3>
    <a href="dashboard.php" class="btn btn-secondary">⬅ Kembali</a>
  </div>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">✅ Onderdil berhasil ditambahkan!</div>
  <?php elseif (isset($_GET['deleted'])): ?>
    <div class="alert alert-warning">🗑️ Onderdil berhasil dihapus!</div>
  <?php elseif (isset($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <!-- Form Tambah -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-success text-white">Tambah Onderdil</div>
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data">
        <?= csrf_input() ?>
        <div class="row g-3">
          <div class="col-md-4">
            <input type="text" name="nama" class="form-control" placeholder="Nama Onderdil" required>
          </div>
          <div class="col-md-3">
            <input type="number" name="stok" class="form-control" placeholder="Stok" min="0" required>
          </div>
          <div class="col-md-3">
            <input type="number" step="1" name="harga" class="form-control" placeholder="Harga (Rp)" min="0" required>
          </div>
          <div class="col-md-12 mt-2">
            <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi singkat (opsional)">
          </div>
          <div class="col-md-6">
            <input type="file" name="image" accept="image/*" class="form-control">
          </div>
          <div class="col-md-2">
            <button type="submit" name="add_part" class="btn btn-primary w-100">Tambah</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabel Onderdil -->
  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white">Daftar Onderdil</div>
    <div class="card-body">
      <table class="table table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Nama Onderdil</th>
            <th>Stok</th>
            <th>Harga</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= $row['stok'] ?></td>
            <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
            <td>
              <a href="edit_part.php?id=<?= $row['id'] ?>" class="btn btn-secondary btn-sm me-1">Edit</a>
              <form method="POST" onsubmit="return confirm('Yakin hapus onderdil ini?')" style="display:inline-block;">
                <?= csrf_input() ?>
                <input type="hidden" name="delete_part" value="<?= $row['id'] ?>">
                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
              </form>
            </td>
          </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center text-muted">Belum ada onderdil.</td>
          </tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
