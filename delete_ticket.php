<?php
session_start();
include("config/db.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticket_id = $_POST["ticket_id"];

    $stmt = $conn->prepare("DELETE FROM tickets WHERE ticket_id = ?");
    $stmt->execute([$ticket_id]);

    header("Location: admin_dashboard.php");
    exit();
}
?>