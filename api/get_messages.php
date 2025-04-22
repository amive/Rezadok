<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$receiver_id = isset($_GET['receiver_id']) ? $_GET['receiver_id'] : null;

if ($receiver_id) {
    $stmt = $conn->prepare("SELECT * FROM messages 
        WHERE (sender_id = :user_id AND receiver_id = :receiver_id)
           OR (sender_id = :receiver_id AND receiver_id = :user_id)
        ORDER BY created_at ASC");
    $stmt->execute([
        ':user_id' => $user_id,
        ':receiver_id' => $receiver_id
    ]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // تحويل التاريخ إلى تنسيق سهل القراءة
    foreach ($messages as &$message) {
        $message['created_at'] = date('H:i', strtotime($message['created_at']));
    }

    echo json_encode(['messages' => $messages]);
} else {
    echo json_encode(['error' => 'No receiver specified']);
}
