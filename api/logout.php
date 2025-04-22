<?php
session_start(); // بدء الجلسة
session_unset(); // إلغاء جميع متغيرات الجلسة
session_destroy(); // تدمير الجلسة
header("Location: index.php"); // إعادة توجيه المستخدم إلى الصفحة الرئيسية
exit(); // إنهاء السكربت
?>
