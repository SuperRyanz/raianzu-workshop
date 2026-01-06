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
    * { box-sizing: border-box; margin: 0; padding: 0; }
    
    body {
      font-family: 'Poppins', sans-serif;
      color: #fff;
      background-image: url('../assets/img/bengkel.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      background-repeat: no-repeat;
      min-height: 100vh;
    }
    
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.48);
      z-index: -1;
    }
    
    .sidebar {
      background: rgba(15, 23, 42, 0.85);
      backdrop-filter: blur(8px);
      width: 280px;
      min-height: 100vh;
      border-right: 1px solid rgba(255, 255, 255, 0.1);
      position: sticky;
      top: 0;
    }
    
    .sidebar-header {
      padding: 2rem 1.5rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .sidebar h4 {
      font-weight: 700;
      color: #fff;
      font-size: 1.3rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .sidebar h4 i {
      color: #ff6a00;
      font-size: 1.5rem;
    }
    
    .sidebar .nav-link {
      color: #94a3b8;
      font-weight: 500;
      padding: 0.85rem 1.5rem;
      border-radius: 10px;
      transition: all 0.3s ease;
      margin: 0.3rem 0.75rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }
    
    .sidebar .nav-link:hover {
      background: rgba(255, 106, 0, 0.15);
      color: #ff6a00;
      transform: translateX(5px);
    }
    
    .sidebar .nav-link.active {
      background: linear-gradient(135deg, #ff6a00, #e75d00);
      color: #fff;
    }
    
    .sidebar .nav-link i {
      width: 20px;
      text-align: center;
    }
    
    .main-content {
      flex: 1;
      padding: 2rem;
      max-width: 1400px;
      margin: 0 auto;
    }
    
    .welcome-card {
      background: rgba(0, 0, 0, 0.52);
      backdrop-filter: blur(6px);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 16px;
      padding: 2rem;
      margin-bottom: 2rem;
    }
    
    .welcome-card h3 {
      font-weight: 700;
      font-size: 2rem;
      margin-bottom: 0.5rem;
    }
    
    .welcome-card .text-accent {
      color: #ff6a00;
    }
    
    .stat-card {
      background: rgba(0, 0, 0, 0.48);
      backdrop-filter: blur(6px);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 16px;
      padding: 1.75rem;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .stat-card::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(255, 106, 0, 0.1), transparent);
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(255, 106, 0, 0.3);
      border-color: rgba(255, 106, 0, 0.5);
    }
    
    .stat-card:hover::before {
      opacity: 1;
    }
    
    .stat-icon {
      width: 70px;
      height: 70px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      margin-bottom: 1rem;
    }
    
    .stat-icon.primary {
      background: linear-gradient(135deg, #3b82f6, #2563eb);
    }
    
    .stat-icon.success {
      background: linear-gradient(135deg, #10b981, #059669);
    }
    
    .stat-icon.warning {
      background: linear-gradient(135deg, #f59e0b, #d97706);
    }
    
    .stat-card h5 {
      font-size: 0.95rem;
      font-weight: 600;
      color: #cbd5e1;
      margin-bottom: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .stat-card .stat-value {
      font-size: 2.5rem;
      font-weight: 700;
      color: #fff;
      line-height: 1;
    }
    
    .logout-btn {
      color: #ef4444 !important;
      margin-top: 2rem;
    }
    
    .logout-btn:hover {
      background: rgba(239, 68, 68, 0.15) !important;
      color: #f87171 !important;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="d-flex">
    <div class="sidebar">
      <div class="sidebar-header">
        <h4><i class="fas fa-tools"></i> Arman Jaya</h4>
        <small style="color: #64748b; display: block; margin-top: 0.5rem;">Admin Panel</small>
      </div>
      <ul class="nav flex-column" style="padding: 1rem 0;">
        <li class="nav-item">
          <a href="dashboard.php" class="nav-link active">
            <i class="fas fa-chart-line"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a href="services.php" class="nav-link">
            <i class="fas fa-screwdriver-wrench"></i> Layanan
          </a>
        </li>
        <li class="nav-item">
          <a href="parts.php" class="nav-link">
            <i class="fas fa-gears"></i> Onderdil
          </a>
        </li>
        <li class="nav-item">
          <a href="messages.php" class="nav-link">
            <i class="fas fa-envelope"></i> Pesan
          </a>
        </li>
        <li class="nav-item">
          <a href="logout.php" class="nav-link logout-btn">
            <i class="fas fa-right-from-bracket"></i> Logout
          </a>
        </li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <div class="welcome-card">
        <h3>Selamat Datang, <span class="text-accent"><?= htmlspecialchars($_SESSION['username']); ?></span></h3>
        <p style="color: #cbd5e1; margin: 0;">Kelola workshop Anda dengan mudah dari panel admin ini</p>
      </div>

      <div class="row g-4">
        <div class="col-md-4">
          <div class="stat-card">
            <div class="stat-icon primary">
              <i class="fas fa-screwdriver-wrench"></i>
            </div>
            <h5>Total Layanan</h5>
            <div class="stat-value">
              <?php
                $result = $conn->query("SELECT COUNT(*) AS total FROM services");
                echo $result->fetch_assoc()['total'] ?? 0;
              ?>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="stat-card">
            <div class="stat-icon success">
              <i class="fas fa-gear"></i>
            </div>
            <h5>Stok Onderdil</h5>
            <div class="stat-value">
              <?php
                $result = $conn->query("SELECT SUM(stok) AS total FROM parts");
                echo $result->fetch_assoc()['total'] ?? 0;
              ?>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="stat-card">
            <div class="stat-icon warning">
              <i class="fas fa-envelope"></i>
            </div>
            <h5>Pesan Masuk</h5>
            <div class="stat-value">
              <?php
                $result = $conn->query("SELECT COUNT(*) AS total FROM messages");
                echo $result->fetch_assoc()['total'] ?? 0;
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
