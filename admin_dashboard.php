<?php
session_start();
include("config/db.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT tickets.*, users.full_name, users.email
    FROM tickets
    JOIN users ON tickets.user_id = users.user_id
    ORDER BY ticket_id DESC
");
$stmt->execute();
$tickets = $stmt->fetchAll();

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
  <meta charset="UTF-8">
  <title>Admin Dashboard — YIC IT Support</title>
  <link rel="stylesheet" href="/yic-it-support/assets/css/style.css">
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
      <li><a href="admin_dashboard.php" class="active"><i class="fa fa-tachometer-alt"></i> Admin Dashboard</a></li>
      <li><a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </nav>
</header>

<div class="page-hero">
  <div class="container">
    <h1><i class="fa fa-tachometer-alt"></i> Admin Dashboard</h1>
    <p>View all submitted tickets, update their status, or delete tickets.</p>
  </div>
</div>

<main>
  <div class="container">
    <div class="card">
      <div class="card-header">
        <h2 class="card-title"><i class="fa fa-ticket-alt"></i> All Submitted Tickets</h2>
      </div>

      <?php if (count($tickets) == 0): ?>
        <div class="empty-state">
          <div class="empty-icon">🎫</div>
          <h3>No tickets found</h3>
          <p>No users have submitted tickets yet.</p>
        </div>
      <?php endif; ?>

      <?php foreach ($tickets as $t): ?>
        <div class="ticket-card">
          <div>
            <div class="ticket-id">
              #TKT-<?= htmlspecialchars($t["ticket_id"]) ?> —
              <?= htmlspecialchars($t["full_name"]) ?>
              (<?= htmlspecialchars($t["email"]) ?>)
            </div>

            <div class="ticket-title">
              <?= htmlspecialchars($t["title"]) ?>
            </div>

            <div class="ticket-meta">
              <?= htmlspecialchars($t["description"]) ?>
            </div>
          </div>

          <div class="ticket-actions">
            <span class="badge <?= badgeClass($t["status"]) ?>">
              <?= htmlspecialchars($t["status"]) ?>
            </span>

            <form action="update_status.php" method="POST" class="d-flex gap-2">
              <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($t["ticket_id"]) ?>">

              <select name="status" class="form-control" required>
                <option value="open" <?= $t["status"] == "open" ? "selected" : "" ?>>Open</option>
                <option value="in-progress" <?= $t["status"] == "in-progress" ? "selected" : "" ?>>In Progress</option>
                <option value="resolved" <?= $t["status"] == "resolved" ? "selected" : "" ?>>Resolved</option>
                <option value="closed" <?= $t["status"] == "closed" ? "selected" : "" ?>>Closed</option>
              </select>

              <button type="submit" class="btn btn-primary btn-sm">
                Update
              </button>
            </form>

            <form action="delete_ticket.php" method="POST" onsubmit="return confirm('Delete this ticket?');">
              <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($t["ticket_id"]) ?>">
              <button type="submit" class="btn btn-danger btn-sm">
                Delete
              </button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>

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