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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Onderdil - Arman Jaya</title>
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
    .form-control::file-selector-button{background:rgba(255,106,0,0.8);color:#fff;border:none;padding:0.375rem 0.75rem;margin-right:0.75rem;border-radius:4px;cursor:pointer;}
    .form-control::file-selector-button:hover{background:#ff6a00;}
    input[type="file"].form-control{padding-left:0.5rem;color:#e2e8f0;font-size:0.9rem;}
    .form-control::file-selector-button{background:rgba(255,106,0,0.8);color:#fff;border:none;padding:0.375rem 0.75rem;margin-right:0.75rem;border-radius:4px;cursor:pointer;}
    .form-control::file-selector-button:hover{background:#ff6a00;}
    .table thead th{color:#fff;border:none;background:linear-gradient(135deg,#ff6a00,#e75d00);letter-spacing:0.4px;text-transform:uppercase;font-weight:700;font-size:0.85rem;} 
    .table tbody td{color:#e8edf5;vertical-align:middle;border-color:rgba(255,255,255,0.08);} 
    .table>:not(caption)>*>*{background:transparent!important;} 
    .modern-table tbody tr>*{background:rgba(0,0,0,0.18);} 
    .modern-table tbody tr:nth-of-type(odd)>*{background:rgba(0,0,0,0.22);} 
    .modern-table tbody tr:hover>*{background:rgba(255,255,255,0.08);color:#fff;box-shadow:inset 4px 0 0 #ff6a00;} 
    .table img{max-height:50px;border-radius:6px;box-shadow:0 6px 18px rgba(0,0,0,0.35);} 
    .alert-glass{background:rgba(59,130,246,0.15);border:1px solid rgba(59,130,246,0.35);color:#dbeafe;border-radius:12px;}
    .alert-success-glass{background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.35);color:#bbf7d0;border-radius:12px;}
    .alert-warning-glass{background:rgba(234,179,8,0.15);border:1px solid rgba(234,179,8,0.35);color:#fef08a;border-radius:12px;}
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
        <li class="nav-item"><a class="nav-link" href="services.php"><i class="fas fa-screwdriver-wrench"></i> Layanan</a></li>
        <li class="nav-item"><a class="nav-link active" href="parts.php"><i class="fas fa-gears"></i> Onderdil</a></li>
        <li class="nav-item"><a class="nav-link" href="messages.php"><i class="fas fa-envelope"></i> Pesan</a></li>
        <li class="nav-item"><a class="nav-link logout-btn" href="logout.php"><i class="fas fa-right-from-bracket"></i> Logout</a></li>
      </ul>
    </div>

    <div class="main-content">
      <div class="d-flex align-items-center mb-3" style="gap:0.5rem;">
        <div style="width:10px;height:10px;border-radius:50%;background:#ff6a00;"></div>
        <h3 style="margin:0;font-weight:700;">Kelola Onderdil</h3>
      </div>

      <?php if (isset($_GET['success'])): ?>
        <div class="alert-success-glass mb-3 p-3">✅ Onderdil berhasil ditambahkan!</div>
      <?php elseif (isset($_GET['deleted'])): ?>
        <div class="alert-warning-glass mb-3 p-3">🗑️ Onderdil berhasil dihapus!</div>
      <?php elseif (isset($error)): ?>
        <div class="alert-glass mb-3 p-3"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <div class="glass-card mb-4">
        <div class="section-title">Tambah Onderdil</div>
        <form method="POST" enctype="multipart/form-data">
          <?= csrf_input() ?>
          <div class="row g-3 align-items-end">
            <div class="col-md-4">
              <label class="form-label" style="color:#e2e8f0;font-weight:500;">Nama Onderdil</label>
              <input type="text" name="nama" class="form-control" placeholder="Nama" required>
            </div>
            <div class="col-md-3">
              <label class="form-label" style="color:#e2e8f0;font-weight:500;">Stok</label>
              <input type="number" name="stok" class="form-control" placeholder="Stok" min="0" required>
            </div>
            <div class="col-md-3">
              <label class="form-label" style="color:#e2e8f0;font-weight:500;">Harga (Rp)</label>
              <input type="number" step="1" name="harga" class="form-control" placeholder="Harga" min="0" required>
            </div>
            <div class="col-md-12">
              <label class="form-label" style="color:#e2e8f0;font-weight:500;">Deskripsi (opsional)</label>
              <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi singkat">
            </div>
            <div class="col-md-4">
              <label class="form-label" style="color:#e2e8f0;font-weight:500;">Gambar (maks 1MB)</label>
              <input type="file" name="image" accept="image/*" class="form-control">
            </div>
            <div class="col-md-2 d-grid">
              <button type="submit" name="add_part" class="btn btn-primary">Tambah</button>
            </div>
          </div>
        </form>
      </div>

      <div class="glass-card">
        <div class="section-title">Daftar Onderdil</div>
        <div class="table-responsive">
          <table class="table table-striped table-hover modern-table align-middle text-center mb-0">
            <thead>
              <tr>
                <th>No</th>
                <th>Gambar</th>
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
                <td>
                  <?php 
                    if (!empty($row['image'])) {
                      $imgPath = '../assets/img/parts/' . $row['image'];
                      if (file_exists($imgPath)) {
                        $src = htmlspecialchars('../assets/img/parts/' . $row['image']);
                        echo '<a href="' . $src . '" target="_blank" rel="noopener noreferrer" title="Buka gambar"><img src="' . $src . '" alt="img" style="max-height:50px;border-radius:6px;box-shadow:0 6px 18px rgba(0,0,0,0.35);"></a>';
                      } else {
                        echo '<span class="text-muted small">—</span>';
                      }
                    } else {
                      echo '<span class="text-muted small">—</span>';
                    }
                  ?>
                </td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= $row['stok'] ?></td>
                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                <td class="d-flex gap-2 justify-content-center">
                  <a href="edit_part.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                  <form method="POST" onsubmit="return confirm('Yakin hapus onderdil ini?')">
                    <?= csrf_input() ?>
                    <input type="hidden" name="delete_part" value="<?= $row['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                  </form>
                </td>
              </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center text-muted">Belum ada onderdil.</td>
              </tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
