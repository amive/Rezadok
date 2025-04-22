<?php
$host = 'localhost'; // السيرفر المحلي
$dbname = 'reza'; // اسم قاعدة البيانات
$username = 'root'; // اسم المستخدم الافتراضي لـ XAMPP أو WAMP
$password = ''; // كلمة المرور الافتراضية (فارغة في XAMPP)

// إنشاء اتصال باستخدام PDO
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>
