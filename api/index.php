<?php
require_once("db.php");

$registerError = "";
$registerSuccess = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name       = $_POST['name'];
    $email      = $_POST['email'];
    $password   = $_POST['password'];
    $phone      = $_POST['phone'];
    $role       = $_POST['role'];
    $specialty  = $_POST['specialty'] ?? '';
    $bio        = $_POST['bio'] ?? '';

    if (!preg_match('/^0(5|6|7)\d{8}$/', $phone)) {
        $registerError = "رقم الهاتف غير صالح.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $registerError = "البريد الإلكتروني مستخدم من قبل.";
        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role, specialty, bio) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $name, $email, $hashedPassword, $phone, $role, $specialty, $bio);

            if ($stmt->execute()) {
                $registerSuccess = "تم إنشاء الحساب بنجاح!";
            } else {
                $registerError = "حدث خطأ أثناء إنشاء الحساب.";
            }
        }
        $stmt->close();
    }
}

$loginError = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {

        $stmt->bind_result($id, $hashedPassword);
        $stmt->fetch();
        
        if (password_verify($password, $hashedPassword)) {
            session_start();
            $_SESSION['user_id'] = $id;
            header("Location: dashboard.php"); // Redirect to dashboard or home page
            exit();
        } else {
            $loginError = "كلمة المرور غير صحيحة.";
        }
    } else {
        $loginError = "البريد الإلكتروني غير موجود.";
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="jejwDxsSve77G4TYtNZPqrWhyVA6GLSG9lauaa9Y064" />
    <meta name="description" content="Rezadok هو نظام متكامل لحجز المواعيد الطبية عبر الإنترنت، حيث يمكن للمرضى تسجيل الدخول أو إنشاء حساب للوصول إلى أفضل الأطباء بسهولة.">
    <meta name="keywords" content="حجز مواعيد طبية، أطباء، رعاية صحية، تسجيل الدخول، إنشاء حساب">
    <meta name="author" content="Rezadok Team">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Rezadok | الصفحة الرئيسية</title>
    <link rel="stylesheet" href="/Design/index.css">
</head>
<body>
    <header>
        <h2><i class="fa-solid fa-stethoscope"></i> Rezadok</h2>
        <nav>
            <a href="index.php" class="icon-btn"data-text="الرئيسية"><i class="fa-solid fa-house"></i></a>
            <a href="#" class="icon-btn"data-text="الخدمات"><i class="fa-solid fa-user-doctor"></i></a>
            <a href="#" class="icon-btn"data-text="من نحن"><i class="fa-solid fa-circle-info"></i></a>
            <a href="#" class="icon-btn"data-text="اتصل بنا"><i class="fa-solid fa-phone"></i> </a>
        </nav>
    </header>
    <div class="container-index" id="main-section">
        <h2>مرحبًا بك في<i class="fa-solid fa-stethoscope" style="color:#2c3e50;"></i> <span>Rezadok</span></h2>
        <p id="p_index">احجز مواعيدك الطبية بسهولة وسرعة من خلال منصتنا المتكاملة. تواصل مع الأطباء المتخصصين وحدد موعدك في ثوانٍ</p>
        <div class="toggle-buttons">
            <button onclick="showForm('login')">تسجيل الدخول</button>
            <button onclick="showForm('register')">إنشاء حساب</button>
        </div>
    </div>
<!-- تسجيل الدخول -->
 <div class="container hidden" id="login-form">
<form method="POST" action="">
        <h3><i class="fa-solid fa-sign-in-alt"></i> تسجيل الدخول</h3>
        <input type="email" name="email" placeholder="البريد الإلكتروني" required>
        <input type="password" name="password" placeholder="كلمة المرور" required>
        <button type="submit" class="btn-login-register">تسجيل الدخول</button>
    </form>
    <?php
if (!empty($loginError)) {
    echo "<p style='color:red;'>$loginError</p>";
}
?>

 </div>
<!-- إنشاء حساب -->
    <div class="container hidden" id="register-form"></div>
    <form method="POST" action="">
        <h3><i class="fa-solid fa-user-plus"></i> إنشاء حساب</h3>
        <input type="text" name="name" placeholder="الاسم الكامل" required>
        <input type="email" name="email" placeholder="البريد الإلكتروني" required>
        <input type="password" name="password" placeholder="كلمة المرور" required>
        <input type="text" name="phone" pattern="^\0(5|6|7)\d{8}$" required placeholder="رقم الهاتف" required>
        <select name="role" id="role" required onchange="toggleDoctorFields()">
            <option value="">اختر دورك</option>
            <option value="doctor">طبيب(ة)</option>
            <option value="patient">مريض(ة)</option>
        </select>
        <div id="doctorFields" class="hidden">
            <input type="text" name="specialty" placeholder="التخصص">
            <textarea name="bio" placeholder="نبذة عنك"></textarea>
        </div>
        <button type="submit" id="btn-login-register">تسجيل</button>
   </form>
       <?php
if (!empty($registerError)) {
    echo "<p style='color:red;'>$registerError</p>";
}
if (!empty($registerSuccess)) {
    echo "<p style='color:green;'>$registerSuccess</p>";
}
?>
    </div>


 
    <script src="/script/index.js"></script>
</body>
</html>
