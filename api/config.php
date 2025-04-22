<?php
$host = getenv('DB_HOST'); // Replace with your environment variable for the host
$dbname = getenv('DB_NAME'); // Replace with your environment variable for the database name
$username = getenv('DB_USER'); // Replace with your environment variable for the username
$password = getenv('DB_PASS'); // Replace with your environment variable for the password
$port = getenv('DB_PORT'); // Replace with your environment variable for the port

// إنشاء اتصال باستخدام PDO
try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>