<?php
include 'config.php';

// التحقق مما إذا كان المستخدم طبيبًا باستخدام الكوكيز
if (!isset($_COOKIE['user_id']) || $_COOKIE['role'] != 'doctor') {
    header("Location: index.php");
    exit();
}

$doctor_id = $_COOKIE['user_id'];

// عند إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // معالجة رفع الصورة
    $image_name = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_exts)) {
            $image_name = uniqid() . '.' . $file_ext;
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image_name);
        } else {
            setcookie('error_message', "❌ نوع الصورة غير مدعوم!", time() + 3600, "/");
            header("Location: add_post.php");
            exit();
        }
    }

    // إدخال المنشور في قاعدة البيانات
    $stmt = $conn->prepare("INSERT INTO posts (doctor_id, title, content, image, created_at) VALUES (?, ?, ?, ?, NOW())");
    if ($stmt->execute([$doctor_id, $title, $content, $image_name])) {
        setcookie('success_message', "✅ تم نشر المنشور بنجاح!", time() + 3600, "/");
        header("Location: doctor_dashboard.php");
        exit();
    } else {
        setcookie('error_message', "❌ حدث خطأ أثناء النشر!", time() + 3600, "/");
        header("Location: add_post.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة منشور</title>
    <link rel="stylesheet" href="css.css">   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<header>
    <h2><i class="fa-solid fa-stethoscope"></i> Rezadok</h2>
    <nav>
        <a href="discussion.php" class="icon-btn" data-text="الرئيسية">
            <i class="fa-solid fa-house"></i>
        </a>
        <a href="appointments.php" class="icon-btn" data-text="مواعيدي">
            <i class="fa-solid fa-calendar-days"></i>
        </a>
        <div class="dropdown">
            <button>
                <i class="fa-solid fa-user-circle"></i>
            </button>
            <div class="dropdown-content">
                <a href="#"><i class="fa-solid fa-user"></i> حسابي</a>
                <a href="#"><i class="fa-solid fa-cog"></i> الإعدادات</a>
                <a href="logout.php"><i class="fa-solid fa-sign-out-alt"></i> تسجيل الخروج</a>
            </div>
        </div>
    </nav>
</header>

<div class="container">
    <h2><i class="fa-solid fa-pen-to-square"></i> إضافة منشور جديد</h2>
    
    <?php if (isset($_COOKIE['success_message'])): ?>
        <div class="success-message"><?= $_COOKIE['success_message']; ?></div>
        <?php setcookie('success_message', '', time() - 3600, '/'); ?>
    <?php endif; ?>

    <?php if (isset($_COOKIE['error_message'])): ?>
        <div class="error-message"><?= $_COOKIE['error_message']; ?></div>
        <?php setcookie('error_message', '', time() - 3600, '/'); ?>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="title"><i class="fa-solid fa-heading"></i> عنوان المنشور:</label>
        <input type="text" id="title" name="title" required>

        <label for="content"><i class="fa-solid fa-file-alt"></i> المحتوى:</label>
        <textarea id="content" name="content" rows="5" required></textarea>

        <div class="file-upload-container">
            <label for="file-upload" class="icon-btn" id="custom-file-upload" data-text="رفع صورة">
                <i class="fa fa-upload"></i> 
            </label>
            <span id="file-name">لم يتم اختيار أي ملف</span>
            <input id="file-upload" type="file" name="image" accept="image/*">
        </div>

        <button type="submit"><i class="fa-solid fa-paper-plane"></i> نشر</button>
    </form>
</div>

<script>
    document.getElementById("file-upload").addEventListener("change", function() {
        var fileName = this.files.length > 0 ? this.files[0].name : "لم يتم اختيار أي ملف";
        document.getElementById("file-name").textContent = fileName;
    });
</script>

</body>
</html>
