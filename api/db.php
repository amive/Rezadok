<?php
$host = "localhost";
$username = "rezadok_user";
$password = "your_password";
$dbname = "rezadok";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}
?>
