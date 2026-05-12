<?php
session_start();
include("config/db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("SELECT * FROM tickets WHERE user_id = ? ORDER BY ticket_id DESC");
$stmt->execute([$user_id]);
$tickets = $stmt->fetchAll();

$open = 0;
$progress = 0;
$resolved = 0;
$closed = 0;

foreach ($tickets as $t) {
    if ($t["status"] == "open") $open++;
    if ($t["status"] == "in-progress") $progress++;
    if ($t["status"] == "resolved") $resolved++;
    if ($t["status"] == "closed") $closed++;
}

function badgeClass($status) {
    if ($status == "open") return "badge-open";
    if ($status == "in-progress") return "badge-progress";
    if ($status == "resolved") return "badge-resolved";
    if ($status == "closed") return "badge-closed";
    return "badge-open";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Tickets — YIC IT Support</title>
  <link rel="stylesheet" href="/yic-it-support/assets/css/style.css" />
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
      <li><a href="index.html"><i class="fa fa-home"></i> Home</a></li>
      <li><a href="submit_ticket.php"><i class="fa fa-ticket-alt"></i> Submit Ticket</a></li>
      <li><a href="my_tickets.php" class="active"><i class="fa fa-list"></i> My Tickets</a></li>
      <li><a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </nav>
</header>

<div class="page-hero">
  <div class="container">
    <h1><i class="fa fa-list-alt"></i> My Support Tickets</h1>
    <p>Track and monitor all your submitted IT support requests.</p>
  </div>
</div>

<main>
<div class="container">

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon yellow"><i class="fa fa-folder-open"></i></div>
      <div class="stat-info">
        <div class="stat-label">Open</div>
        <div class="stat-value"><?= $open ?></div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon blue"><i class="fa fa-spinner"></i></div>
      <div class="stat-info">
        <div class="stat-label">In Progress</div>
        <div class="stat-value"><?= $progress ?></div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon green"><i class="fa fa-check-circle"></i></div>
      <div class="stat-info">
        <div class="stat-label">Resolved</div>
        <div class="stat-value"><?= $resolved ?></div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon red"><i class="fa fa-times-circle"></i></div>
      <div class="stat-info">
        <div class="stat-label">Closed</div>
        <div class="stat-value"><?= $closed ?></div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header d-flex justify-between align-center flex-wrap gap-2">
      <h2 class="card-title"><i class="fa fa-ticket-alt"></i> All Tickets</h2>
      <a href="submit_ticket.php" class="btn btn-accent btn-sm">
        <i class="fa fa-plus"></i> New Ticket
      </a>
    </div>

    <div class="ticket-list">

      <?php if (count($tickets) == 0): ?>
        <div class="empty-state">
          <div class="empty-icon">🎫</div>
          <h3>No tickets found</h3>
          <p>You have not submitted any support tickets yet.</p>
          <a href="submit_ticket.php" class="btn btn-accent">Submit Ticket</a>
        </div>
      <?php endif; ?>

      <?php foreach ($tickets as $ticket): ?>
      <div class="ticket-card" data-status="<?= htmlspecialchars($ticket["status"]) ?>">
        <div>
          <div class="ticket-id">#TKT-<?= htmlspecialchars($ticket["ticket_id"]) ?></div>
          <div class="ticket-title"><?= htmlspecialchars($ticket["title"]) ?></div>
          <div class="ticket-meta"><?= htmlspecialchars($ticket["description"]) ?></div>
        </div>

        <div class="ticket-actions">
          <span class="badge <?= badgeClass($ticket["status"]) ?>">
            <?= htmlspecialchars($ticket["status"]) ?>
          </span>
        </div>
      </div>
      <?php endforeach; ?>

    </div>
  </div>

</div>
</main>

<footer>
  <div class="container">
    <p>&copy; 2026 <strong>YIC IT Support Portal</strong> — CS381 Web Application Development</p>
  </div>
</footer>

</body>
</html>