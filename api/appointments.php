<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Rezadok | إدارة المواعيد</title>
    <link rel="stylesheet" href="/Design/index.css">
</head>
<body>
    <header>
        <h2><i class="fa-solid fa-stethoscope"></i> Rezadok</h2>
            <!--الرةابط-->
        <nav>
            <a href="index.php" class="icon-btn"data-text="الرئيسية">
                <i class="fa-solid fa-house"></i>
            </a>
            <a href="discussions.php" class="icon-btn" data-text="الرسائل">
                <i class="fa-solid fa-comments"></i>
            </a>
            <!-- القائمة -->
            <div class="dropdown">
                <button>
                    <i class="fa-solid fa-user-circle"></i>
                </button>
                <div class="dropdown-content">
                    <a href="#"><i class="fa-solid fa-user"></i> حسابي</a>
                    <a href="#"><i class="fa-solid fa-cog"></i> الإعدادات</a>
                    <a href="#"><i class="fa-solid fa-sign-out-alt"></i> تسجيل الخروج</a>
                </div>
            </div>
        </nav>
    </header>
    <div class="container-appointments">
        <h2>🗓️ قائمة المواعيد</h2>
        <?php if ($role === 'doctor'): ?>
            <!-- جدول الطبيب -->
            <table>
                <tr>
                    <th>اسم المريض</th>
                    <th>نوع الحجز</th>
                    <th>التاريخ</th>
                    <th>الوقت</th>
                    <th>الإجراء</th>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <div class="button-container">
                            <button class="confirm-btn" data-text="تأكيد">✅</button>
                            <button class="cancel-btn" data-text="إلغاء">❌</button>
                        </div>                                                            
                    </td>
                </tr>
            </table>
        <?php elseif ($role === 'patient'): ?>
            <!--جدول المريض -->
            <table>
                <tr>
                    <th>اسم الطبيب</th>
                    <th>نوع الحجز</th>
                    <th>التاريخ</th>
                    <th>الوقت</th>
                    <th>حالة الحجز</th>
                    <th>كم متبقي؟</th>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        <?php else: ?>
            <p id="error">⚠️ يجب تسجيل الدخول لعرض المواعيد!</p>
        <?php endif; ?>
    </div>
</body>
</html>
