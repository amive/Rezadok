<?php
// Start your PHP logic (no session_start(), using cookies instead)
include 'config.php';

// التحقق من تسجيل الدخول عبر الكوكيز
if (!isset($_COOKIE['role'])) {
    header("Location: index.php");
    exit();
}

$role = $_COOKIE['role'];
$user_id = $_COOKIE['user_id'];

// معالجة تأكيد أو إلغاء الموعد
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'], $_POST['appointment_id'])) {
    $appointment_id = filter_var($_POST['appointment_id'], FILTER_SANITIZE_NUMBER_INT);
    $action = filter_var($_POST['action'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($role === 'doctor') {
        try {
            $check_stmt = $conn->prepare("SELECT status, patient_id FROM appointments WHERE id = ? AND doctor_id = ?");
            $check_stmt->execute([$appointment_id, $user_id]);

            if ($check_stmt->rowCount() > 0) {
                $appointment = $check_stmt->fetch();
                
                if ($action === 'confirm') {
                    if ($appointment['status'] === 'confirmed') {
                        setcookie('error', "❌ لا يمكن تأكيد الموعد لأنه مؤكد مسبقاً", time() + 3600, "/");
                    } else {
                        $stmt = $conn->prepare("UPDATE appointments SET status = 'confirmed' WHERE id = ?");
                        $stmt->execute([$appointment_id]);
                        setcookie('success', "✅ تم تأكيد الموعد بنجاح", time() + 3600, "/");
                    }
                } elseif ($action === 'cancel') {
                    $stmt = $conn->prepare("UPDATE appointments SET status = 'canceled' WHERE id = ?");
                    $stmt->execute([$appointment_id]);
                    setcookie('success', "🚫 تم إلغاء الموعد بنجاح", time() + 3600, "/");
                }
            } else {
                setcookie('error', "⚠️ لا تملك صلاحية تعديل هذا الموعد", time() + 3600, "/");
            }
        } catch (PDOException $e) {
            setcookie('error', "حدث خطأ: " . $e->getMessage(), time() + 3600, "/");
        }
    } else {
        setcookie('error', "صلاحيات غير كافية", time() + 3600, "/");
    }

    header("Location: appointments.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezadok | إدارة المواعيد</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link rel="stylesheet" href="Design/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* أنماط النافذة المنبثقة */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            backdrop-filter: blur(3px);
        }
        
        .modal-content {
            background-color: #f8f9fa;
            margin: 10% auto;
            padding: 25px;
            border-radius: 10px;
            width: 400px;
            max-width: 90%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            animation: modalFadeIn 0.4s ease-out;
            border: 1px solid #ddd;
        }
        
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .modal-header h3 {
            margin: 0;
            color: #2c3e50;
            font-size: 1.3rem;
        }
        
        .close-btn {
            font-size: 24px;
            cursor: pointer;
            color: #95a5a6;
            transition: color 0.3s;
        }
        
        .close-btn:hover {
            color: #e74c3c;
        }
        
        .modal-body {
            margin-bottom: 25px;
            font-size: 1.1rem;
            color: #34495e;
            line-height: 1.6;
        }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }
        
        .modal-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }
        
        .modal-btn:active {
            transform: translateY(0);
        }
        
        .confirm-btn {
            background-color: #27ae60;
            color: white;
        }
        
        .confirm-btn:hover {
            background-color: #2ecc71;
        }
        
        .cancel-btn {
            background-color: #e74c3c;
            color: white;
        }
        
        .cancel-btn:hover {
            background-color: #c0392b;
        }
        
        /* تحسينات للجدول */
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: right;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #2c3e50;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        /* أزرار الإجراءات */
        .action-btn {
            padding: 6px 12px;
            margin: 0 3px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        
        .action-btn i {
            margin-left: 5px;
        }
        
        .confirm-btn-table {
            background-color: #27ae60;
            color: white;
        }
        
        .cancel-btn-table {
            background-color: #e74c3c;
            color: white;
        }
        
        .action-btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <header>
        <h2><a href="index.php"><i class="fa-solid fa-stethoscope"></i></a>Rezadok</h2>
        <nav>
            <a href="<?= ($role == 'doctor') ? 'doctor_dashboard.php' : 'patient_dashboard.php' ?>" class="icon-btn" data-text="الرئيسية">
                <i class="fa-solid fa-house"></i>
            </a>
            <a href="chat.php" class="icon-btn" data-text="الرسائل">
                <i class="fa-solid fa-comments"></i>
            </a>
        </nav>
    </header>

    <div class="container-appointments">
        <h2><i class="fa-solid fa-calendar-check"></i> قائمة المواعيد</h2>

        <!-- Success/Error Messages -->
        <?php if (isset($_COOKIE['success'])): ?>
            <div class="alert success"><?= $_COOKIE['success'] ?></div>
            <?php setcookie('success', '', time() - 3600, '/'); ?>
        <?php endif; ?>

        <?php if (isset($_COOKIE['error'])): ?>
            <div class="alert error"><?= $_COOKIE['error'] ?></div>
            <?php setcookie('error', '', time() - 3600, '/'); ?>
        <?php endif; ?>

        <?php
        try {
            if ($role === 'doctor') {
                $stmt = $conn->prepare("
                    SELECT a.*, u.name AS patient_name, u.phone AS patient_phone 
                    FROM appointments a
                    JOIN users u ON a.patient_id = u.id
                    WHERE a.doctor_id = ?
                    ORDER BY a.appointment_date DESC
                ");
                $stmt->execute([$user_id]);
                $appointments = $stmt->fetchAll();

                if (count($appointments) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>المريض</th>
                                <th>الهاتف</th>
                                <th>التاريخ</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach ($appointments as $appointment): ?>
                                <tr>
                                    <td><?= htmlspecialchars($appointment['patient_name']) ?></td>
                                    <td><?= htmlspecialchars($appointment['patient_phone']) ?></td>
                                    <td><?= date('Y-m-d H:i', strtotime($appointment['appointment_date'])) ?></td>
                                    <td>
                                        <span class="status-badge <?= $appointment['status'] ?>">
                                            <?= htmlspecialchars($appointment['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($appointment['status'] !== 'confirmed'): ?>
                                            <button onclick="confirmAction('confirm', <?= $appointment['id'] ?>)" 
                                                    class="action-btn confirm-btn-table">
                                                <i class="fa-solid fa-check"></i> تأكيد
                                            </button>
                                        <?php endif; ?>
                                        <button onclick="confirmAction('cancel', <?= $appointment['id'] ?>)" 
                                                class="action-btn cancel-btn-table">
                                            <i class="fa-solid fa-times"></i> إلغاء
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="no-data">لا توجد مواعيد مسجلة</p>
                <?php endif;
            }
        } catch (PDOException $e) {
            // Handle the exception
            echo "<p class='error'>حدث خطأ أثناء استرجاع البيانات: " . htmlspecialchars($e->getMessage()) . "</p>";
        } 
        if ($role === 'patient') {
                $stmt = $conn->prepare("
                    SELECT a.*, u.name AS doctor_name, u.specialty 
                    FROM appointments a
                    JOIN users u ON a.doctor_id = u.id
                    WHERE a.patient_id = ?
                    ORDER BY a.appointment_date DESC
                ");
                $stmt->execute([$user_id]);
                $appointments = $stmt->fetchAll();
        
                if (count($appointments) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>رقم الموعد</th>
                                <th>الطبيب</th>
                                <th>التخصص</th>
                                <th>التاريخ</th>
                                <th>الحالة</th>
                                <th>الوقت المتبقي</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $counter = 1; // يبدأ من 1
                        foreach ($appointments as $appointment): 
                            $appointmentDate = $appointment['appointment_date'];
                        ?>
                            <tr>
                                <td><?= $counter ?></td>
                                <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
                                <td><?= htmlspecialchars($appointment['specialty']) ?></td>
                                <td><?= date("Y-m-d H:i", strtotime($appointmentDate)) ?></td>
                                <td><?= htmlspecialchars($appointment['status']) ?></td>
                                <td>
                                    <?php 
                                    if ($appointment['status'] === 'confirmed') {
                                        $remaining_seconds = strtotime($appointmentDate) - time(); // Total seconds left
                                        if ($remaining_seconds > 0) {
                                            $days = floor($remaining_seconds / (60 * 60 * 24)); // Calculate days
                                            $hours = floor(($remaining_seconds % (60 * 60 * 24)) / (60 * 60)); // Calculate hours
                                            $minutes = floor(($remaining_seconds % (60 * 60)) / 60); // Calculate minutes
                                            $seconds = $remaining_seconds % 60; // Calculate seconds

                                            echo sprintf('%d يوم %02d:%02d:%02d', $days, $hours, $minutes, $seconds);
                                        } else {
                                            echo 'حان الموعد';
                                        }
                                    } elseif ($appointment['status'] === 'canceled') {
                                        echo 'مرفوض';
                                    } else {
                                        echo 'في انتظار التأكيد';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php 
                            $counter++;
                        endforeach;
                        endif;
                    } else {
                        ?>
                            <p>لا توجد مواعيد حالياً</p>
                        <?php 
                    }
                ?>
                </tbody>
                </table>
            </div>
            <!-- نافذة التأكيد -->
<div id="confirmationModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); text-align:center;">
    <div style="background:#fff; padding:20px; border-radius:10px; display:inline-block; margin-top:100px; position:relative;">
        <span class="close-btn" style="position:absolute; top:10px; right:10px; cursor:pointer;">&times;</span>
        <p id="modalMessage" style="margin-bottom:20px;">هل أنت متأكد؟</p>
        <button id="modalConfirmBtn" style="background-color:green; color:white; padding:10px; border:none; border-radius:5px;">تأكيد</button>
        <button id="modalCancelBtn" style="background-color:red; color:white; padding:10px; border:none; border-radius:5px;">إلغاء</button>
    </div>
</div>

    <script>
    // عناصر النافذة المنبثقة
    const modal = document.getElementById('confirmationModal');
    const closeBtn = document.querySelector('.close-btn');
    const modalCancelBtn = document.getElementById('modalCancelBtn');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');
    const modalMessage = document.getElementById('modalMessage');

    const notificationModal = document.getElementById('notificationModal');
    const okButton = document.getElementById('okButton');

    const actionMessages = {
        'confirm': 'هل أنت متأكد من تأكيد هذا الموعد؟',
        'cancel': 'هل أنت متأكد من إلغاء هذا الموعد؟'
    };

    let currentAction = '';
    let currentAppointmentId = 0;

    // فتح نافذة التأكيد
    function confirmAction(action, appointmentId) {
        currentAction = action;
        currentAppointmentId = appointmentId;
        modalMessage.textContent = actionMessages[action];
        modal.style.display = 'block';
    }

    // إغلاق نافذة التأكيد
    closeBtn.onclick = modalCancelBtn.onclick = function () {
        modal.style.display = 'none';
    }

    // عند الضغط على "تأكيد"
    modalConfirmBtn.onclick = function () {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';

        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = currentAction;

        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'appointment_id';
        idInput.value = currentAppointmentId;

        form.appendChild(actionInput);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
    }

    // نافذة تنبيه العد التنازلي (موعد خلال ساعة)
    okButton.onclick = function () {
        notificationModal.style.display = 'none';
    }

    
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const countdownElements = document.querySelectorAll('.countdown');

    countdownElements.forEach(elem => {
        const targetDate = new Date(elem.getAttribute('data-datetime'));
        updateCountdown();

        const interval = setInterval(updateCountdown, 1000);

        function updateCountdown() {
            const now = new Date();
            const diff = targetDate - now;

            if (diff <= 0) {
                elem.textContent = "🕒 حان الوقت";
                clearInterval(interval);

                // عرض التنبيه إن بقي أقل من ساعة
                const modal = document.getElementById("notificationModal");
                modal.style.display = "block";

                const okBtn = document.getElementById("okButton");
                okBtn.addEventListener("click", () => {
                    modal.style.display = "none";
                });

                return;
            }

            const hours = Math.floor(diff / 1000 / 60 / 60);
            const minutes = Math.floor((diff / 1000 / 60) % 60);
            const seconds = Math.floor((diff / 1000) % 60);

            elem.textContent = `${hours} س ${minutes} د ${seconds} ث`;

            // عرض النافذة إذا كان الموعد في أقل من ساعة
            if (diff < 3600000 && !modalDisplayed) {
                modalDisplayed = true;
                const modal = document.getElementById("notificationModal");
                modal.style.display = "block";

                const okBtn = document.getElementById("okButton");
                okBtn.addEventListener("click", () => {
                    modal.style.display = "none";
                });
            }
        }
    });

    let modalDisplayed = false;
});
document.addEventListener("DOMContentLoaded", () => {
    const countdownElements = document.querySelectorAll(".countdown");

    countdownElements.forEach(el => {
        const datetime = el.getAttribute("data-datetime");
        const targetDate = new Date(datetime).getTime();

        const interval = setInterval(() => {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance <= 0) {
                const elapsed = now - targetDate;

                // إذا فات على الموعد أكثر من ساعة (3600000 ملي ثانية)
                if (elapsed > 60 * 60 * 1000) {
                    el.textContent = "⏰ فات موعدك";
                    el.style.color = "#999";
                    clearInterval(interval);
                    return;
                }

                // الموعد حان للتو → أظهر التنبيه
                el.textContent = "📅 حان موعدك الآن!";
                el.style.color = "#e74c3c";
                clearInterval(interval);
                
                const popup = document.createElement("div");
                popup.style.position = "fixed";
                popup.style.top = "50%";
                popup.style.left = "50%";
                popup.style.transform = "translate(-50%, -50%)";
                popup.style.background = "#fff";
                popup.style.border = "2px solid #27ae60";
                popup.style.padding = "25px";
                popup.style.zIndex = "9999";
                popup.style.borderRadius = "10px";
                popup.style.boxShadow = "0 0 10px rgba(0,0,0,0.2)";
                popup.style.textAlign = "center";
                popup.innerHTML = `
                    <h3>⏰ تنبيه</h3>
                    <p>لقد حان موعدك الآن! يرجى الاستعداد.</p>
                    <button style="padding: 10px 20px; background: #27ae60; color: white; border: none; border-radius: 6px; cursor: pointer;" onclick="this.parentElement.remove()">حسنًا</button>
                `;
                document.body.appendChild(popup);

                return;
            }

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            el.textContent = `${hours}س ${minutes}د ${seconds}ث`;
        }, 1000);
    });
});

</script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // دالة لحساب الفارق الزمني بين الآن وموعد الموعد
    function checkCountdowns() {
        const countdowns = document.querySelectorAll('.countdown');
        const now = new Date();

        countdowns.forEach(span => {
            const appointmentTime = new Date(span.getAttribute('data-datetime'));
            const diffMs = appointmentTime - now;
            const diffMinutes = Math.floor(diffMs / 1000 / 60);

            if (diffMinutes <= 60 && diffMinutes > 0) {
                // عرض الوقت المتبقي
                const hours = Math.floor(diffMinutes / 60);
                const minutes = diffMinutes % 60;
                span.textContent = `${hours > 0 ? hours + " ساعة و " : ""}${minutes} دقيقة`;

                // عرض النافذة المنبثقة مرة واحدة فقط
                if (!sessionStorage.getItem('alertShown')) {
                    document.getElementById("notificationModal").style.display = "block";
                    sessionStorage.setItem('alertShown', true);
                }
            } else if (diffMinutes <= 0) {
                span.textContent = "الآن";
            } else {
                const hours = Math.floor(diffMinutes / 60);
                const minutes = diffMinutes % 60;
                span.textContent = `${hours > 0 ? hours + " ساعة و " : ""}${minutes} دقيقة`;
            }
        });
    }

    // تحقق من العد التنازلي كل 30 ثانية
    setInterval(checkCountdowns, 30000);
    checkCountdowns();

    // إغلاق النافذة المنبثقة
    document.getElementById("okButton").addEventListener("click", () => {
        document.getElementById("notificationModal").style.display = "none";
    });

    // نافذة التأكيد
    const modal = document.getElementById("confirmationModal");
    const closeBtn = document.querySelector(".close-btn");
    const cancelBtn = document.getElementById("modalCancelBtn");

    closeBtn.onclick = () => modal.style.display = "none";
    cancelBtn.onclick = () => modal.style.display = "none";

    // تنفيد العملية عند التأكيد
    let formAction = '';
    let formId = '';

    window.confirmAction = function(action, appointmentId) {
        formAction = action;
        formId = appointmentId;
        document.getElementById("modalMessage").textContent = 
            action === 'confirm' ? "هل تريد تأكيد هذا الموعد؟" : "هل تريد إلغاء هذا الموعد؟";
        modal.style.display = "block";
    }

    document.getElementById("modalConfirmBtn").onclick = () => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';

        const inputAction = document.createElement('input');
        inputAction.type = 'hidden';
        inputAction.name = 'action';
        inputAction.value = formAction;

        const inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = 'appointment_id';
        inputId.value = formId;

        form.appendChild(inputAction);
        form.appendChild(inputId);

        document.body.appendChild(form);
        form.submit();
    }
});
</script>


</body>
</html>