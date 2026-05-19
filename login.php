<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("config/db.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check admin first
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin["password"])) {
        $_SESSION["admin_id"] = $admin["admin_id"];
        $_SESSION["role"] = "admin";
        header("Location: admin_dashboard.php");
        exit();
    }

    // Check user
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["role"] = "user";
        header("Location: my_tickets.php");
        exit();
    }

    $error = "Invalid email or password";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login — YIC IT Support</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body class="page-wrapper">

<header>
  <nav class="navbar container">
    <a href="index.html" class="navbar-brand">
      <div class="logo-icon">IT</div>
      <div>
        YIC IT Support
        <span>Yanbu Industrial College</span>
      </div>
    </a>

    <ul class="nav-links">
      <li><a href="index.html">Home</a></li>
      <li><a href="submit_ticket.php">Submit Ticket</a></li>
      <li><a href="my_tickets.php">My Tickets</a></li>
      <li><a href="login.php" class="active">Login</a></li>
      <li><a href="register.html">Register</a></li>
    </ul>
  </nav>
</header>

<main>
  <div class="auth-wrap">
    <div class="auth-card">

      <div class="auth-logo">
        <div class="logo-big">IT</div>
        <h2>Login</h2>
        <p class="auth-subtitle">Access your account</p>
      </div>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="login.php">
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-lg">
          <i class="fa fa-sign-in-alt"></i> Login
        </button>
      </form>

      <p class="auth-link">
        Don't have an account? <a href="register.html">Register</a>
      </p>

    </div>
  </div>
</main>

<footer>
  <div class="container">
    <p>&copy; 2026 <strong>YIC IT Support Portal</strong></p>
  </div>
</footer>

</body>
</html>
