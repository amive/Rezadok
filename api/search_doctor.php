<?php
// Include the database configuration file
include('config.php');
// التحقق من تسجيل الدخول باستخدام الكوكيز
if (!isset($_COOKIE['user_id']) || $_COOKIE['role'] != 'patient') {
    header("Location: /");
    exit();
}

$patient_id = $_COOKIE['user_id']; // استخدام الـ cookie بدلاً من الـ session
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>البحث عن طبيب</title>
    <link rel="stylesheet" href="Design/search_doctor.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">

</head>
<body>
    <header>
        <h2><a href=""><i class="fa-solid fa-stethoscope"></i></a>Rezadok</h2>
        <nav> 
            <a href="patient_dashboard" class="icon-btn" data-text="الرئيسية">
                <i class="fa-solid fa-house"></i>
            </a>
            <a href="appointments" class="icon-btn" data-text="مواعيدي">
                <i class="fa-solid fa-calendar-days"></i>
            </a>
            <div class="dropdown">
                <button>
                    <i id="usercircle" class="fa-solid fa-user-circle"></i>
                </button>
                <div class="dropdown-content">
                    <a href="#"><i class="fa-solid fa-user"></i> حسابي</a>
                    <a href="settings"><i class="fa-solid fa-cog"></i> الإعدادات</a>
                    <a href="logout"><i class="fa-solid fa-sign-out-alt"></i> تسجيل الخروج</a>
                </div>
            </div>
        </nav>
    </header>

    <div class="container">
        <form id="searchForm">
            <h2>البحث عن طريق : </h2>
            <div id="method_search">
                <label>
                    <input type="radio" name="searchOption" value="name" id="searchByName"> الاسم
                </label>
                <label>
                    <input type="radio" name="searchOption" value="specialty" id="searchBySpecialty"> التخصص
                </label>
            </div>
            <input type="text" id="searchName" placeholder="اسم الطبيب..." disabled onkeyup="searchDoctors()">

            <?php
            // جلب التخصصات من قاعدة البيانات
            $stmt = $conn->prepare("SELECT DISTINCT specialty FROM users WHERE role = 'doctor'");
            $stmt->execute();
            $specialties = $stmt->fetchAll();
            ?>
            <select id="searchSpecialty" disabled onchange="searchDoctors()">
                <option value="">اختر التخصص</option>
                <?php foreach ($specialties as $spec): ?>
                    <option value="<?= $spec['specialty'] ?>"><?= $spec['specialty'] ?></option>
                <?php endforeach; ?>
            </select>

            <button class="searchbtn" type="button" onclick="searchDoctors()">بحث</button>
        </form>

        <ul id="doctor-list">
        <?php
        $stmt = $conn->prepare("SELECT id, name, specialty FROM users WHERE role = 'doctor'");
        $stmt->execute();
        $doctors = $stmt->fetchAll();

        if (count($doctors) > 0):
            foreach ($doctors as $doctor): ?>
                <li class="doctor-item">
                    <a href="doctor_profile?id=<?php echo $doctor['id']; ?>">
                        👨‍⚕️ <?php echo htmlspecialchars($doctor['name']); ?> - <?php echo htmlspecialchars($doctor['specialty']); ?>
                    </a>
                </li>
            <?php endforeach;
        else: ?>
            <p>❌ لا يوجد أطباء متاحون.</p>
        <?php endif; ?>
        </ul>
    </div>
     <footer>
        <div class="reza"> © 2025 جميع الحقوق محفوظة لـ<strong>Rezadok</strong></div>  
        <a href="https://www.github.com/amive" target="_blank" style="color: #390071;"><i class="fab fa-github" style="color: #390071;"></i>Amine</a> |
        <a href="https://www.github.com/bilalgarah" target="_blank" style="color: #a52a2a;"><i class="fab fa-github"style="color: #a52a2a;"></i>Bilal</a>
    </footer>
<script src="script/search_doctor.js"></script>

</body>
</html>
