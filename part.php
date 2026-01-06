<?php
include 'database/config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: parts.php');
    exit;
}

$stmt = $conn->prepare('SELECT * FROM parts WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$part = $res->fetch_assoc();
if (!$part) {
    header('Location: parts.php');
    exit;
}

// structured data
$ld = [
  "@context" => "https://schema.org/",
  "@type" => "Product",
  "name" => $part['nama'],
  "image" => (!empty($part['image']) ? ("https://example.com/assets/img/parts/" . $part['image']) : "https://example.com/assets/img/onderdil.jpg"),
  "description" => $part['deskripsi'] ?? '',
  "offers" => [
    "@type" => "Offer",
    "priceCurrency" => "IDR",
    "price" => (int)$part['harga'],
    "availability" => ((int)$part['stok'] > 0) ? "https://schema.org/InStock" : "https://schema.org/OutOfStock"
  ]
];
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($part['nama']) ?> - Arman Jaya</title>
    <meta name="description" content="<?= htmlspecialchars(substr($part['deskripsi'] ?? '',0,160)) ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css?v=20260104_1" rel="stylesheet">
    <script type="application/ld+json">
      <?= json_encode($ld, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) ?>
    </script>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <a class="navbar-brand brand" href="index.html"><i class="fas fa-tools me-2"></i>Arman Jaya</a>
      <div class="collapse navbar-collapse" id="navmenu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item ms-3"><a class="btn btn-contact" href="contact.php?product=<?= urlencode($part['nama']) ?>">Hubungi</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container py-5">
    <div class="product-detail">
      <div class="gallery">
        <?php $imgPath = 'assets/img/parts/' . ($part['image'] ?? ''); ?>
        <?php if (!empty($part['image']) && file_exists(__DIR__ . '/' . $imgPath)): ?>
          <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($part['nama']) ?>" class="img-fluid rounded">
        <?php else: ?>
          <img src="assets/img/onderdil.jpg" alt="<?= htmlspecialchars($part['nama']) ?>" class="img-fluid rounded">
        <?php endif; ?>
      </div>
      <div class="info">
        <h2><?= htmlspecialchars($part['nama']) ?></h2>
        <?php if (!empty($part['deskripsi'])): ?><p class="text-muted"><?= nl2br(htmlspecialchars($part['deskripsi'])) ?></p><?php endif; ?>
        <p class="product-price">Rp <?= number_format($part['harga'],0,',','.') ?></p>
        <?php if ((int)$part['stok'] <= 0): ?>
          <p class="text-danger">Stok: Habis</p>
        <?php else: ?>
          <p class="text-success">Stok: <?= (int)$part['stok'] ?></p>
        <?php endif; ?>

        <div class="mt-4">
          <a href="contact.php?product=<?= urlencode($part['nama']) ?>" class="btn btn-primary btn-lg">Hubungi untuk Order</a>
          <a href="parts.php" class="btn btn-outline-secondary btn-lg ms-2">Kembali ke Katalog</a>
        </div>
      </div>
    </div>
  </main>

  <footer class="footer mt-5">
    <div class="container text-center small">© <?= date('Y') ?> Arman Jaya</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>