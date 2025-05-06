<?php
// التحقق من تسجيل الدخول باستخدام الكوكيز
if (!isset($_COOKIE['user_id'])) {
    header("Location: /");
    exit();
}

include('config.php'); // ربط قاعدة البيانات

// استرجاع بيانات المستخدم من قاعدة البيانات
$user_id = $_COOKIE['user_id']; // استخدام الـ cookie بدلاً من الـ session
$sql = "SELECT * FROM users WHERE id = :id"; // استخدام :id للربط مع المعاملات
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT); // ربط المعامل باستخدام bindParam
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT); // تشفير كلمة المرور
        $update_sql = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id";
        $stmt = $conn->prepare($update_sql);
        // ربط المعاملات مع PDO
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    } else {
        $update_sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        $stmt = $conn->prepare($update_sql);
        // ربط المعاملات مع PDO
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    }

    if ($stmt->execute()) {
        $success_message = "تم تحديث البيانات بنجاح!";
    } else {
        $error_message = "حدث خطأ أثناء تحديث البيانات.";
    }
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Design/settings.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <title>إعدادات الحساب</title>
</head>
<body class="settings-body">
    <h1>إعدادات الحساب</h1>

    <?php if (isset($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php elseif (isset($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="name">الاسم:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        <br>
        <label for="email">البريد الإلكتروني:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <br>
        <label for="password">كلمة المرور الجديدة:</label>
        <input type="password" id="password" name="password">
        <br>
        <button type="submit">تحديث البيانات</button>
    </form>
    <footer>
        <div class="reza"> © 2025 جميع الحقوق محفوظة لـ<strong>Rezadok</strong></div>  
        <a href="https://www.github.com/amive" target="_blank" style="color: #390071;"><i class="fab fa-github" style="color: #390071;"></i>Amine</a> |
        <a href="https://www.github.com/bilalgarah" target="_blank" style="color: #a52a2a;"><i class="fab fa-github"style="color: #a52a2a;"></i>Bilal</a>
    </footer>
</body>
</html>
