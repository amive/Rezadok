<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rezadok هو نظام متكامل لحجز المواعيد الطبية عبر الإنترنت، حيث يمكن للمرضى تسجيل الدخول أو إنشاء حساب للوصول إلى أفضل الأطباء بسهولة.">
    <meta name="keywords" content="حجز مواعيد طبية، أطباء، رعاية صحية، تسجيل الدخول، إنشاء حساب">
    <meta name="author" content="Rezadok Team">
    <title>Rezadok - حجز المواعيد الطبية</title>
    <link rel="stylesheet" href="/Design/index.css">
</head>
<body>

<nav>
    <h1>Rezadok</h1>
    <div>
        <a href="#">اتصل بنا</a>
        <a href="#">من نحن</a>
        <a href="#">الخدمات</a>
        <a href="#">الرئيسية</a>
    </div>
</nav>

<div class="container" id="main-section">
    <h2>مرحبًا بك في Rezadok</h2>
    <p>احجز مواعيدك الطبية بسهولة وسرعة من خلال منصتنا المتكاملة. تواصل مع الأطباء المتخصصين وحدد موعدك في ثوانٍ</p>
    <div class="toggle-buttons">
        <button onclick="showForm('login')">تسجيل الدخول</button>
        <button onclick="showForm('register')">إنشاء حساب</button>
    </div>
</div>

<div class="container hidden" id="login-form">
    <h3>تسجيل الدخول</h3>
    <input type="email" name="email" placeholder="البريد الإلكتروني" required>
    <input type="password" name="password" placeholder="كلمة المرور" required>
    <button type="submit" class="btn">تسجيل الدخول</button>
    <button onclick="showForm('main')">رجوع</button>
</div>

<div class="container hidden" id="register-form">
    <h3>إنشاء حساب</h3>
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
    <button type="submit" class="btn">تسجيل</button>
    <button onclick="showForm('main')">رجوع</button>
</div>
<script src="/script/index.js"></script>
</body>
</html>