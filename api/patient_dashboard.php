<?php
// استخدام الكوكيز بدلًا من session
$user_id = $_COOKIE['user_id'] ?? null;
$role = strtolower(trim($_COOKIE['role'] ?? ''));

// التحقق من تسجيل الدخول وصلاحية المريض
if (!$user_id || $role !== 'patient') {
    error_log("PATIENT user_id/Role check false from cookies.");
    header("Location: /index.php");
    exit();
}

include('config.php');

// استرجاع بيانات المستخدم
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// استرجاع منشورات الأطباء مع اسم الطبيب
$sql_posts = "SELECT p.*, u.name AS doctor_name 
              FROM posts p 
              JOIN users u ON p.doctor_id = u.id 
              ORDER BY p.created_at DESC";
$stmt_posts = $conn->prepare($sql_posts);
$stmt_posts->execute();
$posts = $stmt_posts->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezadok | منشورات الأطباء</title>
    <link rel="stylesheet" href="Design/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* [نفس التنسيقات بدون تغيير] */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 40px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .post {
            background-color: #ffffff;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-right: 180px;
            width: 60%;
        }

        .post:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .post img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .post h3 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .post p {
            color: #555;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .post small {
            font-size: 12px;
            color: #777;
            display: block;
            margin-top: 15px;
        }

        .container p {
            text-align: center;
            font-size: 18px;
            color: #888;
        }
    </style>
</head>
<body>

<header>
    <h2><a href="index.php"><i class="fa-solid fa-stethoscope"></i></a>Rezadok</h2>
    <nav>
        <a href="patient_dashboard.php" class="icon-btn" data-text="الرئيسية">
            <i class="fa-solid fa-house"></i>
        </a>
        <a href="appointments.php" class="icon-btn" data-text="مواعيدي">
            <i class="fa-solid fa-calendar-days"></i>
        </a>
        <a href="search_doctor.php" class="icon-btn" data-text="البحث عن طبيب">
            <i class="fa-solid fa-magnifying-glass"></i>
        </a>
        <div class="dropdown">
            <button>
                <i class="fa-solid fa-user-circle"></i>
            </button>
            <div class="dropdown-content">
                <a href="#"><i class="fa-solid fa-user"></i> حسابي</a>
                <a href="settings.php"><i class="fa-solid fa-gear"></i> الإعدادات</a>
                <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> تسجيل الخروج</a>
            </div>
        </div>
    </nav>
</header>

<!-- المنشورات -->
<h2>منشورات الأطباء</h2>
<div class="posts">
    <?php if (count($posts) > 0): ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <p><strong> الطبيب: <?= htmlspecialchars($post['doctor_name']) ?></strong></p>
                <h3><?= htmlspecialchars($post['title']) ?></h3>
                <p><?= htmlspecialchars($post['content']) ?></p>
                <?php if (!empty($post['image'])): ?>
                <img src="<?= htmlspecialchars($post['image']) ?>" alt="Post Image" style="max-width: 100%; height: auto; margin-top: 10px;">
                <?php endif; ?>
                <p><small>تاريخ المنشور: <?= date('d-m-Y H:i', strtotime($post['created_at'])) ?></small></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>لا توجد منشورات لعرضها.</p>
    <?php endif; ?>
</div>

</body>
</html>
