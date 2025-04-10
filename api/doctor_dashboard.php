<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Rezadok | لوحة تحكم الطبيب</title>
    <link rel="stylesheet" href="/Design/index.css">
</head>
<body>
    <header>
        <h2><i class="fa-solid fa-stethoscope"></i> Rezadok</h2>
            <!--الرةابط-->
        <nav>
            <a href="doctor_dashboard.php" class="icon-btn"data-text="الرئيسية">
                <i class="fa-solid fa-house"></i>
            </a>
            <a href="discussions.php" class="icon-btn" data-text="الرسائل">
                <i class="fa-solid fa-comments"></i>
            </a>
            <a href="appointments.php" class="icon-btn" data-text="مواعيدي">
                <i class="fa-solid fa-calendar-days"></i>
            </a>
            
            <a href="#" class="icon-btn" data-text="إضافة منشور">
                <i class="fa-solid fa-plus"></i>
            </a>
            <!-- القائمة -->
            <div class="dropdown">
                <button>
                    <i class="fa-solid fa-user-circle"></i>
                </button>
                <div class="dropdown-content">
                    <a href="#"><i class="fa-solid fa-user"></i> حسابي</a>
                    <a href="#"><i class="fa-solid fa-cog"></i> الإعدادات</a>
                    <a href="#"><i class="fa-solid fa-sign-out-alt"></i> تسجيل الخروج</a>
                </div>
            </div>
        </nav>
    </header>
    <!-- المنشورات -->
    <h2 id="h2-post"> المنشورات <i class="fa-solid fa-newspaper" style="color: #63E6BE;"></i></h2>
</body>
</html>
