<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول / التسجيل - Rezadok</title>
    <link rel="stylesheet" href="style.css">
    <script defer>
        function toggleForms() {
            document.getElementById("login-form").classList.toggle("hidden");
            document.getElementById("register-form").classList.toggle("hidden");
        }

        function toggleDoctorFields() {
            document.getElementById("doctorFields").style.display = (document.getElementById("role").value == "doctor") ? "block" : "none";
        }
    </script>
    <style>
        .hidden { display: none; }
    </style>
</head>
<body>

<div class="container">
    <h2>🩺 Rezadok - تسجيل الدخول / التسجيل</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error) echo "<p>$error</p>"; ?>
        </div>
    <?php endif; ?>

    <!-- ✅ نموذج تسجيل الدخول -->
    <form id="login-form" method="POST">
        <h3>🔑 تسجيل الدخول</h3>
        <input type="email" name="email" placeholder="📧 البريد الإلكتروني" required>
        <input type="password" name="password" placeholder="🔑 كلمة المرور" required>
        <button type="submit" name="login" class="btn">➡️ تسجيل الدخول</button>
        <button type="button" class="btn switch" onclick="toggleForms()">🔄 إنشاء حساب جديد</button>
    </form>

    <!-- ✅ نموذج إنشاء حساب -->
    <form id="register-form" method="POST" class="hidden">
        <h3>🆕 إنشاء حساب</h3>
        <input type="text" name="name" placeholder="👤 الاسم الكامل" required>
        <input type="email" name="email" placeholder="📧 البريد الإلكتروني" required>
        <input type="password" name="password" placeholder="🔑 كلمة المرور" required>
        <input type="text" name="phone" placeholder="📞 رقم الهاتف" required>
        <select name="role" id="role" required onchange="toggleDoctorFields()">
            <option value="">🩺 اختر دورك</option>
            <option value="doctor">👨‍⚕️ طبيب</option>
            <option value="patient">👨‍⚕️ مريض</option>
        </select>
        <div id="doctorFields" style="display: none;">
            <input type="text" name="specialty" placeholder="⚕️ التخصص">
            <textarea name="bio" placeholder="📝 نبذة عنك"></textarea>
        </div>

        <button type="submit" name="register" class="btn">✅ تسجيل</button>
        <button type="button" class="btn switch" onclick="toggleForms()">🔄 لدي حساب بالفعل</button>
    </form>
</div>

</body>
</html>
