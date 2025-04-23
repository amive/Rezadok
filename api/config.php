<?php
$host = $_ENV['DB_HOST'] ?? 'sql7.freesqldatabase.com'; // Default to localhost if not set
$dbname = $_ENV['DB_NAME'] ?? 'sql7774920'; // Default to 'reza' if not set
$username = $_ENV['DB_USER'] ?? 'sql7774920'; // Default to 'root' if not set
$password = $_ENV['DB_PASS'] ?? '7vfTx9dfIL'; // Default to empty password if not set
$port = $_ENV['DB_PORT'] ?? '3306'; // Default to MySQL port 3306 if not set

// إنشاء اتصال باستخدام PDO
try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>