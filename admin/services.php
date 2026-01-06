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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Layanan - Arman Jaya</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    *{box-sizing:border-box;margin:0;padding:0;}
    body{
      font-family:'Poppins',sans-serif;
      color:#fff;
      background-image:url('../assets/img/bengkel.jpg');
      background-size:cover;
      background-position:center;
      background-attachment:fixed;
      min-height:100vh;
    }
    body::before{content:'';position:fixed;inset:0;background:rgba(0,0,0,0.48);z-index:-1;}
    .sidebar{background:rgba(15,23,42,0.85);backdrop-filter:blur(8px);width:280px;min-height:100vh;border-right:1px solid rgba(255,255,255,0.1);position:sticky;top:0;}
    .sidebar-header{padding:2rem 1.5rem;border-bottom:1px solid rgba(255,255,255,0.1);}
    .sidebar h4{font-weight:700;color:#fff;font-size:1.3rem;display:flex;align-items:center;gap:0.5rem;}
    .sidebar h4 i{color:#ff6a00;font-size:1.5rem;}
    .nav-link{color:#94a3b8;font-weight:500;padding:0.85rem 1.5rem;border-radius:10px;transition:all 0.3s ease;margin:0.3rem 0.75rem;display:flex;align-items:center;gap:0.75rem;}
    .nav-link:hover{background:rgba(255,106,0,0.15);color:#ff6a00;transform:translateX(5px);}
    .nav-link.active{background:linear-gradient(135deg,#ff6a00,#e75d00);color:#fff;}
    .nav-link i{width:20px;text-align:center;}
    .logout-btn{color:#ef4444;}
    .logout-btn:hover{background:rgba(239,68,68,0.15)!important;color:#f87171!important;}
    .main-content{flex:1;padding:2rem;max-width:1400px;margin:0 auto;}
    .glass-card{background:rgba(0,0,0,0.48);backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,0.06);border-radius:16px;padding:1.5rem;color:#fff;}
    .section-title{font-weight:700;font-size:1.4rem;margin-bottom:1rem;}
    .btn-primary{background:#ff6a00;border-color:#ff6a00;font-weight:600;}
    .btn-primary:hover{background:#e75d00;border-color:#e75d00;}
    .form-control,.form-select,.form-control:focus{background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.16);color:#fff;}
    .form-control::placeholder{color:#94a3b8;}
    .form-control::file-selector-button{background:#fff;color:#1a1a1a;border:none;padding:0.375rem 0.75rem;margin-right:0.75rem;border-radius:4px;cursor:pointer;font-weight:500;}
    .form-control::file-selector-button:hover{background:#f0f0f0;}
    input[type="file"].form-control{color:#e2e8f0;font-size:0.9rem;}
    .table thead th{color:#fff;border:none;background:linear-gradient(135deg,#ff6a00,#e75d00);letter-spacing:0.4px;text-transform:uppercase;font-weight:700;font-size:0.85rem;} 
    .table tbody td{color:#e8edf5;vertical-align:middle;border-color:rgba(255,255,255,0.08);} 
    .table>:not(caption)>*>*{background:transparent!important;} 
    .modern-table tbody tr>*{background:rgba(0,0,0,0.18);} 
    .modern-table tbody tr:nth-of-type(odd)>*{background:rgba(0,0,0,0.22);} 
    .modern-table tbody tr:hover>*{background:rgba(255,255,255,0.08);color:#fff;box-shadow:inset 4px 0 0 #ff6a00;} 
    .table img{max-height:50px;border-radius:6px;box-shadow:0 6px 18px rgba(0,0,0,0.35);} 
    .alert-glass{background:rgba(16,185,129,0.12);border:1px solid rgba(16,185,129,0.35);color:#bbf7d0;border-radius:12px;}
    .table img{max-height:50px;border-radius:4px;}
  </style>
</head>
<body>
  <div class="d-flex">
    <div class="sidebar">
      <div class="sidebar-header">
        <h4><i class="fas fa-tools"></i> Arman Jaya</h4>
        <small style="color:#64748b;display:block;margin-top:0.5rem;">Admin Panel</small>
      </div>
      <ul class="nav flex-column" style="padding:1rem 0;">
        <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="services.php"><i class="fas fa-screwdriver-wrench"></i> Layanan</a></li>
        <li class="nav-item"><a class="nav-link" href="parts.php"><i class="fas fa-gears"></i> Onderdil</a></li>
        <li class="nav-item"><a class="nav-link" href="messages.php"><i class="fas fa-envelope"></i> Pesan</a></li>
        <li class="nav-item"><a class="nav-link logout-btn" href="logout.php"><i class="fas fa-right-from-bracket"></i> Logout</a></li>
      </ul>
    </div>

    <div class="main-content">
      <div class="d-flex align-items-center mb-3" style="gap:0.5rem;">
        <div style="width:10px;height:10px;border-radius:50%;background:#ff6a00;"></div>
        <h3 style="margin:0;font-weight:700;">Kelola Layanan</h3>
      </div>

      <?php if ($success): ?>
        <div class="alert alert-glass mb-3"><?= $success ?></div>
      <?php endif; ?>

      <div class="glass-card mb-4">
        <div class="section-title">Tambah Layanan</div>
        <form method="POST" enctype="multipart/form-data">
          <?= csrf_input() ?>
          <div class="row g-3 align-items-end">
            <div class="col-md-3">
              <label class="form-label" style="color:#e2e8f0;font-weight:500;">Nama Layanan</label>
              <input type="text" name="nama" class="form-control" placeholder="Nama Layanan" required>
            </div>
            <div class="col-md-3">
              <label class="form-label" style="color:#e2e8f0;font-weight:500;">Deskripsi</label>
              <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi" required>
            </div>
            <div class="col-md-2">
              <label class="form-label" style="color:#e2e8f0;font-weight:500;">Harga (Rp)</label>
              <input type="number" step="0.01" name="harga" class="form-control" placeholder="Harga" required>
            </div>
            <div class="col-md-2">
              <label class="form-label" style="color:#e2e8f0;font-weight:500;">Gambar (JPG/PNG/WebP)</label>
              <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
            </div>
            <div class="col-md-2 d-grid">
              <button type="submit" name="add_service" class="btn btn-primary">Tambah</button>
            </div>
          </div>
        </form>
      </div>

      <div class="glass-card">
        <div class="section-title d-flex justify-content-between align-items-center">
          <span>Daftar Layanan</span>
        </div>
        <div class="table-responsive">
          <table class="table table-striped table-hover modern-table align-middle text-center mb-0">
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
                        $src = htmlspecialchars('../assets/img/services/' . $row['image']);
                        echo '<a href="' . $src . '" target="_blank" rel="noopener noreferrer" title="Buka gambar"><img src="' . $src . '" alt="img"></a>';
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
                <td class="d-flex gap-2 justify-content-center">
                  <a href="edit_service.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                  <form method="POST" onsubmit="return confirm('Hapus layanan ini?');">
                    <?= csrf_input() ?>
                    <button class="btn btn-sm btn-danger" type="submit" name="delete_service" value="<?= $row['id'] ?>">Hapus</button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
