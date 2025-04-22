<?php
// تفعيل عرض الأخطاء (لبيئة التطوير فقط)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// تعطيل التخزين المؤقت
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// بدء الجلسة
session_start();
include 'config.php';
// معالجة رفع الصورة
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_picture'])) {
    $image = $_FILES['profile_picture'];

    // اسم الصورة المرفوعة
    $imageName = time() . "_" . basename($image['name']);
    $imagePath = 'uploads/' . $imageName;

    // التأكد من أن الصورة هي صورة فعلية وليست ملفًا ضارًا
    $imageFileType = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($imageFileType, $allowedTypes)) {
       
    } elseif ($image['size'] > 5000000) { // الحد الأقصى لحجم الصورة 5 ميجابايت
        $_SESSION['error_message'] = "⚠️ يجب أن تكون الصورة أقل من 5 ميجابايت!";
    } else {
        // نقل الصورة إلى المجلد
        if (move_uploaded_file($image['tmp_name'], $imagePath)) {
            $_SESSION['image_path'] = $imagePath;
        } else {
            $_SESSION['error_message'] = "⚠️ فشل في رفع الصورة، يرجى المحاولة مرة أخرى!";
        }
    }
} else {
    // إذا لم يتم رفع صورة، استخدم الصورة الافتراضية
    $_SESSION['image_path'] = 'uploads/default_avatar.jpg';
}


// التحقق من إعادة التوجيه
$is_redirected = isset($_SESSION['redirected']);
unset($_SESSION['redirected']);

// معالجة طلبات POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $_SESSION['form_data'] = $_POST;

    if ($action == "login") {
        // معالجة تسجيل الدخول
        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] == 'doctor') {
                    header("Location: doctor_dashboard.php");
                } elseif ($user['role'] == 'patient') {
                    header("Location: patient_dashboard.php");
                }
                exit();
            } else {
                $_SESSION['error_message'] = "البريد الإلكتروني أو كلمة المرور غير صحيحة!";
                $_SESSION['redirected'] = true;
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "حدث خطأ في النظام، يرجى المحاولة لاحقاً";
            error_log("Login error: " . $e->getMessage());
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }

    } elseif ($action == "register") {
        // معالجة التسجيل
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $phone = $_POST['phone'];
        $role = $_POST['role'];
        $specialty = ($role == "doctor") ? $_POST['specialty'] : NULL;
        $bio = ($role == "doctor") ? $_POST['bio'] : NULL;
        $photo =  $_SESSION['image_path'];

        try {
            // التحقق من البريد الإلكتروني
            $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $checkEmail->execute([$email]);

            if ($checkEmail->rowCount() > 0) {
                $_SESSION['error_message'] = "⚠️ البريد الإلكتروني مستخدم من قبل!";
            } else {
                // التحقق من صحة البيانات
                if (strlen($_POST['password']) < 8) {
                    $_SESSION['error_message'] = "⚠️ كلمة المرور يجب أن تكون 8 أحرف على الأقل!";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['error_message'] = "⚠️ البريد الإلكتروني غير صالح!";
                } else {
                    if ($role == 'doctor') {
                        $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role, specialty, bio, profile_picture) 
                                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$name, $email, $password, $phone, $role, $specialty, $bio, $photo]);
                    } else {
                        $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role, profile_picture) 
                                               VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$name, $email, $password, $phone, $role, $photo]);
                    }
                    if ($stmt->rowCount() > 0) {
                        $_SESSION['success_message'] = "✅ تم التسجيل بنجاح! يمكنك تسجيل الدخول الآن.";
                        unset($_SESSION['form_data']);
                    } else {
                        $_SESSION['error_message'] = "⚠️ فشل في إنشاء الحساب، يرجى المحاولة مرة أخرى.";
                    }
                }
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "⚠️ حدث خطأ في النظام، يرجى المحاولة لاحقاً";
            error_log("Registration error: " . $e->getMessage());
        }
        
        $_SESSION['redirected'] = true;
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

