<?php
$images = [
  'assets/img/motor.jpg',
  'assets/img/oli.jpg',
  'assets/img/onderdil.jpg'
];
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Test Images - Arman Jaya</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>body{background:#111;color:#eee;padding:24px;font-family:sans-serif} img{max-width:320px;height:auto;margin:8px;border:2px solid #222;border-radius:6px;background:#000}</style>
</head>
<body>
  <div class="container">
    <h3>Image diagnostic</h3>
    <p>Open this page to verify images are served correctly. If an image is missing, you'll see a broken image icon and the <code>Exists</code> info will be "No".</p>
    <div class="row">
<?php foreach ($images as $img): ?>
  <div class="col-md-4">
    <h5><?= htmlspecialchars(basename($img)) ?></h5>
    <p>Path: <code><?= htmlspecialchars($img) ?></code></p>
    <p>Exists: <strong><?= file_exists(__DIR__ . '/' . $img) ? 'Yes' : 'No' ?></strong></p>
    <p>Filesize: <strong><?php if (file_exists(__DIR__ . '/' . $img)) echo number_format(filesize(__DIR__ . '/' . $img)) . ' bytes'; else echo '-'; ?></strong></p>
    <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars(basename($img)) ?>">
  </div>
<?php endforeach; ?>
    </div>
    <hr>
    <p><a href="index.html" class="btn btn-light">Back to Home</a></p>
  </div>
</body>
</html>