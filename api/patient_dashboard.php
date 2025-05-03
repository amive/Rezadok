<?php
// استخدام الكوكيز بدلًا من session
$user_id = $_COOKIE['user_id'] ?? null;
$role = strtolower(trim($_COOKIE['role'] ?? ''));

// التحقق من تسجيل الدخول وصلاحية المريض
if (!$user_id || $role !== 'patient') {
    error_log("PATIENT user_id/Role check false from cookies.");
    header("Location: /");
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
        <title>Rezadok | لوحة تحكم المريض</title>
        <link rel="stylesheet" href="Design/patient_dashboard.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    </head>
    <body>
        <header>
            <h2><a href=""><i class="fa-solid fa-stethoscope"></i></a>Rezadok</h2>
            <button class="menu-toggle">&#9776;</button>
            <nav class="nav-main">
                <a href="patient_dashboard" class="icon-btn" data-text="الرئيسية">
                    <i class="fa-solid fa-house"></i>
                </a>
                <a href="appointments" class="icon-btn" data-text="مواعيدي">
                    <i class="fa-solid fa-calendar-days"></i>
                </a>
                <a href="search_doctor" class="icon-btn" data-text="البحث عن طبيب">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>
                <div class="dropdown">
                    <button>
                        <i class="fa-solid fa-user-circle"></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="#"><i class="fa-solid fa-user"></i> حسابي</a>
                        <a href="settings"><i class="fa-solid fa-gear"></i> الإعدادات</a>
                        <a href="logout"><i class="fa-solid fa-right-from-bracket"></i> تسجيل الخروج</a>
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
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const menuToggle = document.querySelector('.menu-toggle');
                const nav = document.querySelector('nav');
                menuToggle.addEventListener('click', () => {
                    nav.classList.toggle('active');
                });
            });
        </script>

    </body>
</html>
