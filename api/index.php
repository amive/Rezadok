<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <h2>مرحبًا بك في <i class="fa-solid fa-stethoscope"></i> Rezadok</h2>
        <p id="p_index">.احجز مواعيدك الطبية بسهولة وسرعة من خلال منصتنا المتكاملة، تواصل مع الأطباء المتخصصين وحدد موعدك في ثوانٍ</p>
        <div class="toggle-buttons">
            <button onclick="showForm('login')">تسجيل الدخول</button>
            <button onclick="showForm('register')">إنشاء حساب</button>
        </div>
    </div>
<!-- تسجيل الدخول -->
    <div class="container hidden" id="login-form">
        <h3><i class="fa-solid fa-sign-in-alt"></i> تسجيل الدخول</h3>
        <input type="email" name="email" placeholder="البريد الإلكتروني" required>
        <input type="password" name="password" placeholder="كلمة المرور" required>
        <button type="submit" class="btn-login-register">تسجيل الدخول</button>
    </div>
<!-- إنشاء حساب -->
    <div class="container hidden" id="register-form">
        <h3><i class="fa-solid fa-user-plus"></i> إنشاء حساب</h3>
        <input type="text" name="name" placeholder="الاسم الكامل" required>
        <input type="email" name="email" placeholder="البريد الإلكتروني" required>
        <input type="password" name="password" placeholder="كلمة المرور" required>
        <input type="text" name="phone" placeholder="رقم الهاتف" required>
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
    </div>
    <script src="/script/index.js"></script>
</body>
</html>
