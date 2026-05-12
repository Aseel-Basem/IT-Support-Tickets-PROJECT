<?php
session_start();
include("config/db.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.html");
    exit();
}

$ticket_id = $_POST["ticket_id"];
$status = $_POST["status"];

$stmt = $conn->prepare("UPDATE tickets SET status = ? WHERE ticket_id = ?");
$stmt->execute([$status, $ticket_id]);

header("Location: admin_dashboard.php");
exit();
?>