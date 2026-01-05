<?php
include 'includes/auth.php';
include '../database/config.php';
include 'includes/csrf.php';

// Ensure services folder exists
if (!is_dir('../assets/img/services')) {
    mkdir('../assets/img/services', 0755, true);
}

$success = "";

// Tambah layanan
if (isset($_POST['add_service'])) {
    if (!verify_csrf($_POST['_csrf'] ?? '')) {
        $success = "⚠️ Permintaan tidak valid (CSRF).";
    } else {
        $nama = trim($_POST['nama']);
        $deskripsi = trim($_POST['deskripsi']);
        $harga = (float) $_POST['harga'];
        $image = null;

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            $file = $_FILES['image'];
            $allowed = ['image/jpeg', 'image/png', 'image/webp'];
            
            // Log upload attempt
            $logfile = '../debug_upload.log';
            file_put_contents($logfile, "\n=== Upload Attempt " . date('Y-m-d H:i:s') . " ===\n", FILE_APPEND);
            file_put_contents($logfile, "File name: " . $file['name'] . "\n", FILE_APPEND);
            file_put_contents($logfile, "File size: " . $file['size'] . "\n", FILE_APPEND);
            file_put_contents($logfile, "File type: " . $file['type'] . "\n", FILE_APPEND);
            file_put_contents($logfile, "File error: " . $file['error'] . "\n", FILE_APPEND);
            file_put_contents($logfile, "Tmp name: " . $file['tmp_name'] . "\n", FILE_APPEND);
            
            if (!in_array($file['type'], $allowed)) {
                $success = "⚠️ Format gambar tidak didukung (gunakan JPG, PNG, atau WebP). Tipe: " . $file['type'];
                file_put_contents($logfile, "Result: Type not allowed\n", FILE_APPEND);
            } elseif ($file['size'] > 1048576) {
                $success = "⚠️ Ukuran gambar terlalu besar (maksimal 1MB).";
                file_put_contents($logfile, "Result: File too large\n", FILE_APPEND);
            } else {
                // Pastikan folder ada dan writable
                $folder = '../assets/img/services';
                if (!is_dir($folder)) {
                    mkdir($folder, 0777, true);
                }
                
                if (!is_writable($folder)) {
                    $success = "⚠️ Folder tidak dapat ditulis. Hubungi admin.";
                    file_put_contents($logfile, "Result: Folder not writable\n", FILE_APPEND);
                } else {
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $image = 'service_' . uniqid() . '.' . $ext;
                    $target = $folder . '/' . $image;
                    
                    file_put_contents($logfile, "Target: " . $target . "\n", FILE_APPEND);
                    
                    if (move_uploaded_file($file['tmp_name'], $target)) {
                        file_put_contents($logfile, "Result: SUCCESS - File moved\n", FILE_APPEND);
                    } else {
                        file_put_contents($logfile, "Result: FAILED - move_uploaded_file returned false\n", FILE_APPEND);
                        $success = "⚠️ Gagal menyimpan gambar.";
                        $image = null;
                    }
                }
            }
        }

        if (!isset($success) || $success === "") {
            $stmt = $conn->prepare("INSERT INTO services (nama, deskripsi, harga, image, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssds", $nama, $deskripsi, $harga, $image);
            if ($stmt->execute()) {
                $success = "✅ Layanan berhasil ditambahkan!";
            } else {
                $success = "⚠️ Gagal menyimpan ke database: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Hapus layanan
if (isset($_POST['delete_service'])) {
    if (!verify_csrf($_POST['_csrf'] ?? '')) {
        $success = "⚠️ Permintaan tidak valid (CSRF).";
    } else {
        $id = (int) $_POST['delete_service'];
        
        // Get image before delete
        $stmt = $conn->prepare("SELECT image FROM services WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        
        if ($row && $row['image']) {
            $imgPath = '../assets/img/services/' . $row['image'];
            if (file_exists($imgPath)) unlink($imgPath);
        }
        
        $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $success = "🗑️ Layanan berhasil dihapus!";
    }
}

// Ambil data layanan
// Tambah kolom image jika belum ada
$checkCol = $conn->query("SHOW COLUMNS FROM services LIKE 'image'");
if ($checkCol->num_rows == 0) {
    $conn->query("ALTER TABLE services ADD COLUMN image VARCHAR(255) DEFAULT NULL");
}

$result = $conn->query("SELECT * FROM services ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Layanan - Arman Jaya</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
    }
    .card {
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    th {
      background-color: #212529;
      color: white;
    }
    .btn {
      border-radius: 8px;
    }
  </style>
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Kelola Layanan</h3>
    <a href="dashboard.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Kembali</a>
  </div>

  <!-- Notifikasi -->
  <?php if ($success): ?>
    <div class="alert alert-success d-flex align-items-center" role="alert">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check2-circle me-2" viewBox="0 0 16 16">
        <path d="M2.5 8a5.5 5.5 0 1 1 11 0A5.5 5.5 0 0 1 2.5 8zm11-6.5A6.5 6.5 0 1 0 10.75 13H13a.5.5 0 0 0 0-1h-2.25A6.5 6.5 0 0 0 13.5 1.5z"/>
        <path d="M10.854 6.146a.5.5 0 1 0-.708.708L11.293 8l-1.147 1.146a.5.5 0 0 0 .708.708l1.5-1.5a.5.5 0 0 0 0-.708l-1.5-1.5z"/>
      </svg>
      <?= $success ?>
    </div>
  <?php endif; ?>

  <!-- Form Tambah -->
  <div class="card mb-4">
    <div class="card-header bg-success text-white fw-bold">Tambah Layanan</div>
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data">
        <?= csrf_input() ?>
        <div class="row g-3">
          <div class="col-md-3">
            <input type="text" name="nama" class="form-control" placeholder="Nama Layanan" required>
          </div>
          <div class="col-md-3">
            <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi" required>
          </div>
          <div class="col-md-2">
            <input type="number" step="0.01" name="harga" class="form-control" placeholder="Harga (Rp)" required>
          </div>
          <div class="col-md-2">
            <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp" title="JPG/PNG/WebP maksimal 1MB">
          </div>
          <div class="col-md-2">
            <button type="submit" name="add_service" class="btn btn-primary w-100">Tambah</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabel Layanan -->
  <div class="card">
    <div class="card-header bg-dark text-white fw-bold">Daftar Layanan</div>
    <div class="card-body">
      <table class="table table-striped align-middle text-center">
        <thead>
          <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Nama Layanan</th>
            <th>Deskripsi</th>
            <th>Harga</th>
            <th>Tanggal Dibuat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td>
              <?php 
                if (!empty($row['image'])) {
                  $imgPath = '../assets/img/services/' . $row['image'];
                  if (file_exists($imgPath)) {
                    echo '<img src="' . htmlspecialchars('assets/img/services/' . $row['image']) . '" style="max-height:50px; border-radius:4px;">';
                  } else {
                    echo '<span class="text-muted small">—</span>';
                  }
                } else {
                  echo '<span class="text-muted small">—</span>';
                }
              ?>
            </td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['deskripsi']) ?></td>
            <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
            <td><?= $row['created_at'] ?? '-' ?></td>
            <td>
              <div class="d-flex gap-2 justify-content-center">
                <a href="edit_service.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                <form method="POST" onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
                  <?= csrf_input() ?>
                  <input type="hidden" name="delete_service" value="<?= $row['id'] ?>">
                  <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
