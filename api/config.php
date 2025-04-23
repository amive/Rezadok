<?php
$host = $_ENV['DB_HOST'] ; // Default to localhost if not set
$dbname = $_ENV['DB_NAME'] ; // Default to 'reza' if not set
$username = $_ENV['DB_USER']; // Default to 'root' if not set
$password = $_ENV['DB_PASS'] ; // Default to empty password if not set
$port = $_ENV['DB_PORT']; // Default to MySQL port 3306 if not set

// إنشاء اتصال باستخدام PDO
try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>