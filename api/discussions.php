<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title> Rezadok | الدردشة </title>
    <link rel="stylesheet" href="css.css">
</head>
<body>
    <header>
        <h2><i class="fa-solid fa-stethoscope"></i> Rezadok</h2>
            <!--الرةابط-->
        <nav> 
           <a href="discussion.php" class="icon-btn"data-text="الرئيسية">
                <i class="fa-solid fa-house"></i>
            </a>
            <a href="appointments.php" class="icon-btn" data-text="مواعيدي">
                <i class="fa-solid fa-calendar-days"></i>
            </a>
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
    <div class="chat-container">
        <h2> <i class="fa-solid fa-comments"></i>الدردشة</h2>
        <form method="GET">
            <label>اختر المستخدم:</label>
            <select>
                <option></option>
                <option></option>
            </select>
            <button type="submit" id="start"> بدء الدردشة</button>
        </form>
        <h3><i class="fa-solid fa-envelope"></i> الرسائل:</h3>
        <div class="chat-box" id="chat-box">
            <div class="message received">
                <p>مرحبا! كيف حالك؟</p>
                <small>10:00 AM</small>
            </div>
            <div class="message sent">
                <p>أنا بخير، شكرا لك!</p>
                <small>10:02 AM</small>
            </div>
        </div>
        <form class="message-form">
            <button id="send" type="submit" class="icon-btn">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
            <textarea id="message-input" required placeholder="اكتب رسالتك هنا..."></textarea>
            <div class="file-upload">
                <button type="button" id="toggle-btn" class="icon-btn">
                    <i class="fa-solid fa-plus"></i>
                </button>
                <div class="file-buttons">
                    <input type="file" id="image-upload" accept="image/*" hidden>
                    <label for="image-upload" class="custom-btn tooltip" data-tooltip="تحميل صورة">
                        <i class="fa-solid fa-image"></i>
                    </label>
                    <input type="file" id="file-upload" accept=".pdf, .doc, .docx, .txt" hidden>
                    <label for="file-upload" class="custom-btn tooltip" data-tooltip="تحميل ملف">
                        <i class="fa-solid fa-file"></i>
                    </label>
                </div>
            </div>
        </form>   
    </div>     
</body>
</html>
