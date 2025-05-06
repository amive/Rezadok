<?php
ob_start();
include 'config.php';

// التحقق من أن المستخدم مريض باستخدام الكوكيز
if (!isset($_COOKIE['user_id']) || $_COOKIE['role'] != 'patient') {
    header("Location: /");
    exit();
}

// التأكد من تحديد الطبيب في الرابط
if (!isset($_GET['doctor_id'])) {
    setcookie('error', "<i class='fa-solid fa-triangle-exclamation'></i> لم يتم تحديد الطبيب.", time() + 3600, "/");
    header("Location: patient_dashboard");
    exit();
}

$patient_id = $_COOKIE['user_id'];
$doctor_id = $_GET['doctor_id'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_date = $_POST['appointment_date'];

    if (strtotime($appointment_date) < time()) {
        setcookie('error', "<i class='fa-solid fa-calendar-xmark'></i> لا يمكنك حجز موعد في تاريخ ووقت قديمين!", time() + 3600, "/");
        header("Location: book_appointments?doctor_id=$doctor_id");
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
        header("Location: book_appointments?doctor_id=$doctor_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
    <head>
        <meta charset="UTF-8">
        <title>حجز موعد - Rezadok</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="Design/book_appointments.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="https://www.fontstatic.com/f=geeza-pro" />
    </head>
    <body>
        <header>
            <h2><i class="fa-solid fa-stethoscope"></i> Rezadok</h2>
            <nav>
                <a href="patient_dashboard" class="icon-btn" data-text="الرئيسية">
                    <i class="fa-solid fa-house"></i>
                </a>
            </nav>
        </header>

        <div class="appointment-container">
            <h2>حجز موعد</h2>

            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST">
                <label for="appointment_date">تاريخ ووقت الموعد:</label>
                <input type="datetime-local" name="appointment_date" id="appointment_date" required>

                <button type="submit"><i class="fa-solid fa-check"></i> تأكيد الحجز</button>
            </form>
        </div>

        <script src="script/book_appointments.js"></script>
    </body>
</html>
<?php ob_end_flush(); ?>
