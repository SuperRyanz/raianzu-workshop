<?php
session_start();
include '../database/config.php';

if (isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user) {
      if (password_verify($password, $user['password'])) {
          // login success: set session and harden it
          session_regenerate_id(true);
          $_SESSION['username'] = $user['username'];
          $_SESSION['admin_logged_in'] = true;
          header("Location: dashboard.php");
          exit;
      } else {
          $error = "Password salah!";
      }
  } else {
      $error = "Username tidak ditemukan!";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin - Arman Jaya</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    * { font-family: 'Poppins', sans-serif; }

    body {
      background: url('../assets/img/bengkel.jpg') no-repeat center center/cover;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    body::before {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(4px);
      z-index: 0;
    }

    .login-card {
      position: relative;
      z-index: 1;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      padding: 40px 35px;
      width: 380px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
      animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    h3 {
      text-align: center;
      font-weight: 600;
      margin-bottom: 25px;
      color: #222;
    }

    .form-label { font-weight: 500; color: #444; }

    .form-control {
      border-radius: 8px;
      border: 1px solid #ccc;
      padding: 10px 12px;
    }

    .form-control:focus {
      border-color: #FF6600;
      box-shadow: 0 0 0 0.2rem rgba(255, 102, 0, 0.25);
    }

    .btn-gradient {
      background: linear-gradient(90deg, #FF6600, #FF8533);
      border: none;
      color: white;
      font-weight: 500;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .btn-gradient:hover {
      background: linear-gradient(90deg, #e65c00, #ff751a);
      transform: scale(1.03);
    }

    .error {
      background: #ffe6e6;
      border: 1px solid #ff9999;
      color: #cc0000;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 15px;
      text-align: center;
    }

    footer {
      position: absolute;
      bottom: 10px;
      color: rgba(255,255,255,0.8);
      font-size: 0.9rem;
      text-align: center;
      width: 100%;
      z-index: 1;
    }
  </style>
</head>
<body>
  <div class="login-card">
    <h3>Login Admin</h3>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control" required autofocus>
      </div>

      <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-gradient w-100 py-2">Login</button>
    </form>
  </div>

  <footer>
    © <?= date("Y") ?> Arman Jaya. All rights reserved.
  </footer>
</body>
</html>
