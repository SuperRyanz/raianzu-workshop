<?php
include 'database/config.php';

// Check if services table has image column
$check = $conn->query("SHOW COLUMNS FROM services LIKE 'image'");
if ($check->num_rows === 0) {
    $conn->query("ALTER TABLE services ADD COLUMN image VARCHAR(255) DEFAULT NULL");
}

$stmt = $conn->prepare("SELECT id, nama, deskripsi, harga, image FROM services ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();
$services = $result->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Layanan - Arman Jaya</title>
    <meta name="description" content="Layanan servis dan perawatan dari Arman Jaya.">
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <meta property="og:title" content="Arman Jaya - Layanan">
    <meta property="og:description" content="Lihat layanan servis kami, termasuk ganti oli dan tune-up.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css?v=20260104_2" rel="stylesheet">
  </head>
  <body>
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container d-flex align-items-center">
      <a class="navbar-brand brand" href="index.html"><i class="fas fa-tools me-2"></i>Arman Jaya</a>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navmenu"></button>
      <div class="collapse navbar-collapse" id="navmenu">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item"><a class="nav-link text-light" href="index.html">Beranda</a></li>
          <li class="nav-item"><a class="nav-link text-light" href="services.php">Layanan</a></li>
          <li class="nav-item"><a class="nav-link text-light" href="parts.php">Onderdil</a></li>
          <li class="nav-item"><a class="nav-link text-light" href="about.html">Tentang</a></li>
          <li class="nav-item"><a class="nav-link text-light" href="contact.php">Kontak</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <section class="py-5">
    <div class="container">
      <div class="section-panel panel-overlay">
      <h2 class="mb-4">Layanan Kami</h2>
      <div class="row g-4">
        <?php if (count($services) === 0): ?>
          <div class="col-12 text-center text-muted">Belum ada layanan.</div>
        <?php endif; ?>

        <?php foreach ($services as $s): ?>
        <div class="col-md-6">
          <div class="card service-card h-100">
            <div class="row g-0">
              <div class="col-4">
                <?php 
                  $imgPath = $s['image'] ? 'assets/img/services/' . $s['image'] : 'assets/img/motor.jpg';
                  $defaultImg = file_exists(__DIR__ . '/' . $imgPath) ? $imgPath : 'assets/img/motor.jpg';
                ?>
                <img src="<?= htmlspecialchars($defaultImg) ?>" class="img-fluid" loading="lazy" width="300" height="180" alt="<?= htmlspecialchars($s['nama']) ?>">
              </div>
              <div class="col-8">
                <div class="card-body">
                  <h5 class="card-title"><?= htmlspecialchars($s['nama']) ?></h5>
                  <p class="card-text"><?= htmlspecialchars($s['deskripsi']) ?></p>
                  <p><strong>Harga:</strong> Rp <?= number_format($s['harga'],0,',','.') ?></p>
                  <a href="contact.php?service=<?= urlencode($s['nama']) ?>" class="btn btn-primary btn-sm mt-2">Pesan Sekarang</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>

      </div>
      </div>
    </div>
  </section>

  <footer class="footer mt-5">
    <div class="container footer-inner">
      <div class="row">
        <div class="col-md-6">
          <div class="footer-brand"><i class="fas fa-tools"></i><span>Arman Jaya</span></div>
          <p>Servis kendaraan cepat, tepat, dan terpercaya. Jl. Kapten Dulasim No.169, Jegong, Pulopancikan, Kec. Gresik, Kabupaten Gresik, Jawa Timur 61124.</p>
        </div>
        <div class="col-md-3">
          <h6 style="color:#fff">Jam Operasional</h6>
          <p>Senin - Sabtu: 08.00 - 18.00</p>
        </div>
        <div class="col-md-3">
          <h6 style="color:#fff">Ikuti Kami</h6>
          <p class="social"><a href="https://instagram.com/arman-jaya" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a> &nbsp; <a href="https://www.tiktok.com/@arman-jaya" target="_blank" rel="noopener noreferrer"><i class="fab fa-tiktok"></i></a></p>
        </div>
      </div>
      <hr class="footer-sep">
      <p class="text-center small">© <?= date('Y') ?> Arman Jaya. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>