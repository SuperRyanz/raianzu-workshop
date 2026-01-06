<?php
include 'includes/auth.php';
include '../database/config.php';

$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pesan Pelanggan - Arman Jaya</title>
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
    .table thead th{color:#fff;border:none;background:linear-gradient(135deg,#ff6a00,#e75d00);letter-spacing:0.4px;text-transform:uppercase;font-weight:700;font-size:0.85rem;}
    .table tbody td{color:#e8edf5;vertical-align:middle;border-color:rgba(255,255,255,0.08);} 
    .table>:not(caption)>*>*{background:transparent!important;} 
    .modern-table tbody tr>*{background:rgba(0,0,0,0.18);} 
    .modern-table tbody tr:nth-of-type(odd)>*{background:rgba(0,0,0,0.22);} 
    .modern-table tbody tr:hover>*{background:rgba(255,255,255,0.08);color:#fff;box-shadow:inset 4px 0 0 #ff6a00;} 
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
        <li class="nav-item"><a class="nav-link" href="parts.php"><i class="fas fa-gears"></i> Onderdil</a></li>
        <li class="nav-item"><a class="nav-link active" href="messages.php"><i class="fas fa-envelope"></i> Pesan</a></li>
        <li class="nav-item"><a class="nav-link logout-btn" href="logout.php"><i class="fas fa-right-from-bracket"></i> Logout</a></li>
      </ul>
    </div>

    <div class="main-content">
      <div class="d-flex align-items-center mb-3" style="gap:0.5rem;">
        <div style="width:10px;height:10px;border-radius:50%;background:#ff6a00;"></div>
        <h3 style="margin:0;font-weight:700;">Pesan Masuk</h3>
      </div>

      <div class="glass-card">
        <div class="section-title">Daftar Pesan Pelanggan</div>
        <div class="table-responsive">
          <table class="table table-striped table-hover modern-table align-middle mb-0">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Pesan</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody>
            <?php $no = 1; while ($msg = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($msg['nama']) ?></td>
                <td><?= htmlspecialchars($msg['email']) ?></td>
                <td><?= nl2br(htmlspecialchars($msg['pesan'])) ?></td>
                <td><?= $msg['created_at'] ?></td>
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
