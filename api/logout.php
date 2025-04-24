<?php
// إلغاء الكوكيز عن طريق تعيين وقت انتهاء في الماضي
setcookie('user_id', '', time() - 3600, '/'); // حذف cookie الخاص بـ user_id
setcookie('role', '', time() - 3600, '/'); // حذف cookie الخاص بـ role

// إعادة توجيه المستخدم إلى الصفحة الرئيسية
header("Location: index.php");
exit(); // إنهاء السكربت
?>
