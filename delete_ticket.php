<?php
session_start();
include("config/db.php");

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION["admin_id"])) {
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

    $ticket_id = $_POST["ticket_id"];

    $stmt = $conn->prepare("DELETE FROM tickets WHERE ticket_id = ?");
    $stmt->execute([$ticket_id]);

    header("Location: admin_dashboard.php");
    exit();
}
?>
