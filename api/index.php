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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        nav {
            background-color: #007BFF;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        nav h1 {
            margin: 0;
            font-size: 24px;
        }
        nav div {
            display: flex;
            gap: 15px;
        }
        nav a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            padding: 5px 10px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 400px;
            margin: 100px auto 20px;
            display: none;
        }
        h2 {
            color: #007BFF;
        }
        input, select, textarea, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .toggle-buttons {
            margin-top: 20px;
        }
        #main-section {
            display: block;
        }
        .hidden {
            display: none;
        }
    </style>
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

<script>
    function showForm(formType) {
        document.getElementById("main-section").style.display = "none";
        document.getElementById("login-form").style.display = "none";
        document.getElementById("register-form").style.display = "none";

        if (formType === "login") {
            document.getElementById("login-form").style.display = "block";
        } else if (formType === "register") {
            document.getElementById("register-form").style.display = "block";
        } else {
            document.getElementById("main-section").style.display = "block";
        }
    }

    function toggleDoctorFields() {
        document.getElementById("doctorFields").style.display = 
            (document.getElementById("role").value == "doctor") ? "block" : "none";
    }
</script>

</body>
</html>