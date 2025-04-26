<?php
include 'config.php';

// التحقق من أن المستخدم مريض باستخدام الكوكيز
if (!isset($_COOKIE['user_id']) || $_COOKIE['role'] != 'patient') {
    header("Location: login.php");
    exit();
}

// التأكد من تحديد الطبيب في الرابط
if (!isset($_GET['doctor_id'])) {
    setcookie('error', "<i class='fa-solid fa-triangle-exclamation'></i> لم يتم تحديد الطبيب.", time() + 3600, "/");
    header("Location: patient_dashboard.php");
    exit();
}

$patient_id = $_COOKIE['user_id'];
$doctor_id = $_GET['doctor_id'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_date = $_POST['appointment_date'];

    if (strtotime($appointment_date) < time()) {
        setcookie('error', "<i class='fa-solid fa-calendar-xmark'></i> لا يمكنك حجز موعد في تاريخ ووقت قديمين!", time() + 3600, "/");
        header("Location: book_appointments.php?doctor_id=$doctor_id");
        exit();
    } else {
        // التحقق من عدم وجود حجز سابق لنفس الطبيب والمريض في نفس التاريخ
        $stmt = $conn->prepare("SELECT * FROM appointments WHERE doctor_id = ? AND patient_id = ? AND appointment_date = ?");
        $stmt->execute([$doctor_id, $patient_id, $appointment_date]);

        if ($stmt->rowCount() > 0) {
            setcookie('error', "<i class='fa-solid fa-clock-rotate-left'></i> لديك بالفعل موعد محجوز مع هذا الطبيب في هذا الوقت!", time() + 3600, "/");
        } else {
            // إدخال الموعد
            $stmt = $conn->prepare("INSERT INTO appointments (doctor_id, patient_id, appointment_date, status) VALUES (?, ?, ?, 'pending')");
            if ($stmt->execute([$doctor_id, $patient_id, $appointment_date])) {
                setcookie('success', "<i class='fa-solid fa-circle-check'></i> تم حجز موعدك بنجاح! في انتظار تأكيد الطبيب.", time() + 3600, "/");
            } else {
                setcookie('error', "<i class='fa-solid fa-circle-xmark'></i> حدث خطأ أثناء الحجز!", time() + 3600, "/");
            }
        }
        header("Location: book_appointment.php?doctor_id=$doctor_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>حجز موعد - Rezadok</title>
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f6f6f6;
            padding: 20px;
            direction: rtl;
            text-align: right;
        }

        .appointment-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-top: 10px;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #198754;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #157347;
        }

        a {
            color: #0d6efd;
            text-decoration: none;
        }

        .success {
            color: green;
            background-color: #d4edda;
            padding: 10px;
            margin-top: 10px;
            border-radius: 6px;
        }

        .error {
            color: red;
            background-color: #f8d7da;
            padding: 10px;
            margin-top: 10px;
            border-radius: 6px;
        }

        header {
            margin-bottom: 30px;
        }

        header h2 {
            display: inline-block;
            margin: 0 10px;
        }

        nav a {
            margin: 0 10px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <header>
        <h2><a href="index.php"><i class="fa-solid fa-stethoscope"></i></a>Rezadok</h2>
        <nav>
            <a href="patient_dashboard.php" class="icon-btn" data-text="الرئيسية">
                <i class="fa-solid fa-house"></i>
            </a>
            <a href="appointments.php" class="icon-btn" data-text="مواعيدي">
                <i class="fa-solid fa-calendar-days"></i>
            </a>
        </nav>
    </header>

    <div class="appointment-container">
        <h2>حجز موعد</h2>

        <?php if (isset($_COOKIE['error'])): ?>
            <div class="error"><?= $_COOKIE['error']; ?></div>
            <?php setcookie('error', '', time() - 3600, '/'); ?>
        <?php endif; ?>

        <?php if (isset($_COOKIE['success'])): ?>
            <div class="success"><?= $_COOKIE['success']; ?></div>
            <?php setcookie('success', '', time() - 3600, '/'); ?>
        <?php endif; ?>

        <form method="POST">
            <label for="appointment_date">تاريخ ووقت الموعد:</label>
            <input type="date" name="appointment_date" id="appointment_date" required>

            <button type="submit"><i class="fa-solid fa-check"></i> تأكيد الحجز</button>
        </form>

        <br>
        <a href="patient_dashboard.php"><i class="fa-solid fa-arrow-left"></i> العودة إلى لوحة التحكم</a>
    </div>

    <script>
        // تحديد أقل تاريخ ووقت مسموح به (الآن)
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset()); // لتفادي فرق التوقيت
        document.getElementById("appointment_date").min = now.toISOString().slice(0,16);
    </script>
</body>
</html>
