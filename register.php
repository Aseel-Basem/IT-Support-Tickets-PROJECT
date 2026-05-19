<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $student_id = trim($_POST["student_id"]);
    $role = "student";
    $password = $_POST["password"];

    if (empty($full_name) || empty($email) || empty($student_id) || empty($password)) {
        echo "All fields are required!";
        exit();
    }

    if (!preg_match('/@yic\.edu\.sa$/', $email)) {
        echo "Use YIC university email only!";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {
            echo "Email already exists!";
            exit();
        }

        $sql = "INSERT INTO users (full_name, email, student_id, role, password)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$full_name, $email, $student_id, $role, $hashed_password]);

        header("Location: login.php");
        exit();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

header("Location: register.html");
exit();
?>
