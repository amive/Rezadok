<?php
// التحقق من تسجيل الدخول باستخدام الكوكيز
if (!isset($_COOKIE['user_id']) || strtolower(trim($_COOKIE['role'])) !== 'doctor') {
    error_log("DOCTOR user_id/Role check false. role: ");
    header("Location: /index.php");
    exit();
}

include('config.php'); // الاتصال بقاعدة البيانات

$doctor_id = $_COOKIE['user_id']; // استخدام الـ cookie بدلاً من الـ session

// استرجاع المنشورات الخاصة بالطبيب
$sql_posts = "SELECT * FROM posts WHERE doctor_id = :doctor_id ORDER BY created_at DESC";
$stmt_posts = $conn->prepare($sql_posts);
$stmt_posts->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
$stmt_posts->execute();
$posts = $stmt_posts->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezadok | لوحة تحكم الطبيب</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="Design/index.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">

</head>
<body>
    <header>
        <h2><a href="index.php"><i class="fa-solid fa-stethoscope"></i></a>Rezadok</h2>
        <nav>
            <a href="chat.php" class="icon-btn" data-text="الرسائل">
                <i class="fa-solid fa-comments"></i>
            </a>
            <a href="appointments.php" class="icon-btn" data-text="مواعيدي">
                <i class="fa-solid fa-calendar-days"></i>
            </a>
            <a href="add_post.php" class="icon-btn" data-text="إضافة منشور">
                <i class="fa-solid fa-plus"></i>
            </a>
            <div class="dropdown">
                <button>
                    <i class="fa-solid fa-user-circle"></i>
                </button>
                <div class="dropdown-content">
                    <a href="#"><i class="fa-solid fa-user"></i> حسابي</a>
                    <a href="settings.php"><i class="fa-solid fa-gear"></i> الإعدادات</a>
                    <a href="logout.php"><i class="fa-solid fa-sign-out-alt"></i> تسجيل الخروج</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- المنشورات -->
    <h2 id="h2-post"> المنشورات <i class="fa-solid fa-newspaper" style="color: #63E6BE;"></i></h2>

    <div class="posts">
        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <h3><?= htmlspecialchars($post['title']) ?></h3>
                    <p><?= htmlspecialchars($post['content']) ?></p>

                     <!-- Display the image if it exists -->
                <?php if (!empty($post['image'])): ?>
                    <img src="<?= htmlspecialchars($post['image']) ?>" alt="Post Image" style="max-width: 100%; height: auto; margin-top: 10px;">
                <?php endif; ?>
                
                    <p><small>تاريخ المنشور: <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></small></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>.لم تقم بنشر شيء بعد</p>
        <?php endif; ?>
    </div>
</body>
</html>
