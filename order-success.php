<?php
require_once 'includes/config.php';

$order_code = isset($_GET['order_code']) ? mysqli_real_escape_string($conn, $_GET['order_code']) : '';

if (empty($order_code)) {
    header('Location: index.php');
    exit;
}

// Lấy thông tin đơn hàng (mở rộng: lấy trạng thái thanh toán và thời điểm khách đã confirm)
$payment_method = '';
$order_total = 0;
$payment_status = '';
$customer_confirmed_at = null;
$res = mysqli_query($conn, "SELECT payment_method, total_amount, payment_status, customer_confirmed_at FROM orders WHERE order_code = '$order_code' LIMIT 1");
if ($res && mysqli_num_rows($res) > 0) {
    $order = mysqli_fetch_assoc($res);
    $payment_method = $order['payment_method'];
    $order_total = $order['total_amount'];
    $payment_status = isset($order['payment_status']) ? $order['payment_status'] : '';
    $customer_confirmed_at = isset($order['customer_confirmed_at']) ? $order['customer_confirmed_at'] : null;
}

// Kiểm tra nếu có payment_method từ GET (ưu tiên)
if (isset($_GET['payment_method']) && $_GET['payment_method'] !== '') {
    $payment_method = mysqli_real_escape_string($conn, $_GET['payment_method']);
}

$is_bank_transfer = ($payment_method === 'bank_transfer');

$page_title = 'Đặt Hàng Thành Công';
include 'includes/header.php';
// Quy tắc hiển thị success: nếu không phải chuyển khoản → hiển thị ngay
// Nếu là chuyển khoản → chỉ hiển thị khi khách đã upload chứng từ (customer_confirmed_at) hoặc khi payment_status = 'paid'
$show_success = (!$is_bank_transfer) || (!empty($customer_confirmed_at)) || ($payment_status === 'paid');
?>

