<?php
require_once 'check_admin.php';
$page_title = 'Quản Lý Chat';

// Xử lý gửi tin nhắn qua AJAX
if (isset($_POST['ajax_send'])) {
    header('Content-Type: application/json');
    $user_id = (int)$_POST['user_id'];
    $message = mysqli_real_escape_string($conn, trim($_POST['message']));
    
    if (!empty($message) && $user_id > 0) {
        $sql = "INSERT INTO messages (user_id, sender_type, message) VALUES ($user_id, 'admin', '$message')";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid data']);
    }
    exit;
}

// Lấy tin nhắn mới qua AJAX
if (isset($_GET['ajax_get_messages'])) {
    header('Content-Type: application/json');
    $user_id = (int)$_GET['user_id'];
    $last_id = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;
    
    $sql = "SELECT * FROM messages WHERE user_id = $user_id AND id > $last_id ORDER BY created_at ASC";
    $result = mysqli_query($conn, $sql);
    
    $messages = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = $row;
    }
    
    // Đánh dấu đã đọc
    mysqli_query($conn, "UPDATE messages SET is_read = 1 WHERE user_id = $user_id AND sender_type = 'user'");
    
    echo json_encode(['success' => true, 'messages' => $messages]);
    exit;
}

// Lấy user_id từ URL
$selected_user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

// Lấy danh sách người dùng đã chat
$users_sql = "SELECT u.id, u.fullname, u.email, 
              MAX(m.created_at) as last_message_time,
              SUM(CASE WHEN m.sender_type = 'user' AND m.is_read = 0 THEN 1 ELSE 0 END) as unread_count
              FROM users u
              INNER JOIN messages m ON u.id = m.user_id
              GROUP BY u.id, u.fullname, u.email
              ORDER BY last_message_time DESC";
$users = mysqli_query($conn, $users_sql);

// Nếu không có user nào chat, hiển thị tất cả users
if (!$users || mysqli_num_rows($users) == 0) {
    $users_sql = "SELECT id, fullname, email, 0 as unread_count FROM users WHERE role = 'user' ORDER BY id DESC";
    $users = mysqli_query($conn, $users_sql);
}

// Nếu có user được chọn, lấy tin nhắn
$messages = null;
$selected_user = null;
if ($selected_user_id > 0) {
    // Đánh dấu tin nhắn từ user là đã đọc
    mysqli_query($conn, "UPDATE messages SET is_read = 1 WHERE user_id = $selected_user_id AND sender_type = 'user'");
    
    // Lấy thông tin user
    $user_info = mysqli_query($conn, "SELECT * FROM users WHERE id = $selected_user_id");
    if ($user_info && mysqli_num_rows($user_info) > 0) {
        $selected_user = mysqli_fetch_assoc($user_info);
    }
    
    // Lấy tin nhắn
    $messages_sql = "SELECT * FROM messages WHERE user_id = $selected_user_id ORDER BY created_at ASC";
    $messages = mysqli_query($conn, $messages_sql);
}

include 'includes/header.php';
?>

