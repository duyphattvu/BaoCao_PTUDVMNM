<?php
require_once 'check_admin.php';
header('Content-Type: application/json');

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

if ($user_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid user']);
    exit;
}

// Đánh dấu tin nhắn từ user là đã đọc
mysqli_query($conn, "UPDATE messages SET is_read = 1 WHERE user_id = $user_id AND sender_type = 'user'");

// Lấy tin nhắn
$messages_sql = "SELECT * FROM messages WHERE user_id = $user_id ORDER BY created_at ASC";
$messages = mysqli_query($conn, $messages_sql);

$html = '';
if ($messages && mysqli_num_rows($messages) > 0) {
    while($msg = mysqli_fetch_assoc($messages)) {
        $isAdmin = $msg['sender_type'] == 'admin';
        $justify = $isAdmin ? 'flex-end' : 'flex-start';
        
        $html .= '<div style="display: flex; justify-content: ' . $justify . '; margin-bottom: 16px;">';
        $html .= '<div style="max-width: 70%;">';
        
        if (!$isAdmin) {
            $html .= '<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">';
            $html .= '<i class="fas fa-user" style="color: #9ca3af; font-size: 12px;"></i>';
            $html .= '<span style="font-size: 11px; color: #6b7280; font-weight: 600;">Khách hàng</span>';
            $html .= '</div>';
        }
        
        $messageClass = $isAdmin ? 'message-admin' : 'message-user';
        $html .= '<div class="' . $messageClass . '" style="border-radius: 16px; padding: 12px 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">';
        $html .= '<p style="margin: 0; font-size: 14px; line-height: 1.5;">' . nl2br(htmlspecialchars($msg['message'])) . '</p>';
        $html .= '</div>';
        
        $textAlign = $isAdmin ? 'text-align: right;' : '';
        $html .= '<p style="font-size: 11px; color: #9ca3af; margin: 4px 0 0 0; ' . $textAlign . '">';
        $html .= date('H:i - d/m/Y', strtotime($msg['created_at']));
        $html .= '</p>';
        
        $html .= '</div>';
        $html .= '</div>';
    }
} else {
    $html = '<div style="text-align: center; padding: 40px; color: #9ca3af;">';
    $html .= '<i class="fas fa-comments" style="font-size: 48px; opacity: 0.3; margin-bottom: 10px;"></i>';
    $html .= '<p>Chưa có tin nhắn. Hãy bắt đầu cuộc trò chuyện!</p>';
    $html .= '</div>';
}

echo json_encode([
    'success' => true,
    'html' => $html,
    'count' => mysqli_num_rows($messages)
]);
?>
