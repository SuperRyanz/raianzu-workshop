<?php
include 'database/config.php';
include 'admin/includes/csrf.php';
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Kontak - Arman Jaya</title>
    <meta name="description" content="Hubungi Arman Jaya untuk servis dan info produk.">
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <meta property="og:title" content="Arman Jaya - Kontak">
    <meta property="og:description" content="Kirim pesan atau kunjungi workshop kami.">
    <meta property="og:image" content="assets/img/workshop-bg.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css?v=20260104_1" rel="stylesheet">
  </head>
  <body>
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <a class="navbar-brand brand" href="index.html"><i class="fas fa-tools me-2"></i>Arman Jaya</a>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navmenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navmenu">
        <ul class="navbar-nav ms-auto">
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
        <h2 class="mb-4">Kontak Kami</h2>
        <?php if (isset($_GET['sent'])): ?>
          <div class="alert alert-success">Pesan Anda telah terkirim. Terima kasih!</div>
        <?php elseif (isset($_GET['error'])): ?>
          <div class="alert alert-danger">Terjadi kesalahan mengirim pesan (<?= htmlspecialchars($_GET['error']) ?>).</div>
        <?php endif; ?>
        <div class="row">
          <div class="col-md-6">
            <form method="POST" action="contact_submit.php">
              <?= csrf_input() ?>
              <div class="mb-3" style="display:none;">
                <!-- honeypot field untuk bot -->
                <label class="form-label">Jangan isi</label>
                <input type="text" name="hp" class="form-control" placeholder=""> 
              </div>
              <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" placeholder="Nama Anda" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="email@domain.com" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Pesan</label>
                <textarea name="pesan" class="form-control" rows="5" required></textarea>
              </div>
              <button type="submit" class="btn btn-primary">Kirim Pesan</button>
            </form>
          </div>
          <div class="col-md-6">
            <h5>Lokasi</h5>
            <p>Jl. Kapten Dulasim No.169, Jegong, Pulopancikan, Kec. Gresik, Kabupaten Gresik, Jawa Timur 61124</p>
            <a href="https://www.google.com/maps/search/Jl.+Kapten+Dulasim+No.169+Jegong+Pulopancikan+Gresik" target="_blank" class="btn btn-sm btn-secondary mt-2">
              <i class="fas fa-map-location-dot"></i> Lihat di Maps
            </a>
            <h5 class="mt-4">Hubungi</h5>
            <p>WhatsApp: <strong>0897-0180-971</strong></p>
            <p>Email: <strong>masryansyaha@gmail.com</strong></p>
            <div class="d-flex gap-2 my-3">
              <a href="https://wa.me/628970180971?text=Halo%20Arman%20Jaya%2C%20saya%20ingin%20menanyakan%20tentang%20layanan%20dan%20onderdil." target="_blank" class="btn btn-success">
                <i class="fab fa-whatsapp"></i> Chat WhatsApp
              </a>
              <a href="mailto:masryansyaha@gmail.com" class="btn btn-primary">
                <i class="fas fa-envelope"></i> Email
              </a>
            </div>
            <h5>Jam Operasional</h5>
            <p>Senin - Sabtu: 08.00 - 18.00</p>
          </div>
        </div>
      </div>
    </div>
  </section>


  <footer class="footer mt-5">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="footer-brand"><i class="fas fa-tools"></i><span>Arman Jaya</span></div>
          <p>Servis kendaraan cepat, tepat, dan terpercaya. Jl. Kapten Dulasim No.169, Jegong, Pulopancikan, Kec. Gresik, Kabupaten Gresik, Jawa Timur 61124.</p>
        </div>
        <div class="col-md-3">
          <h6>Jam Operasional</h6>
          <p>Senin - Sabtu: 08.00 - 18.00</p>
        </div>
        <div class="col-md-3">
          <h6>Hubungi Kami</h6>
          <p>WhatsApp: 0897-0180-971</p>
          <p>Email: <strong>masryansyaha@gmail.com</strong></p>
        </div>
      </div>
      <hr style="border-color: rgba(255,255,255,0.1)">
      <p class="text-center small">© 2025 Arman Jaya. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/script.js"></script>
  </body>
</html>