<style>
.chat-container {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 0;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    height: 700px;
}
.user-list {
    background: #f8f9fa;
    border-right: 1px solid #dee2e6;
    overflow-y: auto;
}
.user-item {
    padding: 15px;
    border-bottom: 1px solid #dee2e6;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    color: inherit;
    display: block;
}
.user-item:hover {
    background: #e9ecef;
}
.user-item.active {
    background: #e3f2fd;
    border-left: 4px solid #2196f3;
}
.user-avatar {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 18px;
}
.chat-area {
    display: flex;
    flex-direction: column;
    height: 100%;
}
.chat-header {
    padding: 20px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-bottom: 1px solid #dee2e6;
}
.chat-messages {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    background: #f8f9fa;
}
.message {
    margin-bottom: 15px;
    display: flex;
}
.message.user {
    justify-content: flex-start;
}
.message.admin {
    justify-content: flex-end;
}
.message-bubble {
    max-width: 70%;
    padding: 12px 16px;
    border-radius: 18px;
    word-wrap: break-word;
}
.message.user .message-bubble {
    background: white;
    color: #333;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}
.message.admin .message-bubble {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}
.message-time {
    font-size: 11px;
    color: #999;
    margin-top: 4px;
}
.chat-input {
    padding: 20px;
    background: white;
    border-top: 1px solid #dee2e6;
}
.chat-input form {
    display: flex;
    gap: 10px;
}
.chat-input input {
    flex: 1;
    padding: 12px 16px;
    border: 1px solid #dee2e6;
    border-radius: 25px;
    font-size: 14px;
}
.chat-input button {
    padding: 12px 24px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-weight: 600;
}
.chat-input button:hover {
    opacity: 0.9;
}
.unread-badge {
    background: #dc3545;
    color: white;
    border-radius: 10px;
    padding: 2px 8px;
    font-size: 11px;
    font-weight: bold;
}
.empty-state {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #999;
    flex-direction: column;
}
.empty-state i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.3;
}
</style>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fas fa-comments"></i> Quản Lý Chat</h3>
    </div>
    <div class="admin-card-body" style="padding: 0;">
        <div class="chat-container">
            <!-- User List -->
            <div class="user-list">
                <div style="padding: 15px; background: #343a40; color: white; font-weight: 600;">
                    <i class="fas fa-users"></i> Danh Sách Chat
                </div>
                <?php if ($users && mysqli_num_rows($users) > 0): ?>
                    <?php while($user = mysqli_fetch_assoc($users)): ?>
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?user_id=<?php echo $user['id']; ?>" 
                       class="user-item <?php echo $selected_user_id == $user['id'] ? 'active' : ''; ?>">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="user-avatar">
                                <?php echo strtoupper(substr($user['fullname'], 0, 1)); ?>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; margin-bottom: 2px;">
                                    <?php echo htmlspecialchars($user['fullname']); ?>
                                </div>
                                <div style="font-size: 12px; color: #6c757d;">
                                    <?php echo $user['email']; ?>
                                </div>
                            </div>
                            <?php if (isset($user['unread_count']) && $user['unread_count'] > 0): ?>
                            <span class="unread-badge"><?php echo $user['unread_count']; ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="padding: 40px 20px; text-align: center; color: #999;">
                        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 10px;"></i>
                        <p>Chưa có tin nhắn nào</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Chat Area -->
            <div class="chat-area">
                <?php if ($selected_user): ?>
                    <!-- Chat Header -->
                    <div class="chat-header">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div class="user-avatar">
                                <?php echo strtoupper(substr($selected_user['fullname'], 0, 1)); ?>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 18px;">
                                    <?php echo htmlspecialchars($selected_user['fullname']); ?>
                                </h4>
                                <p style="margin: 0; font-size: 13px; opacity: 0.9;">
                                    <?php echo $selected_user['email']; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="chat-messages" id="chatMessages">
                        <?php if ($messages && mysqli_num_rows($messages) > 0): ?>
                            <?php 
                            $last_message_id = 0;
                            while($msg = mysqli_fetch_assoc($messages)): 
                                $last_message_id = $msg['id'];
                            ?>
                                <div class="message <?php echo $msg['sender_type']; ?>">
                                    <div class="message-bubble">
                                        <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                                        <div class="message-time">
                                            <?php echo date('H:i - d/m/Y', strtotime($msg['created_at'])); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div style="text-align: center; padding: 40px; color: #999;">
                                <i class="fas fa-comments" style="font-size: 48px; opacity: 0.3;"></i>
                                <p>Chưa có tin nhắn. Hãy bắt đầu trò chuyện!</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Input -->
                    <div class="chat-input">
                        <form id="chatForm" onsubmit="sendMessage(event)">
                            <input type="hidden" id="userId" value="<?php echo $selected_user_id; ?>">
                            <input type="text" id="messageInput" placeholder="Nhập tin nhắn của bạn..." required autofocus>
                            <button type="submit" id="sendBtn">
                                <i class="fas fa-paper-plane"></i> Gửi
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-comments"></i>
                        <h3>Chọn một cuộc trò chuyện</h3>
                        <p>Chọn người dùng bên trái để bắt đầu chat</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
let lastMessageId = <?php echo isset($last_message_id) ? $last_message_id : 0; ?>;
const userId = <?php echo $selected_user_id; ?>;

// Auto scroll to bottom
function scrollToBottom() {
    const chatMessages = document.getElementById('chatMessages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}

// Gửi tin nhắn qua AJAX
function sendMessage(event) {
    event.preventDefault();
    
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    // Disable button
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';
    
    // Send via AJAX
    fetch(window.location.pathname, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'ajax_send=1&user_id=' + userId + '&message=' + encodeURIComponent(message)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageInput.value = '';
            loadNewMessages();
        } else {
            alert('Lỗi: ' + (data.error || 'Không thể gửi tin nhắn'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Lỗi kết nối!');
    })
    .finally(() => {
        sendBtn.disabled = false;
        sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Gửi';
        messageInput.focus();
    });
}

// Load tin nhắn mới
function loadNewMessages() {
    if (userId <= 0) return;
    
    fetch(window.location.pathname + '?ajax_get_messages=1&user_id=' + userId + '&last_id=' + lastMessageId)
    .then(response => response.json())
    .then(data => {
        if (data.success && data.messages.length > 0) {
            const chatMessages = document.getElementById('chatMessages');
            data.messages.forEach(msg => {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'message ' + msg.sender_type;
                
                const bubble = document.createElement('div');
                bubble.className = 'message-bubble';
                bubble.innerHTML = msg.message.replace(/\n/g, '<br>') + 
                    '<div class="message-time">' + formatTime(msg.created_at) + '</div>';
                
                messageDiv.appendChild(bubble);
                chatMessages.appendChild(messageDiv);
                
                lastMessageId = msg.id;
            });
            scrollToBottom();
        }
    })
    .catch(error => console.error('Error loading messages:', error));
}

// Format time
function formatTime(datetime) {
    const date = new Date(datetime);
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return hours + ':' + minutes + ' - ' + day + '/' + month + '/' + year;
}

// Auto load tin nhắn mới mỗi 3 giây (KHÔNG reload trang)
<?php if ($selected_user_id > 0): ?>
setInterval(loadNewMessages, 3000);
scrollToBottom();
<?php endif; ?>
</script>

<?php include 'includes/footer.php'; ?>
