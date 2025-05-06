<?php
include 'config.php';
require __DIR__ . '/../vendor/autoload.php'; // Include the Cloudinary SDK

use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;

// التحقق من وجود الكوكيز بدلاً من الجلسة
if (!isset($_COOKIE['user_id'])) {
    header("Location: /");
    exit();
}

$user_id = $_COOKIE['user_id']; // استخدام الكوكيز للحصول على user_id
$role = $_COOKIE['role']; // استخدام الكوكيز للحصول على الدور

if ($role == 'patient') {
    $stmt = $conn->prepare("SELECT id, name FROM users WHERE role = 'doctor'");
} else {
    $stmt = $conn->prepare("SELECT id, name FROM users WHERE role = 'patient'");
}
$stmt->execute();
$contacts = $stmt->fetchAll();

$receiver_id = isset($_GET['receiver_id']) ? $_GET['receiver_id'] : null;
// إرسال رسالة
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $receiver_id) {
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $file_path = null;
    $file_type = null;

    // معالجة الملفات المرفقة


// Cloudinary Configuration
$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => 'dkxmhw89v',
        'api_key'    => '856592243673251',
        'api_secret' => '6swgUqDkfTRe4Lyu52OHZHt0eJ8',
    ],
]);

if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['attachment']['tmp_name'];
    $file_name = $_FILES['attachment']['name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'jfif'])) {
        $file_type = "image";
    } else {
        $file_type = "file";
    }

    try {
        // اسم الملف بدون الامتداد
        $originalName = pathinfo($file_name, PATHINFO_FILENAME);

        // إزالة أي رموز خاصة من الاسم
        $sanitizedName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);

        // رفع الملف إلى Cloudinary
        $uploadResult = $cloudinary->uploadApi()->upload($file_tmp, [
            'folder' => $file_type === "image" ? "chat/images" : "chat/files",
            'public_id' => $sanitizedName . '_' . time(), // إضافة الوقت لتفادي التكرار
            'resource_type' => $file_type === "image" ? "image" : "auto",
        ]);

        $file_path = $uploadResult['secure_url'];
    } catch (Exception $e) {
        die("Error uploading file to Cloudinary: " . $e->getMessage());
    }
}


if (!empty($message) || !empty($file_path)) {
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, file_path, file_type)
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $receiver_id, $message, $file_path, $file_type]);
}

header("Location: chat?receiver_id=$receiver_id");
exit();
}

// جلب الرسائل
$messages = [];
if ($receiver_id) {
    $stmt = $conn->prepare("SELECT * FROM messages 
        WHERE (sender_id = :user_id AND receiver_id = :receiver_id)
           OR (sender_id = :receiver_id AND receiver_id = :user_id)
        ORDER BY created_at ASC");
    $stmt->execute([
        ':user_id' => $user_id,
        ':receiver_id' => $receiver_id
    ]);
    $messages = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>الدردشة - Rezadok</title>
    <link rel="stylesheet" href="Design/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link rel="stylesheet" type="text/css" href="https://www.fontstatic.com/f=geeza-pro" />

    <style>
        #chat-box {
            max-height: 400px;
            overflow-y: scroll; /* نسمح بالتمرير */
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none;  /* Internet Explorer 10+ */
        }

        #chat-box::-webkit-scrollbar {
            display: none; /* Chrome, Safari and Edge */
        }
        /* Enlarged image styling */
        .enlarged {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 90%;
            max-height: 90%;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: white; /* Optional: Add a background color for better visibility */
            cursor: zoom-out;
            width: auto; /* Ensure the image scales properly */
            height: auto; /* Ensure the image scales properly */
        }
        /* Blurred background styling */
        .blurred {
            filter: blur(5px);
            pointer-events: none; /* Prevent interactions with blurred content */
        }
    </style>
</head>

<body>
<header>
    <h2><a href=""><i class="fa-solid fa-stethoscope"></i></a>Rezadok</h2>
    <nav>
        <a href="/" class="icon-btn" data-text="الرئيسية"><i class="fa-solid fa-house"></i></a>
        <a href="appointments" class="icon-btn" data-text="مواعيدي"><i class="fa-solid fa-calendar-days"></i></a>
        <div class="dropdown">
            <button><i class="fa-solid fa-user-circle"></i></button>
            <div class="dropdown-content">
                <a href="settings"><i class="fa-solid fa-cog"></i> الإعدادات</a>
                <a href="logout"><i class="fa-solid fa-sign-out-alt"></i> تسجيل الخروج</a>
            </div>
        </div>
    </nav>
</header>

<div class="chat-container">
    <h2>💬 الدردشة</h2>
    
    <form method="GET">
        <label>اختر المستخدم:</label>
        <select name="receiver_id" required onchange="this.form.submit()">
            <option disabled selected>-- اختر --</option>
            <?php foreach ($contacts as $contact): ?>
                <option value="<?= $contact['id']; ?>" <?= $receiver_id == $contact['id'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($contact['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($receiver_id): ?>
        <h3>📨 الرسائل:</h3>
        <div class="chat-box" id="chat-box" style="border:1px solid #ccc; padding:10px;">
            <?php if (count($messages) == 0): ?>
                <p>لا توجد رسائل بعد.</p>
            <?php else: ?>
                <?php foreach ($messages as $msg): ?>
                    <div class="message <?= $msg['sender_id'] == $user_id ? 'sent' : 'received'; ?>">
                        <?php if (!empty($msg['message'])): ?>
                            <p><?= nl2br(htmlspecialchars($msg['message'])); ?></p>
                        <?php endif; ?>

                        <?php if (!empty($msg['file_path'])): ?>
                            <?php if ($msg['file_type'] == 'image'): ?>
                                <img src="<?= $msg['file_path']; ?>" alt="مرفق" style="max-width:200px;">
                            <?php else: ?>
                                <a href="<?= $msg['file_path']; ?>" target="_blank">📎 تحميل الملف</a>
                            <?php endif; ?>
                        <?php endif; ?>

                        <small><?= date('H:i', strtotime($msg['created_at'])); ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- نموذج إرسال الرسالة والمرفقات -->
        <form method="POST" enctype="multipart/form-data" style="display:flex; gap:10px;">
            <textarea name="message" placeholder="اكتب رسالتك هنا..." style="flex:1;"></textarea>
            <input type="file" name="attachment" id="attachment" hidden>
            <label for="attachment" style="cursor:pointer;">📎</label>
            <button type="submit">📤 إرسال</button>
        </form>
    <?php endif; ?>
</div>

<script>
    const receiverId = <?= json_encode($receiver_id); ?>;
    const userId = <?= json_encode($user_id); ?>;
</script>
<script src="script/chat.js"></script>

</body>
</html>
