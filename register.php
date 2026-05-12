<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("config/db.php");

// تأكد إن الطلب POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // استلام البيانات
    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $student_id = trim($_POST["student_id"]);
    $role = "student"; // ثابت
    $password = $_POST["password"];

    // تحقق بسيط
    if (empty($full_name) || empty($email) || empty($student_id) || empty($password)) {
        echo "All fields are required!";
        exit();
    }

    // تحقق من الايميل الجامعي
    if (!preg_match('/@([a-zA-Z0-9-]+\.)*university\.edu$/', $email)) {
        echo "Use university email only!";
        exit();
    }

    // تشفير الباسورد
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // تأكد الايميل مو مكرر
        $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {
            echo "Email already exists!";
            exit();
        }

        // إدخال البيانات
        $sql = "INSERT INTO users (full_name, email, student_id, role, password)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$full_name, $email, $student_id, $role, $hashed_password]);

        // نجاح → يروح للوجن
        header("Location: login.php");
        exit();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// لو دخل الصفحة بدون POST
header("Location: register.html");
exit();
?>