<!-- Breadcrumb -->
<div class="bg-gradient-to-r from-amber-50 to-yellow-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex items-center text-sm text-gray-600">
            <a href="index.php" class="hover:text-amber-600 transition-colors">
                <i class="fas fa-home"></i> Trang chủ
            </a>
            <i class="fas fa-chevron-right mx-3 text-xs"></i>
            <span class="text-amber-600 font-medium">Đặt hàng thành công</span>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8 lg:py-12">
    <div class="max-w-2xl mx-auto">
        <?php if ($show_success): ?>
        <!-- Success Message -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
            <div class="p-8 lg:p-12 text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-green-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-check text-4xl text-white"></i>
                </div>

                <h2 class="text-3xl font-bold text-gray-800 mb-3">Đặt Hàng Thành Công!</h2>
                <p class="text-gray-600 text-lg mb-8">
                    Cảm ơn bạn đã tin tưởng và mua hàng tại cửa hàng chúng tôi.
                </p>

                <div class="bg-gradient-to-br from-amber-50 to-yellow-50 border-2 border-amber-200 rounded-xl p-6 mb-8">
                    <p class="text-gray-600 mb-2 text-sm">Mã đơn hàng của bạn</p>
                    <h3 class="text-3xl font-bold text-amber-600 mb-3"><?php echo $order_code; ?></h3>
                    <p class="text-sm text-gray-500">Vui lòng lưu lại mã này để tra cứu đơn hàng</p>
                </div>

                <?php if (!$is_bank_transfer): ?>
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-3 text-left">
                        <i class="fas fa-info-circle text-blue-500 text-xl mt-0.5"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">Phương thức thanh toán: COD (Thanh toán khi nhận hàng)</p>
                            <p>Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận đơn hàng. Vui lòng kiểm tra email hoặc số điện thoại của bạn.</p>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-3 text-left">
                        <i class="fas fa-check-circle text-green-500 text-xl mt-0.5"></i>
                        <div class="text-sm text-green-800">
                            <p class="font-semibold mb-1">Chúng tôi đã nhận được thông tin chuyển khoản của bạn</p>
                            <p>Đơn hàng sẽ được xử lý sau khi quản trị viên xác thực. Cảm ơn bạn!</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="index.php" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-amber-500 to-yellow-500 text-white px-8 py-3 rounded-xl font-semibold hover:from-amber-600 hover:to-yellow-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-home"></i>
                        <span>Về Trang Chủ</span>
                    </a>
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="orders.php" class="inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-700 px-8 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-300">
                        <i class="fas fa-list"></i>
                        <span>Đơn Hàng Của Tôi</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!-- Pending payment (bank transfer not yet confirmed by customer) -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
            <div class="p-8 lg:p-12 text-center">
                <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-clock text-2xl text-white"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-3">Đơn hàng đã được tạo — Chờ chuyển khoản</h2>
                <p class="text-gray-600 mb-4">Vui lòng quét mã QR phía dưới hoặc chuyển khoản theo hướng dẫn. Sau khi chuyển xong, bấm <strong>"Tôi đã chuyển khoản xong"</strong> và upload chứng từ để hệ thống ghi nhận.</p>

                <div class="bg-gradient-to-br from-amber-50 to-yellow-50 border-2 border-amber-200 rounded-xl p-6 mb-8">
                    <p class="text-gray-600 mb-2 text-sm">Mã đơn hàng của bạn</p>
                    <h3 class="text-3xl font-bold text-amber-600 mb-3"><?php echo $order_code; ?></h3>
                    <p class="text-sm text-gray-500">Vui lòng lưu lại mã này để tra cứu đơn hàng</p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- QR Code Section (Only for Bank Transfer and when customer hasn't confirmed yet) -->
        <?php if ($is_bank_transfer && !$show_success): ?>
        <div id="qrSection" class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-qrcode"></i>
                    <span>Thanh Toán Chuyển Khoản</span>
                </h3>
            </div>

            <div class="p-8 text-center">
                <div class="mb-6">
                    <p class="text-gray-700 text-lg font-semibold mb-2">Vui lòng quét mã QR để thanh toán</p>
                    <p class="text-gray-500 text-sm">Số tiền cần thanh toán: <span class="text-amber-600 font-bold text-xl"><?php echo number_format($order_total); ?>đ</span></p>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-2xl inline-block mb-6">
                    <div class="bg-white p-4 rounded-xl shadow-lg">
                        <img src="<?php echo BASE_URL; ?>assets/images/qr/qr_bank.png" 
                             alt="QR chuyển khoản" 
                             class="w-64 h-64 object-contain mx-auto">
                    </div>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-3 text-left">
                        <i class="fas fa-lightbulb text-amber-500 text-xl mt-0.5"></i>
                        <div class="text-sm text-amber-800">
                            <p class="font-semibold mb-1">Lưu ý khi chuyển khoản:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Vui lòng ghi rõ mã đơn hàng <strong><?php echo $order_code; ?></strong> trong nội dung chuyển khoản</li>
                                <li>Sau khi chuyển khoản thành công, đơn hàng sẽ được xử lý trong vòng 24h</li>
                                <li>Chúng tôi sẽ liên hệ xác nhận sau khi nhận được thanh toán</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <button onclick="confirmPayment()" class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-4 rounded-xl font-bold text-lg hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-check-circle mr-2"></i>
                    Tôi đã chuyển khoản xong
                </button>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Transfer confirmation modal -->
<div id="transferModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl w-full max-w-lg p-6 mx-4">
        <h3 class="text-lg font-bold mb-4">Xác nhận chuyển khoản</h3>
        <p class="text-sm text-gray-600 mb-4">Vui lòng upload chứng từ chuyển khoản hoặc nhập mã giao dịch. Việc xác thực sẽ do quản trị viên kiểm tra trước khi đánh dấu đã thanh toán.</p>
        <form id="transferForm" enctype="multipart/form-data">
            <input type="hidden" name="order_code" value="<?php echo htmlspecialchars($order_code); ?>">
            <div class="mb-3">
                <label class="block text-sm font-semibold mb-1">Số tiền (nếu muốn)</label>
                <input type="text" name="transfer_amount" class="w-full px-3 py-2 border rounded" placeholder="Ví dụ: 100000">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-semibold mb-1">Ngân hàng</label>
                <input type="text" name="bank_name" class="w-full px-3 py-2 border rounded" placeholder="Ngân hàng chuyển">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-semibold mb-1">Mã giao dịch (transaction id)</label>
                <input type="text" name="transaction_id" class="w-full px-3 py-2 border rounded" placeholder="Mã giao dịch">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-semibold mb-1">Ảnh chứng từ (JPG/PNG/PDF)</label>
                <input type="file" name="transfer_proof" accept="image/*,application/pdf" class="w-full">
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <button type="button" onclick="closeTransferModal()" class="px-4 py-2 rounded border">Hủy</button>
                <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white">Gửi xác nhận</button>
            </div>
        </form>
    </div>
</div>

<script>
function confirmPayment() {
    // Open modal for upload/confirmation
    const modal = document.getElementById('transferModal');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

function closeTransferModal() {
    const modal = document.getElementById('transferModal');
    if (modal) modal.classList.add('hidden');
}

// Handle form submit
document.addEventListener('DOMContentLoaded', function(){
    const form = document.getElementById('transferForm');
    if (!form) return;

    form.addEventListener('submit', function(e){
        e.preventDefault();
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Đang gửi...';

        const fd = new FormData(form);
        fetch('confirm-transfer.php', {
            method: 'POST',
            body: fd
        }).then(r => r.json()).then(data => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Gửi xác nhận';
            if (data.success) {
                closeTransferModal();
                // Replace qrSection with success message
                const qrSection = document.getElementById('qrSection');
                if (qrSection) {
                    qrSection.style.transition = 'opacity 0.4s ease-out, transform 0.4s ease-out';
                    qrSection.style.opacity = '0';
                    qrSection.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        qrSection.style.display = 'none';
                        const successMsg = document.createElement('div');
                        successMsg.className = 'bg-green-50 border-2 border-green-500 rounded-2xl p-6 text-center shadow-lg';
                        successMsg.innerHTML = `\n                            <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-500 rounded-full flex items-center justify-center mx-auto mb-4">\n                                <i class="fas fa-check text-2xl text-white"></i>\n                            </div>\n                            <h4 class="text-xl font-bold text-green-800 mb-2">Cảm ơn bạn!</h4>\n                            <p class="text-green-700">Chúng tôi đã ghi nhận thông tin. Đơn hàng của bạn sẽ được xử lý sau khi xác nhận thanh toán.</p>\n                        `;
                        qrSection.parentNode.appendChild(successMsg);
                    }, 450);
                }
            } else {
                alert(data.message || 'Lỗi khi gửi xác nhận');
            }
        }).catch(err => {
            console.error(err);
            alert('Lỗi kết nối');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Gửi xác nhận';
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