// إعداد رسائل النظام
if ($is_redirected) {
    $success_message = $_SESSION['success_message'] ?? "";
    $error_message = $_SESSION['error_message'] ?? "";
    $form_data = $_SESSION['form_data'] ?? [];
    unset($_SESSION['success_message'], $_SESSION['error_message']);
} else {
    $success_message = "";
    $error_message = "";
    $form_data = [];
    unset($_SESSION['form_data']);
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Rezadok | الصفحة الرئيسية</title>
    <link rel="stylesheet" href="css.css">
    <style>
        .success-message, .error-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            font-size: 18px;
            display: none;
            z-index: 1000;
            text-align: center;
        }
        .success-message {
            background-color: #2ecc71;
            color: white;
        }
        .error-message {
            background-color: #e74c3c;
            color: white;
        }
        .hidden {
            display: none;
        }
        #doctorFields {
            display: none;
        }

    input[type="file"] {
        display: block;
        margin-top: 5px;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #f9f9f9;
        cursor: pointer;
    }

    input[type="file"]::-webkit-file-upload-button {
        background: #28a745;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }

    input[type="file"]::-webkit-file-upload-button:hover {
        background: #218838;
    }

</style>

</head>
<body>
    <header>
        <h2><i class="fa-solid fa-stethoscope"></i> Rezadok</h2>
        <nav>
            <a href="index.php" class="icon-btn" data-text="الرئيسية"><i class="fa-solid fa-house"></i></a>
            <a href="#" class="icon-btn" data-text="الخدمات"><i class="fa-solid fa-user-doctor"></i></a>
            <a href="#" class="icon-btn" data-text="من نحن"><i class="fa-solid fa-circle-info"></i></a>
            <a href="#" class="icon-btn" data-text="اتصل بنا"><i class="fa-solid fa-phone"></i></a>
        </nav>
    </header>

    <div class="container-index" id="main-section">
        <h2>مرحبًا بك في <i class="fa-solid fa-stethoscope" style="color:#2c3e50;"></i> <span>Rezadok</span></h2>
        <p id="p_index">احجز مواعيدك الطبية بسهولة وسرعة من خلال منصتنا المتكاملة.</p>
        <div class="toggle-buttons">
            <button onclick="showForm('login')">تسجيل الدخول</button>
            <button onclick="showForm('register')">إنشاء حساب</button>
        </div>
    </div>

    <?php if (!empty($success_message)): ?>
        <div id="success-message" class="success-message" style="display: block;">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
        <div id="error-message" class="error-message" style="display: block;">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <div class="container hidden" id="login-form">
        <h3><i class="fa-solid fa-sign-in-alt"></i> تسجيل الدخول</h3>
        <form action="index.php" method="POST">
            <input type="hidden" name="action" value="login">
            <input type="email" name="email" placeholder="البريد الإلكتروني" 
                   value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>" required>
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <button type="submit" class="btn-login-register">تسجيل الدخول</button>
        </form>
    </div>

    <div class="container hidden" id="register-form">
        <h3><i class="fa-solid fa-user-plus"></i> إنشاء حساب</h3>
        <form action="index.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="register">
            <input type="text" name="name" placeholder="الاسم الكامل" 
                   value="<?php echo htmlspecialchars($form_data['name'] ?? ''); ?>" required>
            <input type="email" name="email" placeholder="البريد الإلكتروني" 
                   value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>" required>
            <input type="password" name="password" placeholder="كلمة المرور" minlength="8" required>
            <input type="tel" name="phone" placeholder="رقم الهاتف" 
                   value="<?php echo htmlspecialchars($form_data['phone'] ?? ''); ?>" required>
            <p>rf3</p>
            <input type="file" name="profile_picture" id="profile_picture" accept="image/*" >
            <select name="role" id="role" required onchange="toggleDoctorFields()">
                <option value="">اختر دورك</option>
                <option value="doctor" <?php echo ($form_data['role'] ?? '') == 'doctor' ? 'selected' : ''; ?>>طبيب(ة)</option>
                <option value="patient" <?php echo ($form_data['role'] ?? '') == 'patient' ? 'selected' : ''; ?>>مريض(ة)</option>
            </select>

            <div id="doctorFields">
                <select name="specialty" >
                    <option value="">اختر التخصص</option>
                    <?php
                    $specialties = [
                        "طب عام", "طب القلب", "طب العيون", "طب الأطفال",
                        "طب النساء والتوليد", "طب الأعصاب", "طب الجلدية"
                    ];
                    foreach ($specialties as $spec) {
                        $selected = ($form_data['specialty'] ?? '') == $spec ? 'selected' : '';
                        echo "<option value=\"$spec\" $selected>$spec</option>";
                    }
                    ?>
                </select>
                <textarea name="bio" placeholder="نبذة عنك"><?php echo htmlspecialchars($form_data['bio'] ?? ''); ?></textarea>
                 <!-- حقل رفع الصورة -->

            </div>

            <button type="submit" class="btn-login-register">تسجيل</button>
        </form>
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
            const role = document.getElementById("role").value;
            const doctorFields = document.getElementById("doctorFields");
            
            if (role === "doctor") {
                doctorFields.style.display = "block";
                doctorFields.querySelectorAll("select, textarea").forEach(el => el.required = true);
            } else {
                doctorFields.style.display = "none";
                doctorFields.querySelectorAll("select, textarea").forEach(el => el.required = false);
            }
        }

        window.onload = function() {
            // إظهار حقول الطبيب إذا كان الدور المختار هو طبيب
            if (document.getElementById("role").value === "doctor") {
                document.getElementById("doctorFields").style.display = "block";
            }

            // إخفاء رسائل التنبيه بعد 5 ثواني
            setTimeout(() => {
                const successMsg = document.getElementById("success-message");
                const errorMsg = document.getElementById("error-message");
                if (successMsg) successMsg.style.display = "none";
                if (errorMsg) errorMsg.style.display = "none";
            }, 5000);

            // منع إعادة إرسال النموذج عند تحديث الصفحة
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        };
    </script>-->
    <script>
    function toggleDoctorFields() {
        var role = document.getElementById("role").value;
        var doctorFields = document.getElementById("doctorFields");
        doctorFields.style.display = (role === "doctor") ? "block" : "none";
    }

    // لإعادة إظهار الحقول بعد إعادة تحميل الصفحة إذا تم اختيار "doctor"
    window.onload = function() {
        toggleDoctorFields();
    };
</script>

</body>
</html>