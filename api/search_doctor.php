<?php
session_start();
include 'config.php';

// التحقق من تسجيل الدخول كمريض
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient') {
    header("Location: index.php");
    exit();
}

$patient_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>البحث عن طبيب</title>
    <link rel="stylesheet" href="css.css">
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
        <form id="searchForm">
            <h2>البحث عن طريق : </h2>
            <div id="method_search">
                <label>
                    <input type="radio" name="searchOption" value="name" id="searchByName"> بالاسم
                </label>
                <label>
                    <input type="radio" name="searchOption" value="specialty" id="searchBySpecialty"> بالتخصص
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

            <button type="button" onclick="searchDoctors()">بحث</button>
        </form>

        <ul id="doctor-list">
        <?php
        $stmt = $conn->prepare("SELECT id, name, specialty FROM users WHERE role = 'doctor'");
        $stmt->execute();
        $doctors = $stmt->fetchAll();

        if (count($doctors) > 0):
            foreach ($doctors as $doctor): ?>
                <li class="doctor-item">
                    <a href="doctor_profile.php?id=<?php echo $doctor['id']; ?>">
                        👨‍⚕️ <?php echo htmlspecialchars($doctor['name']); ?> - <?php echo htmlspecialchars($doctor['specialty']); ?>
                    </a>
                </li>
            <?php endforeach;
        else: ?>
            <p>❌ لا يوجد أطباء متاحون.</p>
        <?php endif; ?>
        </ul>
    </div>

<script>
// تفعيل خانات البحث حسب الاختيار
document.querySelectorAll('input[name="searchOption"]').forEach(option => {
    option.addEventListener('change', function () {
        document.getElementById("searchName").disabled = this.value !== "name";
        document.getElementById("searchSpecialty").disabled = this.value !== "specialty";
    });
});

// فلترة الأطباء حسب الاسم أو التخصص
function searchDoctors() {
    let searchOption = document.querySelector('input[name="searchOption"]:checked');
    if (!searchOption) return;

    let doctors = document.querySelectorAll(".doctor-item");
    let keyword = "";

    if (searchOption.value === "name") {
        keyword = document.getElementById("searchName").value.toLowerCase();
    } else if (searchOption.value === "specialty") {
        keyword = document.getElementById("searchSpecialty").value.toLowerCase();
    }

    doctors.forEach(doctor => {
        let text = doctor.textContent.toLowerCase();
        doctor.style.display = text.includes(keyword) ? "block" : "none";
    });
}

// منع الإرسال الافتراضي للنموذج
document.getElementById("searchForm").addEventListener("submit", function(e){
    e.preventDefault();
    searchDoctors();
});
</script>

</body>
</html>
