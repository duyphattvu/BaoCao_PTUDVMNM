<?php
require_once 'includes/config.php';
header('Content-Type: application/json');

// Log errors for debugging
error_log("Chat API called - Method: " . $_SERVER['REQUEST_METHOD']);

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    error_log("Chat API - Not logged in");
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Xử lý gửi tin nhắn
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send') {
    $message = mysqli_real_escape_string($conn, trim($_POST['message']));
    
    error_log("Chat API - Sending message: " . $message);
    
    if (!empty($message)) {
        $sql = "INSERT INTO messages (user_id, sender_type, message) VALUES ($user_id, 'user', '$message')";
        if (mysqli_query($conn, $sql)) {
            error_log("Chat API - Message sent successfully");
            echo json_encode(['success' => true]);
        } else {
            error_log("Chat API - MySQL Error: " . mysqli_error($conn));
            echo json_encode(['success' => false, 'message' => 'Lỗi database: ' . mysqli_error($conn)]);
        }
    } else {
        error_log("Chat API - Empty message");
        echo json_encode(['success' => false, 'message' => 'Tin nhắn trống']);
    }
    exit;
}

// Lấy tin nhắn
if (isset($_GET['action']) && $_GET['action'] === 'get') {
    // Đánh dấu tin nhắn từ admin là đã đọc
    mysqli_query($conn, "UPDATE messages SET is_read = 1 WHERE user_id = $user_id AND sender_type = 'admin'");
    
    // Lấy 50 tin nhắn gần nhất
    $sql = "SELECT * FROM messages WHERE user_id = $user_id ORDER BY created_at ASC LIMIT 50";
    $result = mysqli_query($conn, $sql);
    
    $messages = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $messages[] = [
                'id' => $row['id'],
                'sender_type' => $row['sender_type'],
                'message' => htmlspecialchars($row['message']),
                'time' => date('H:i', strtotime($row['created_at']))
            ];
        }
    }
    
    echo json_encode(['success' => true, 'messages' => $messages]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>
