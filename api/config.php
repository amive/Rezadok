<?php
$host = $_ENV['DB_HOST']; // Use $_ENV to fetch environment variables
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$port = $_ENV['DB_PORT'];

// إنشاء اتصال باستخدام PDO
try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>