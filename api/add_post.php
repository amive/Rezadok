<?php
include 'config.php';
require __DIR__ . '/../vendor/autoload.php'; // Include the Cloudinary SDK

use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;
// التحقق مما إذا كان المستخدم طبيبًا باستخدام الكوكيز
if (!isset($_COOKIE['user_id']) || $_COOKIE['role'] != 'doctor') {
    header("Location: /");
    exit();
}

$doctor_id = $_COOKIE['user_id'];

// قراءة رسائل الكوكيز وتخزينها في متغيرات مؤقتة
$successMessage = null;
$errorMessage = null;

if (isset($_COOKIE['success_message'])) {
    $successMessage = $_COOKIE['success_message'];
    setcookie('success_message', '', time() - 3600, '/');
}

if (isset($_COOKIE['error_message'])) {
    $errorMessage = $_COOKIE['error_message'];
    setcookie('error_message', '', time() - 3600, '/');
}


// عند إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];


    $cloudinary = new Cloudinary([
        'cloud' => [
            'cloud_name' => 'dkxmhw89v',
            'api_key'    => '856592243673251',
            'api_secret' => '6swgUqDkfTRe4Lyu52OHZHt0eJ8',
        ],
    ]);
    // معالجة رفع الصورة
    $image_url = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_exts)) {
            try {
                // رفع الصورة إلى Cloudinary
                $uploadApi = new UploadApi();
                $uploadResult = $cloudinary->uploadApi()->upload($_FILES['image']['tmp_name'], [
                    'folder' => 'posts/images',
                    'public_id' => uniqid(),
                    'resource_type' => 'image',
                ]);

                $image_url = $uploadResult['secure_url']; // رابط الصورة
            } catch (Exception $e) {
                setcookie('error_message', "❌ حدث خطأ أثناء رفع الصورة: " . $e->getMessage(), time() + 3600, "/");
                header("Location: add_post.php");
                exit();
            }
        } else {
            setcookie('error_message', "❌ نوع الصورة غير مدعوم!", time() + 3600, "/");
            header("Location: add_post.php");
            exit();
        }
    }

    // إدخال المنشور في قاعدة البيانات
    $stmt = $conn->prepare("INSERT INTO posts (doctor_id, title, content, image, created_at) VALUES (?, ?, ?, ?, NOW())");
    if ($stmt->execute([$doctor_id, $title, $content, $image_url])) {
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
    <link rel="stylesheet" href="Design/index.css">   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link rel="stylesheet" type="text/css" href="https://www.fontstatic.com/f=geeza-pro" />

</head>
<body>

<header>
    <h2><i class="fa-solid fa-stethoscope"></i> Rezadok</h2>
    <nav>
        <a href="/" class="icon-btn" data-text="الرئيسية">
            <i class="fa-solid fa-house"></i>
        </a>
        <a href="appointments" class="icon-btn" data-text="مواعيدي">
            <i class="fa-solid fa-calendar-days"></i>
        </a>
        <div class="dropdown">
            <button>
                <i class="fa-solid fa-user-circle"></i>
            </button>
            <div class="dropdown-content">
                <a href="settings"><i class="fa-solid fa-cog"></i> الإعدادات</a>
                <a href="logout"><i class="fa-solid fa-sign-out-alt"></i> تسجيل الخروج</a>
            </div>
        </div>
    </nav>
</header>

<div class="container">
    <h2><i class="fa-solid fa-pen-to-square"></i> إضافة منشور جديد</h2>
    
    <?php if ($successMessage): ?>
        <div class="success-message"><?= $successMessage; ?></div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div class="error-message"><?= $errorMessage; ?></div>
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

<script src="script/add_post.js"></script>

</body>
</html>
