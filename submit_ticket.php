<?php
session_start();
include("config/db.php");

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (
        !isset($_POST['csrf_token']) ||
        $_POST['csrf_token'] !== $_SESSION['csrf_token']
    ) {
        die("Invalid CSRF token");
    }

    $user_id = $_SESSION["user_id"];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $status = "open";

    $stmt = $conn->prepare("INSERT INTO tickets (user_id, title, description, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $description, $status]);

    header("Location: my_tickets.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Submit Ticket — YIC IT Support</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body class="page-wrapper">

<header>
  <nav class="navbar container">
    <a href="index.html" class="navbar-brand">
      <div class="logo-icon">IT</div>
      <div>YIC IT Support<span>Yanbu Industrial College</span></div>
    </a>

    <ul class="nav-links">
      <li><a href="index.html"><i class="fa fa-home"></i> Home</a></li>
      <li><a href="submit_ticket.php" class="active"><i class="fa fa-ticket-alt"></i> Submit Ticket</a></li>
      <li><a href="my_tickets.php"><i class="fa fa-list"></i> My Tickets</a></li>
      <li><a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </nav>
</header>

<div class="page-hero">
  <div class="container">
    <h1><i class="fa fa-paper-plane"></i> Submit a Support Ticket</h1>
    <p>Describe your IT issue and submit your request.</p>
  </div>
</div>

<main>
  <div class="container">
    <div class="card">
      <div class="card-header">
        <h2 class="card-title"><i class="fa fa-edit"></i> Ticket Details</h2>
      </div>

      <form method="POST" action="submit_ticket.php">

        <input type="hidden" name="csrf_token"
        value="<?= $_SESSION['csrf_token']; ?>">

        <div class="form-group">
          <label>Ticket Title</label>
          <input type="text" name="title" class="form-control" required>
        </div>

        <div class="form-group">
          <label>Description</label>
          <textarea name="description" class="form-control" rows="6" required></textarea>
        </div>

        <button type="submit" class="btn btn-accent btn-block btn-lg">
          Submit Ticket
        </button>

      </form>
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
