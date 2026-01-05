<?php
include 'includes/auth.php';
include '../database/config.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - Arman Jaya</title>

  <!-- Bootstrap & FontAwesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  
  <style>
    body {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      font-family: 'Poppins', sans-serif;
      color: #333;
    }
    .sidebar {
      background: #1e3c72;
      background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
      width: 250px;
      min-height: 100vh;
    }
    .sidebar h4 {
      font-weight: 600;
      color: #fff;
    }
    .sidebar .nav-link {
      color: #ddd;
      font-weight: 500;
      border-radius: 10px;
      transition: all 0.2s;
    }
    .sidebar .nav-link:hover {
      background: rgba(255,255,255,0.2);
      color: #fff;
    }
    .card {
      border: none;
      border-radius: 15px;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
      transform: translateY(-4px);
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    h3 {
      font-weight: 600;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="d-flex">
    <div class="sidebar text-white p-3">
      <h4 class="text-center mb-4">🛠️ Arman Jaya Admin</h4>
      <ul class="nav flex-column">
        <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link text-white"><i class="fa-solid fa-chart-line"></i> Dashboard</a></li>
        <li class="nav-item mb-2"><a href="services.php" class="nav-link text-white"><i class="fa-solid fa-screwdriver-wrench"></i> Layanan</a></li>
        <li class="nav-item mb-2"><a href="parts.php" class="nav-link text-white"><i class="fa-solid fa-gears"></i> Onderdil</a></li>
        <li class="nav-item mb-2"><a href="messages.php" class="nav-link text-white"><i class="fa-solid fa-envelope"></i> Pesan</a></li>
        <li class="nav-item mt-3"><a href="logout.php" class="nav-link text-danger"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
      <h3>Selamat Datang, <span class="text-primary"><?= htmlspecialchars($_SESSION['username']); ?></span></h3>
      <hr>

      <div class="row g-4 mt-3">
        <div class="col-md-4">
          <div class="card shadow-sm border-0 text-center p-3">
            <i class="fa-solid fa-screwdriver-wrench fa-2x text-primary mb-2"></i>
            <h5>Total Layanan</h5>
            <p class="fs-4 fw-bold text-dark">
              <?php
                $result = $conn->query("SELECT COUNT(*) AS total FROM services");
                echo $result->fetch_assoc()['total'] ?? 0;
              ?>
            </p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card shadow-sm border-0 text-center p-3">
            <i class="fa-solid fa-gear fa-2x text-success mb-2"></i>
            <h5>Stok Onderdil</h5>
            <p class="fs-4 fw-bold text-dark">
              <?php
                $result = $conn->query("SELECT SUM(stok) AS total FROM parts");
                echo $result->fetch_assoc()['total'] ?? 0;
              ?>
            </p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card shadow-sm border-0 text-center p-3">
            <i class="fa-solid fa-envelope fa-2x text-warning mb-2"></i>
            <h5>Pesan Masuk</h5>
            <p class="fs-4 fw-bold text-dark">3</p>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
