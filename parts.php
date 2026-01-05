<?php
include 'database/config.php';

// Ambil data onderdil
$limit = 12;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$stmt = $conn->prepare("SELECT id, nama, stok, harga, created_at FROM parts ORDER BY id DESC LIMIT ? OFFSET ?");
$stmt->bind_param('ii', $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
$parts = $result->fetch_all(MYSQLI_ASSOC);

// hitung total
$totalRes = $conn->query("SELECT COUNT(*) AS c FROM parts");
$totalRow = $totalRes->fetch_assoc();
$total = (int)$totalRow['c'];
$totalPages = max(1, ceil($total / $limit));
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Onderdil - Arman Jaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css?v=20260104_1" rel="stylesheet">
  </head>
  <body>
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <a class="navbar-brand brand" href="index.html">Arman Jaya</a>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navmenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navmenu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.html">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="services.php">Layanan</a></li>
          <li class="nav-item"><a class="nav-link" href="parts.php">Onderdil</a></li>
          <li class="nav-item"><a class="nav-link" href="about.html">Tentang</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Kontak</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <header class="py-5 text-center hero">
    <div class="container hero-inner">
      <h1>Katalog Onderdil Arman Jaya</h1>
      <p class="lead">Onderdil berkualitas dengan stok terupdate — langsung hubungi kami untuk pemesanan dan penawaran.</p>
      <div class="d-flex justify-content-center mt-3">
        <a href="contact.php" class="btn btn-lg btn-contact">Hubungi Sekarang</a>
      </div>
    </div>
  </header>

  <section class="py-5">
    <div class="container">
      <div class="row g-4">
        <?php if (count($parts) === 0): ?>
          <div class="col-12 text-center text-muted">Belum ada onderdil.</div>
        <?php endif; ?>

        <!-- Testimonials / trust -->
        <div class="col-12 my-4">
          <div class="testimonials rounded">
            <div class="container">
              <div class="row">
                <div class="col-md-4 text-center">
                  <div class="card p-3"> <i class="fa fa-shield-alt fa-2x mb-2"></i>
                    <h6 class="mb-1">Garansi Pengerjaan</h6>
                    <p class="small">Kerja rapi dengan garansi 7 hari untuk layanan tertentu.</p>
                  </div>
                </div>
                <div class="col-md-4 text-center">
                  <div class="card p-3"> <i class="fa fa-truck fa-2x mb-2"></i>
                    <h6 class="mb-1">Pengiriman Onderdil</h6>
                    <p class="small">Layanan kirim cepat ke seluruh kota.</p>
                  </div>
                </div>
                <div class="col-md-4 text-center">
                  <div class="card p-3"> <i class="fa fa-user-check fa-2x mb-2"></i>
                    <h6 class="mb-1">Teknisi Bersertifikat</h6>
                    <p class="small">Mekanik berpengalaman untuk semua jenis kendaraan.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <?php foreach ($parts as $part): ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="card product-card h-100">
            <?php $imgPath = 'assets/img/parts/' . ($part['image'] ?? ''); ?>
            <?php if (!empty($part['image']) && file_exists(__DIR__ . '/' . $imgPath)): ?>
              <a href="part.php?id=<?= $part['id'] ?>">
                <img src="<?= $imgPath ?>" loading="lazy" class="card-img-top" alt="<?= htmlspecialchars($part['nama']) ?>">
                <div class="overlay"><a href="part.php?id=<?= $part['id'] ?>"><i class="fa fa-eye"></i> Lihat</a></div>
              </a>
            <?php else: ?>
              <a href="part.php?id=<?= $part['id'] ?>">
                <img src="assets/img/onderdil.jpg" loading="lazy" class="card-img-top" alt="<?= htmlspecialchars($part['nama']) ?>">
                <div class="overlay"><a href="part.php?id=<?= $part['id'] ?>"><i class="fa fa-eye"></i> Lihat</a></div>
              </a>
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title mb-1"><a href="part.php?id=<?= $part['id'] ?>" style="text-decoration:none;color:inherit"><?= htmlspecialchars($part['nama']) ?></a></h5>
              <?php if (!empty($part['deskripsi'])): ?><p class="small text-muted mb-2"><?= htmlspecialchars($part['deskripsi']) ?></p><?php endif; ?>
              <p class="product-price mb-2">Rp <?= number_format($part['harga'],0,',','.') ?></p>
              <?php if ($part['stok'] <= 0): ?>
                <span class="badge bg-danger product-badge mb-2">Habis</span>
              <?php else: ?>
                <span class="badge bg-success product-badge mb-2">Stok: <?= (int)$part['stok'] ?></span>
              <?php endif; ?>

              <div class="d-flex gap-2 mt-auto">
                <a href="contact.php?product=<?= urlencode($part['nama']) ?>" class="btn btn-outline-secondary btn-sm w-100">Minta Penawaran</a>
                <a href="part.php?id=<?= $part['id'] ?>" class="btn btn-primary btn-sm w-100">Detail</a>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- pagination -->
      <nav class="mt-4">
        <ul class="pagination justify-content-center">
          <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <li class="page-item <?= $p === $page ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $p ?>"><?= $p ?></a></li>
          <?php endfor; ?>
        </ul>
      </nav>
    </div>
  </section>

  <footer class="footer mt-5">
    <div class="container footer-inner">
      <div class="row">
        <div class="col-md-6">
          <h5 style="color:#fff">Arman Jaya</h5>
          <p>Servis kendaraan cepat, tepat, dan terpercaya. Jl. Kapten Dulasim No.169, Jegong, Pulopancikan, Kec. Gresik, Kabupaten Gresik, Jawa Timur 61124.</p>
          <p><i class="fa fa-phone me-2"></i>0897-0180-971 &nbsp; <i class="fa fa-envelope ms-3 me-2"></i>masryansyaha@gmail.com</p>
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
      <p class="text-center small">© <?= date('Y') ?> Arman Jaya</p>
    </div>
  </footer>
  <button id="backToTop" aria-label="Back to top"><i class="fa fa-chevron-up"></i></button>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>