<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient') {
    header("Location: /index.php");
    exit();
}

include('config.php');

// استرجاع بيانات المستخدم
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// استرجاع المنشورات الخاصة بجميع الأطباء، وجلب الاسم من جدول users
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
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* تنسيق الصفحة */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f7fc;
    margin: 0;
    padding: 0;
}

/* تنسيق المنشورات */
.container {
    width: 80%;
    margin: 40px auto;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* تنسيق كل منشور */
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

/* صورة المنشور */
.post img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    margin-bottom: 15px;
}

/* عنوان المنشور */
.post h3 {
    color: #333;
    font-size: 24px;
    margin-bottom: 10px;
}

/* محتوى المنشور */
.post p {
    color: #555;
    font-size: 16px;
    line-height: 1.6;
    margin-bottom: 10px;
}

/* تاريخ المنشور */
.post small {
    font-size: 12px;
    color: #777;
    display: block;
    margin-top: 15px;
}

/* في حالة عدم وجود منشورات */
.container p {
    text-align: center;
    font-size: 18px;
    color: #888;
}

    </style>
</head>
<body>

<header>
    <h2><i class="fa-solid fa-stethoscope"></i> Rezadok</h2>
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
                <a href="setting.php"><i class="fa-solid fa-gear"></i> الإعدادات</a>
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
               <p><strong> الطبيب: <?php echo htmlspecialchars($post['doctor_name']); ?></strong></p>
                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                <p><?php echo htmlspecialchars($post['content']); ?></p>
                <?php if ($post['image']): ?>
                    <img src="uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image">
                <?php endif; ?>                
                <p><small>تاريخ المنشور: <?php echo date('d-m-Y H:i', strtotime($post['created_at'])); ?></small></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>لا توجد منشورات لعرضها.</p>
    <?php endif; ?>
</div>

</body>
</html>
