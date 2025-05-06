<?php
include 'config.php';
require __DIR__ . '/../vendor/autoload.php'; // Include the Cloudinary SDK

use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;
// التحقق من وجود الكوكيز
if (!isset($_COOKIE['user_id'])) {
    header("Location: /");
    exit;
}

$user_id = $_COOKIE['user_id']; // استخدام الكوكيز للحصول على user_id

// التحقق من وجود معرف الطبيب
if (!isset($_GET['id'])) {
    echo "لم يتم تحديد الطبيب.";
    exit;
}

$doctor_id = intval($_GET['id']);

// جلب معلومات الطبيب من جدول users (لأنك تخزن الأطباء هناك)
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ? AND role = 'doctor'");
$stmt->execute([$doctor_id]);
$doctor = $stmt->fetch();

if (!$doctor) {
    echo "الطبيب غير موجود.";
    exit;
}

// جلب المنشورات الخاصة بالطبيب من جدول posts
$posts_stmt = $conn->prepare("SELECT * FROM posts WHERE doctor_id = ? ORDER BY created_at DESC");
$posts_stmt->execute([$doctor_id]);
$posts = $posts_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>ملف الطبيب</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Design/doctor_profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link rel="stylesheet" type="text/css" href="https://www.fontstatic.com/f=geeza-pro" />

</head>
<body>
<header>
        <h2><i class="fa-solid fa-stethoscope"></i> Rezadok</h2>
        <nav> 
            <a href="patient_dashboard" class="icon-btn" data-text="الرئيسية">
                <i class="fa-solid fa-house"></i>
            </a>
        </nav>
    </header>
    <div class="container">
         <!-- معلومات الطبيب -->
         <div class="doctor-info">
         <img src="<?php echo !empty($doctor['profile_picture']) ? htmlspecialchars($doctor['profile_picture']) : 'assets/default-doctor.jpg'; ?>" 
     alt="profile_picture" 
     style="max-width: 200px;">
            <h2>د. <?php echo htmlspecialchars($doctor['name']); ?></h2>
            <p><strong>التخصص:</strong> <?php echo htmlspecialchars($doctor['specialty']); ?></p>
            <p><strong>البريد الإلكتروني:</strong> <?php echo htmlspecialchars($doctor['email']); ?></p>
            <?php if (!empty($doctor['bio'])): ?>
                <div class="doctor-bio">
                    <p><strong>نبذة:</strong> <?php echo nl2br(htmlspecialchars($doctor['bio'])); ?></p>
                </div>
            <?php endif; ?>

            <!-- زر حجز موعد -->
            <a href="book_appointments?doctor_id=<?php echo $doctor_id; ?>" class="btn-book"><strong>حجز موعد</strong></a>
        </div>

        <!-- المنشورات الخاصة بالطبيب -->
        <div class="doctor-posts">
            <h3>المنشورات</h3>
            <?php if (count($posts) > 0): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <?php if (!empty($post['image'])): ?>
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="صورة المنشور" style="max-width: 200px; height: auto;">
                        <?php endif; ?>
                        <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                        <small>بتاريخ: <?php echo htmlspecialchars($post['created_at']); ?></small>
                        <hr style="border: 1px solid #ccc; margin: 20px 0;">
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>لا توجد منشورات بعد.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
