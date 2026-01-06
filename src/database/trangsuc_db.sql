-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2026 at 07:51 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trangsuc_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `position` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `image`, `link`, `position`, `status`, `created_at`) VALUES
(1, 'Banner Trang Sức Cao Cấp', 'banner_2.jpg', '#', 1, 1, '2025-12-07 17:29:51'),
(2, 'Khuyến Mãi Đặc Biệt', 'banner_Lac.jpg', '#', 2, 1, '2025-12-07 17:29:51'),
(3, 'Bộ Sưu Tập Mới', 'banner_vb.jpg', '#', 3, 1, '2025-12-07 17:29:51');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Dây chuyền', 'day-chuyen', 'Dây chuyền bạc cao cấp', NULL, 1, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(2, 'Lắc tay', 'lac-tay', 'Lắc tay bạc thời trang', NULL, 1, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(3, 'Nhẫn nữ', 'nhan-nu', 'Nhẫn bạc nữ đẹp', NULL, 1, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(4, 'Bông tai', 'bong-tai', 'Bông tai bạc sang trọng', NULL, 1, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(5, 'Nhẫn đôi', 'nhan-doi', 'Nhẫn đôi cặp tình nhân', NULL, 1, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(6, 'Lắc chân', 'lac-chan', 'Lắc chân bạc nữ tính', NULL, 1, '2025-12-07 17:29:51', '2025-12-07 17:29:51');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('new','processing','completed') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `fullname`, `email`, `phone`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 'Phát', 'nguyenduyphat2019@gmail.com', '92168217', 'dây chuyển', 'daiugsgduiasdugasud', 'completed', '2025-12-09 07:08:17');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT 5,
  `comment` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sender_type` enum('user','admin') NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `sender_type`, `message`, `is_read`, `created_at`) VALUES
(1, 1, 'user', 'chòa bạn', 1, '2025-12-07 19:41:17'),
(2, 1, 'user', 'sadasd', 1, '2025-12-07 19:42:36'),
(3, 1, 'user', 'ádasd', 1, '2025-12-07 19:44:48'),
(4, 2, 'user', 'gvcvg', 1, '2025-12-07 19:50:46'),
(5, 2, 'user', 'ads', 1, '2025-12-08 00:39:47'),
(6, 2, 'admin', 'sadasd', 0, '2025-12-08 00:47:57'),
(7, 1, 'admin', 'àasf', 1, '2025-12-08 00:48:27'),
(8, 1, 'user', 'mnhbvhk', 1, '2025-12-08 06:42:29'),
(9, 1, 'user', 'ádasd', 1, '2025-12-08 07:27:33'),
(10, 1, 'admin', 'sdfsdf', 1, '2025-12-08 07:27:55'),
(11, 1, 'user', 'tôi muốn tư vấn dây chuyền', 1, '2025-12-09 03:14:28'),
(12, 1, 'admin', 'sadsda', 1, '2025-12-09 03:15:12'),
(13, 1, 'admin', 'bạn muốn dây chuyền loại nào', 1, '2025-12-09 03:25:24'),
(14, 1, 'user', 'tôi muốn dây chuyền bạc mặc dây chuyền trái tim', 1, '2025-12-09 06:03:20'),
(15, 1, 'user', 'tôi muốn tư vấn', 1, '2025-12-09 06:09:52'),
(16, 1, 'admin', 'bạn muốn tư vấn những sản phẩm nào', 1, '2025-12-09 06:10:19'),
(17, 3, 'user', 'hello', 1, '2025-12-23 07:07:30'),
(18, 3, 'user', 'hello', 1, '2025-12-23 07:07:36');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `slug`, `content`, `image`, `author`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Xu Hướng Trang Sức Bạc 2025: Minimalism & Layering Thống Trị', 'xu-huong-trang-suc-bac-2025', 'Năm 2025 đánh dấu sự thống trị của hai trường phái thiết kế trang sức bạc hoàn toàn đối lập nhưng đều được giới trẻ yêu thích: Minimalism (tối giản) và Layering (xếp tầng).\r\n\r\n**1. Phong Cách Minimalism - Vẻ Đẹp Của Sự Đơn Giản**\r\n\r\nXu hướng tối giản tiếp tục là \"hot trend\" với các thiết kế đường nét thanh mảnh, không hoa văn cầu kỳ. Những chiếc nhẫn bạc mảnh, dây chuyền đơn giản chỉ với một mặt pendant nhỏ đang được ưa chuộng vì tính ứng dụng cao - dễ phối đồ từ công sở đến dạo phố.\r\n\r\nCác thương hiệu trang sức bạc nổi tiếng như Pandora, Thomas Sabo đều tập trung vào dòng sản phẩm minimal với bạc trắng bóng hoặc bạc oxy hóa nhẹ tạo độ vintage.\r\n\r\n**2. Layering - Nghệ Thuật Xếp Tầng**\r\n\r\nNgược lại với minimalism, xu hướng layering khuyến khích người đeo \"chồng chất\" nhiều món trang sức cùng lúc. Điển hình là việc đeo 3-5 dây chuyền với độ dài khác nhau, hoặc xếp 5-7 chiếc nhẫn mảnh trên các ngón tay.\r\n\r\nInfluencer Châu Bùi, Khánh Linh TheKhanh... thường xuyên xuất hiện với phong cách layering ấn tượng, tạo nên trend \"càng nhiều càng đẹp\" trong giới trẻ Việt Nam.\r\n\r\n**3. Chunky Chain - Dây Xích To Bản**\r\n\r\nDây chuyền xích to, nhẫn chain massivie đang trở lại mạnh mẽ, đặc biệt được các bạn nam yêu thích. Phong cách này mang đến vẻ mạnh mẽ, cá tính và rất \"unisex\".\r\n\r\n**4. Bạc & Ngọc Trai - Sự Kết Hợp Hoàn Hảo**\r\n\r\nCombo bạc kết hợp ngọc trai (đặc biệt là ngọc trai nước ngọt) đang hot nhất thị trường. Vừa trẻ trung vừa sang trọng, phù hợp từ học sinh, sinh viên đến người đi làm.\r\n\r\n**5. Personalized Jewelry - Trang Sức Cá Nhân Hóa**\r\n\r\nKhắc tên, ngày sinh, tọa độ địa lý, thông điệp riêng... lên trang sức bạc đang là dịch vụ được săn đón. Giới trẻ sẵn sàng chi thêm 200-500k cho món trang sức độc nhất vô nhị của riêng mình.\r\n\r\nTheo khảo sát của Hiệp hội Trang sức Việt Nam, thị trường trang sức bạc năm 2025 dự kiến tăng trưởng 25% so với năm trước, chủ yếu từ phân khúc giá 200k-1 triệu đồng.', 'tin tuc 1.webp', 'Admin', 1, '2025-12-07 17:29:51', '2025-12-07 18:42:10'),
(2, 'Cách Phân Biệt Bạc Thật - Giả: 7 Mẹo Đơn Giản Tại Nhà', 'phan-biet-bac-that-gia', 'Thị trường tràn lan hàng giả khiến nhiều người mua phải trang sức bạc kém chất lượng. Dưới đây là 7 cách đơn giản giúp bạn nhận biết bạc thật - giả ngay tại nhà.\r\n\r\n**1. Kiểm Tra Dấu Hallmark (Quan Trọng Nhất)**\r\n\r\nTrang sức bạc thật LUÔN có dấu hallmark:\r\n- 925 hoặc Sterling: 92.5% bạc + 7.5% kim loại khác\r\n- 900: 90% bạc\r\n- 950: 95% bạc\r\n- 999: Bạc nguyên chất (hiếm, mềm, dễ biến dạng)\r\n\r\nDấu thường ở mặt trong nhẫn, móc khóa dây chuyền, hoặc mặt sau bông tai. Nếu KHÔNG có dấu → 90% là hàng giả hoặc mạ bạc.\r\n\r\n**2. Test Nam Châm (30 Giây)**\r\n\r\nBạc thật KHÔNG bị nam châm hút (hoặc hút rất yếu). Nếu trang sức bị hút mạnh → chắc chắn là sắt/thép mạ bạc.\r\n\r\nLưu ý: Một số hợp kim bạc thật vẫn có thể hút yếu do chứa 7.5% kim loại khác, nhưng KHÔNG BAO GIỜ hút mạnh như sắt.\r\n\r\n**3. Thử Acid (Chính Xác Nhất - Dùng Cho Món Đắt Tiền)**\r\n\r\nMua bộ test acid bạc trên Shopee (50-100k). Nhỏ một giọt lên vị trí kín:\r\n- Bạc thật: Dung dịch chuyển màu đỏ sẫm\r\n- Bạc giả: Chuyển xanh hoặc không đổi màu\r\n\r\n**4. Kiểm Tra Độ Bóng & Màu Sắc**\r\n\r\n- Bạc thật: Có độ bóng tự nhiên, màu trắng bạc đặc trưng\r\n- Bạc giả: Thường quá bóng loáng (do xi mạ), hoặc có màu hơi xanh/hơi vàng\r\n\r\n**5. Test Băng Nóng (Thermal Test)**\r\n\r\nBạc có độ dẫn nhiệt cao nhất trong các kim loại. Cho trang sức vào nước đá 2-3 phút, sau đó cầm lên:\r\n- Bạc thật: Ấm lên rất nhanh (5-10 giây)\r\n- Bạc giả: Ấm chậm hoặc vẫn lạnh\r\n\r\n**6. Dùng Khăn Trắng Lau Mạnh**\r\n\r\nLau mạnh trang sức bằng khăn trắng:\r\n- Bạc thật: Khăn có vết đen (do bạc bị oxy hóa nhẹ)\r\n- Bạc giả: Khăn không đổi màu hoặc có vết xanh/vàng\r\n\r\n**7. Kiểm Tra Giá Cả**\r\n\r\nGiá bạc 925 hiện tại (tháng 12/2025): ~1.200đ/gram (chưa tính công)\r\n- Nhẫn nữ (2-3g): ít nhất 100-150k\r\n- Dây chuyền (5-7g): ít nhất 200-300k\r\n\r\nNếu \"bạc 925\" giá rẻ hơn → 99% là giả.\r\n\r\n**Lời Khuyên:**\r\nNên mua trang sức bạc tại cửa hàng uy tín có:\r\n- Tem phiếu bảo hành rõ ràng\r\n- Ghi rõ hàm lượng bạc\r\n- Cam kết đổi trả nếu phát hiện hàng giả\r\n\r\nTránh mua hàng không rõ nguồn gốc trên mạng với giá quá rẻ!', 'tin tuc 2.webp\r\n', 'Admin', 1, '2025-12-07 17:29:51', '2025-12-07 18:44:59'),
(3, 'Bí Quyết Bảo Quản Trang Sức Bạc Không Bị Đen Xỉn', 'bao-quan-trang-suc-bac', 'Trang sức bạc rất dễ bị oxy hóa, đen xỉn theo thời gian nếu không bảo quản đúng cách. Dưới đây là bí quyết từ các chuyên gia trang sức giúp bạc luôn sáng bóng.\r\n\r\n**TẠI SAO BẠCBỊ ĐEN?**\r\n\r\nBạc phản ứng với lưu huỳnh (sulfur) trong không khí, mồ hôi, mỹ phẩm... tạo thành bạc sulfide (Ag₂S) có màu đen. Đây là phản ứng tự nhiên, KHÔNG phải do bạc giả.\r\n\r\nMôi trường ẩm, tiếp xúc hóa chất sẽ đẩy nhanh quá trình oxy hóa.\r\n\r\n**1. LƯU TRỮ ĐÚNG CÁCH**\r\n\r\n✅ **CÓ nên:**\r\n- Bảo quản trong hộp kín, túi zip có khóa\r\n- Cho gói chống ẩm silica gel vào hộp\r\n- Mỗi món trang sức một ngăn riêng (tránh cọ xát)\r\n- Bọc giấy mỏng hoặc túi vải mềm\r\n- Để nơi khô ráo, thoáng mát\r\n\r\n❌ **KHÔNG nên:**\r\n- Để ngăn kéo hở (tiếp xúc không khí)\r\n- Để phòng tắm (độ ẩm cao)\r\n- Treo lộ thiên trên giá trang sức\r\n- Để chung nhiều món chạm nhau\r\n\r\n**2. VỆ SINH ĐỊNH KỲ**\r\n\r\n**Sau mỗi lần đeo:**\r\n- Lau nhẹ bằng khăn mềm\r\n- Loại bỏ mồ hôi, bụi bẩn\r\n\r\n**Mỗi tuần:**\r\n- Ngâm nước ấm + 2-3 giọt nước rửa chén 5 phút\r\n- Dùng bàn chải lông mềm chải nhẹ\r\n- Rửa nước sạch, lau khô hoàn toàn\r\n\r\n**Mỗi tháng:**\r\n- Dùng khăn chuyên dụng đánh bóng bạc\r\n- Hoặc đem đến cửa hàng vệ sinh miễn phí\r\n\r\n**3. TRÁNH TIẾP XÚC HÓA CHẤT**\r\n\r\n❌ Tháo trang sức khi:\r\n- Tắm (xà phòng, dầu gội)\r\n- Rửa bát (nước rửa chén)\r\n- Tẩy tóc, nhuộm tóc\r\n- Đi bơi (clo trong hồ bơi)\r\n- Xịt nước hoa, sơn móng tay\r\n- Tập gym/thể thao (mồ hôi nhiều)\r\n\r\n⏰ **Quy tắc vàng:** Trang sức là món ĐEO CUỐI CÙNG (sau khi trang điểm, xịt nước hoa xong) và THÁO ĐẦU TIÊN khi về nhà.\r\n\r\n**4. CÁCH LÀM SẠCH BẠCBỊ ĐEN TẠI NHÀ**\r\n\r\n**Cách 1: Kem đánh răng (dễ nhất)**\r\n- Dùng kem đánh răng không màu\r\n- Chà nhẹ bằng bàn chải mềm\r\n- Rửa sạch, lau khô\r\n\r\n**Cách 2: Baking Soda + Giấm (hiệu quả)**\r\n- Trộn 2 thìa baking soda + 1 thìa giấm trắng\r\n- Ngâm trang sức 2-3 giờ\r\n- Chà nhẹ, rửa sạch\r\n\r\n**Cách 3: Giấy bạc + Muối + Nước nóng (thần thánh)**\r\n- Lót giấy bạc (nhôm) vào tô\r\n- Cho 2 thìa muối + nước sôi\r\n- Ngâm trang sức 5-10 phút\r\n- Bạc sẽ sáng trở lại do phản ứng hóa học\r\n\r\n**5. BẢO DƯỠNG CHUYÊN NGHIỆP**\r\n\r\nMỗi 6 tháng nên mang trang sức đến cửa hàng uy tín để:\r\n- Vệ sinh siêu âm (loại bụi bẩn sâu)\r\n- Đánh bóng chuyên nghiệp\r\n- Kiểm tra khóa, móc (tránh rơi mất)\r\n- Xi mạ lại nếu cần (cho trang sức bạc mạ vàng/rhodium)\r\n\r\nChi phí: Thường MIỄN PHÍ nếu mua tại cửa hàng đó.\r\n\r\nÁp dụng đúng các bí quyết trên, trang sức bạc của bạn sẽ sáng đẹp như mới trong nhiều năm!', 'tin tuc 3.webp', 'Admin', 1, '2025-12-07 17:29:51', '2025-12-07 18:47:21'),
(4, 'Trang Sức Bạc Cho Da Nhạy Cảm: Những Điều Cần Biết', 'trang-suc-bac-da-nhay-cam', 'Nhiều người lo lắng khi đeo trang sức bạc vì da bị ngứa, đỏ, kích ứng. Vậy da nhạy cảm có đeo được bạc không? Câu trả lời là CÓ, nhưng cần chọn đúng loại.\r\n\r\n**TRANG SỨC BẠC CÓ GÂY DỊ ỨNG KHÔNG?**\r\n\r\nTin tốt: **Bạc nguyên chất KHÔNG gây dị ứng.** Bạc là kim loại hypoallergenic (ít gây dị ứng) và an toàn cho hầu hết mọi người, kể cả da nhạy cảm.\r\n\r\nTuy nhiên, vấn đề nằm ở **7.5% kim loại hợp kim** trong bạc 925:\r\n- Nếu chứa **niken (nickel)** → Dễ gây dị ứng\r\n- Nếu chứa **đồng (copper)** → Ít gây dị ứng hơn\r\n\r\n**CÁC LOẠI BẠC PHÙ HỢP DA NHẠY CẢM**\r\n\r\n**1. Bạc 925 Sterling (Nickel-Free) ⭐⭐⭐⭐⭐**\r\n- Hợp kim: 92.5% bạc + 7.5% đồng (không niken)\r\n- An toàn cho da nhạy cảm\r\n- Giá: 200k - 2 triệu/món\r\n- **Khuyên dùng:** Tìm sản phẩm có ghi \"Nickel Free\"\r\n\r\n**2. Bạc Italia 925 ⭐⭐⭐⭐⭐**\r\n- Bạc cao cấp từ Italy\r\n- Quy trình sản xuất nghiêm ngặt, không niken\r\n- Bề mặt mịn màng, ít oxy hóa\r\n- Giá: 500k - 5 triệu/món\r\n\r\n**3. Bạc Thái 925 ⭐⭐⭐⭐**\r\n- Bạc từ Thái Lan với thiết kế thủ công\r\n- Thường không chứa niken\r\n- Giá: 300k - 3 triệu/món\r\n\r\n**4. Bạc 950 hoặc 999 ⭐⭐⭐⭐⭐**\r\n- Hàm lượng bạc cao hơn (95% hoặc 99.9%)\r\n- Ít hợp kim hơn → ít dị ứng hơn\r\n- Nhược điểm: Mềm hơn, dễ móp méo\r\n- Giá: Cao hơn bạc 925 khoảng 20-30%\r\n\r\n**NHỮNG LOẠI NÊNRÁNH**\r\n\r\n❌ **Bạc mạ (Silver Plated)**\r\n- Chỉ lớp bạc mỏng bên ngoài\r\n- Bên trong là kim loại rẻ tiền (thường có niken)\r\n- Lớp mạ dễ bong → Da tiếp xúc niken → Dị ứng\r\n\r\n❌ **Bạc Thổ Nhĩ Kỳ, bạc không rõ nguồn gốc**\r\n- Thường chứa niken cao\r\n- Không đạt chuẩn nickel-free\r\n\r\n**DẤU HIỆU DỊ ỨNG TRANG SỨC**\r\n\r\nNếu sau 2-3 ngày đeo xuất hiện:\r\n- Ngứa, nổi mẩn đỏ vùng tiếp xúc\r\n- Da khô, bong tróc\r\n- Sưng nhẹ, nóng rát\r\n- Vết đen xanh trên da (do đồng oxy hóa - ít nghiêm trọng)\r\n\r\n→ Ngưng đeo ngay và đến bác sĩ da liễu nếu nặng.\r\n\r\n**CÁCH GIẢM THIỂU DỊ ỨNG**\r\n\r\n**1. Chọn bạc có chứng nhận**\r\n- Ghi rõ \"Nickel Free\" hoặc \"Hypoallergenic\"\r\n- Có giấy chứng nhận chất lượng\r\n\r\n**2. Mạ rhodium hoặc vàng**\r\n- Tạo lớp bảo vệ giữa da và bạc\r\n- Giảm tiếp xúc trực tiếp với hợp kim\r\n- Chi phí: +100-300k\r\n\r\n**3. Sơn móng tay trong suốt**\r\n- Quét lớp mỏng lên mặt trong nhẫn, móc bông tai\r\n- Tạo màng ngăn cách\r\n- Mẹo dân gian hiệu quả\r\n\r\n**4. Vệ sinh thường xuyên**\r\n- Lau sạch mồ hôi, bụi bẩn sau mỗi lần đeo\r\n- Tránh vi khuẩn, hóa chất tích tụ\r\n\r\n**5. Không đeo quá lâu ban đầu**\r\n- Tuần đầu: Đeo 2-3 giờ/ngày\r\n- Theo dõi phản ứng da\r\n- Nếu ổn, tăng dần thời gian\r\n\r\n**LỜI KHUYÊN TỪ BÁC SĨ DA LIỄU**\r\n\r\nBS. Nguyễn Thị Lan Anh (BV Da Liễu TP.HCM) khuyên:\r\n\r\n*\"Da nhạy cảm hoàn toàn có thể đeo trang sức bạc 925 nickel-free. Tuy nhiên, nếu bạn từng bị dị ứng với trang sức kim loại khác, nên test nhỏ trước: đeo ở cổ tay 1-2 ngày, quan sát phản ứng trước khi đeo bông tai hoặc nhẫn.\"*\r\n\r\n**KẾT LUẬN**\r\n\r\n✅ Da nhạy cảm có thể đeo bạc, nhưng chọn:\r\n- Bạc 925 Sterling nickel-free\r\n- Bạc Italia, Thái 925\r\n- Bạc 950 hoặc 999\r\n- Có chứng nhận, xuất xứ rõ ràng\r\n\r\n❌ Tránh:\r\n- Bạc mạ\r\n- Bạc không rõ nguồn gốc\r\n- Bạc giá rẻ bất thường\r\n\r\nMua trang sức bạc tại cửa hàng uy tín, có chính sách đổi trả nếu bị dị ứng là lựa chọn an toàn nhất!', 'tin tuc 4.webp', 'Admin', 1, '2025-12-07 17:29:51', '2025-12-07 18:47:36'),
(5, 'Ý Nghĩa Phong Thủy Của Trang Sức Bạc Theo Mệnh', 'phong-thuy-trang-suc-bac', 'Trong văn hóa phương Đông, trang sức không chỉ là phụ kiện làm đẹp mà còn mang ý nghĩa phong thủy, ảnh hưởng đến vận khí của người đeo. Vậy trang sức bạc hợp với mệnh nào?\r\n\r\n**BẠC THUỘC HÀNH KIM**\r\n\r\nTheo ngũ hành, bạc thuộc hành **Kim** vì:\r\n- Màu trắng, ánh kim loại đặc trưng\r\n- Chất liệu kim loại quý\r\n- Tính chất cứng, bền\r\n\r\n**MỆNH HỢP ĐEO TRANG SỨC BẠC**\r\n\r\n**1. Mệnh Thổ (Đất) ⭐⭐⭐⭐⭐**\r\n\r\n*Thổ sinh Kim* → Bạc rất hợp với người mệnh Thổ\r\n- **Năm sinh:** 1968, 1969, 1976, 1977, 1984, 1985, 1992, 1993, 2000, 2001, 2008, 2009, 2016, 2017, 2024, 2025\r\n- **Lợi ích:** Tăng cường vận khí, thu hút tài lộc, sức khỏe dồi dào\r\n- **Nên đeo:** Nhẫn, lắc tay bạc có đá màu vàng/nâu (Thạch anh vàng, Mắt hổ)\r\n\r\n**2. Mệnh Kim (Kim) ⭐⭐⭐⭐⭐**\r\n\r\n*Kim - Kim tương trợ* → Bạc hỗ trợ lẫn nhau\r\n- **Năm sinh:** 1970, 1971, 1978, 1979, 1986, 1987, 1994, 1995, 2002, 2003, 2010, 2011, 2018, 2019\r\n- **Lợi ích:** Tăng sự quyết đoán, mạnh mẽ, thành công trong sự nghiệp\r\n- **Nên đeo:** Bạc trắng nguyên chất, không đá hoặc đá trắng (Bạc, Kim cương)\r\n\r\n**3. Mệnh Thủy (Nước) ⭐⭐⭐⭐**\r\n\r\n*Kim sinh Thủy* → Bạc nuôi dưỡng mệnh Thủy\r\n- **Năm sinh:** 1972, 1973, 1980, 1981, 1988, 1989, 1996, 1997, 2004, 2005, 2012, 2013, 2020, 2021\r\n- **Lợi ích:** Tăng trí tuệ, sự linh hoạt, may mắn trong công việc\r\n- **Nên đeo:** Bạc kết hợp đá màu xanh (Aquamarine, Topaz xanh)\r\n\r\n**MỆNH KHÔNG NÊN ĐEO BẠCTHƯỜNG XUYÊN**\r\n\r\n**1. Mệnh Hỏa (Lửa) ⭐⭐**\r\n\r\n*Hỏa khắc Kim* → Bạc bị Hỏa kìm hãm\r\n- **Năm sinh:** 1966, 1967, 1974, 1975, 1982, 1983, 1990, 1991, 1998, 1999, 2006, 2007, 2014, 2015, 2022, 2023\r\n- **Ảnh hưởng:** Có thể làm giảm vận khí, gặp khó khăn trong công việc\r\n- **Giải pháp:** \r\n  - Đeo bạc kết hợp đá màu xanh lá (Ngọc bích, Malachite) để Mộc hóa giải\r\n  - Hạn chế đeo, chỉ đeo khi cần thiết\r\n  - Chọn trang sức vàng thay vì bạc\r\n\r\n**2. Mệnh Mộc (Gỗ) ⭐⭐**\r\n\r\n*Kim khắc Mộc* → Bạc kìm hãm mệnh Mộc\r\n- **Năm sinh:** 1964, 1965, 1972, 1973, 1980, 1981, 1988, 1989, 1996, 1997, 2004, 2005, 2012, 2013, 2020, 2021\r\n- **Ảnh hưởng:** Có thể gây bất lợi cho sức khỏe, vận khí\r\n- **Giải pháp:**\r\n  - Đeo bạc kết hợp đá màu đỏ/cam (Thạch anh hồng, Carnelian) để Hỏa hóa giải\r\n  - Ưu tiên trang sức gỗ, dây da, vải\r\n\r\n**TRANG SỨC BẠC THEO TUỔI**\r\n\r\nNgoài mệnh, còn xem xét tuổi (can chi năm sinh):\r\n\r\n**Tuổi Tý, Sửu, Dần, Mão:** Nên đeo nhẫn, lắc tay\r\n**Tuổi Thìn, Tỵ, Ngọ, Mùi:** Nên đeo dây chuyền, mặt dây\r\n**Tuổi Thân, Dậu, Tuất, Hợi:** Nên đeo bông tai, lắc chân\r\n\r\n**ĐEO TRANG SỨC BẠC Ở VỊ TRÍ NÀO?**\r\n\r\n**Tay trái (nhận năng lượng):**\r\n- Tăng trực giác, sự nhạy bén\r\n- Thu hút may mắn, tài lộc\r\n- Phù hợp: Lắc tay, nhẫn bạc có đá quý\r\n\r\n**Tay phải (phát năng lượng):**\r\n- Tăng sự tự tin, quyết đoán\r\n- Xua đuổi tà khí, tiêu cực\r\n- Phù hợp: Nhẫn bạc đơn giản\r\n\r\n**Cổ (vùng trung tâm năng lượng):**\r\n- Cân bằng cảm xúc\r\n- Bảo vệ sức khỏe\r\n- Phù hợp: Dây chuyền bạc có mặt theo mệnh\r\n\r\n**LƯU Ý QUAN TRỌNG**\r\n\r\n1. Phong thủy chỉ là **tham khảo**, không phải quy tắc cứng nhắc\r\n2. Yếu tố **tâm linh, niềm tin** quan trọng hơn\r\n3. Nếu thích và cảm thấy thoải mái → Đeo\r\n4. Nếu cảm thấy không hợp, khó chịu → Không đeo\r\n5. Kết hợp với yếu tố thẩm mỹ, phong cách cá nhân\r\n\r\n**LỜI KHUYÊN TỪ CHUYÊN GIA PHONG THỦY**\r\n\r\nThầy Phạm Thiên Hùng (Chuyên gia phong thủy) chia sẻ:\r\n\r\n*\"Trang sức bạc có năng lượng tốt, phù hợp hầu hết mọi người. Ngay cả mệnh Hỏa, Mộc vẫn có thể đeo nếu kết hợp đúng màu đá, vị trí. Quan trọng là cảm nhận của bản thân - nếu đeo mà thuận lợi, thoải mái là được.\"*\r\n\r\n**KẾT LUẬN**\r\n\r\n✅ **Nên đeo bạc:** Mệnh Kim, Thổ, Thủy\r\n⚠️ **Cân nhắc:** Mệnh Hỏa, Mộc (đeo kết hợp đá hóa giải)\r\n💎 **Quan trọng nhất:** Cảm nhận cá nhân + Thẩm mỹ + Sở thích\r\n\r\nTrang sức bạc không chỉ đẹp mà còn mang ý nghĩa sâu sắc. Hãy chọn món trang sức phù hợp với bản thân để vừa đẹp vừa tăng vận may!', 'tin tuc 5.webp\r\n', 'Admin', 1, '2025-12-07 17:29:51', '2025-12-07 18:48:00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_code` varchar(50) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `note` text DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'cod',
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `order_status` enum('pending','confirmed','shipping','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `transfer_proof` varchar(255) DEFAULT NULL,
  `transfer_amount` decimal(10,2) DEFAULT NULL,
  `transfer_date` datetime DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `customer_confirmed_at` datetime DEFAULT NULL,
  `confirmed_by` int(11) DEFAULT NULL,
  `confirmed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_code`, `fullname`, `email`, `phone`, `address`, `note`, `total_amount`, `payment_method`, `payment_status`, `order_status`, `created_at`, `updated_at`, `transfer_proof`, `transfer_amount`, `transfer_date`, `bank_name`, `transaction_id`, `customer_confirmed_at`, `confirmed_by`, `confirmed_at`) VALUES
(1, 2, 'ORD20251207185506461', 'ngoctan', 'Tan@gmail.com', '02343242343', 'Trà vinhs', '', 3680000.00, 'bank_transfer', 'pending', 'pending', '2025-12-07 17:55:06', '2025-12-07 17:55:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, 'ORD20251207190514823', 'ngoctan', 'ngoctanttdv2004@gmail.com', '02343242343', 'Trà vinhs', '', 390000.00, 'cod', 'pending', 'pending', '2025-12-07 18:05:14', '2025-12-07 18:05:14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 2, 'ORD20251207190855836', 'ngoctan', 'Tan@gmail.com', '0866996041', 'Trà Vinh', '', 750000.00, 'cod', 'pending', 'pending', '2025-12-07 18:08:55', '2025-12-07 18:08:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 1, 'ORD20251207195206457', 'Administrator', 'admin@trangsuc.com', '02343242343', 'Trà vinhs', '', 390000.00, 'cod', 'pending', 'confirmed', '2025-12-07 18:52:06', '2025-12-07 19:19:38', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 1, 'ORD20251207201917382', 'Administrator', 'admin@trangsuc.com', '0987654321', 'ádasdasdasd', 'd', 360000.00, 'cod', 'pending', 'confirmed', '2025-12-07 19:19:17', '2025-12-07 19:19:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 2, 'ORD20251208075707880', 'ngoctan', 'Tan@gmail.com', '0987654321', 'ádasdasdasd', 'jbjjb', 390000.00, 'cod', 'pending', 'shipping', '2025-12-08 06:57:07', '2025-12-08 07:01:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 2, 'ORD20251208075813318', 'ngoctan', 'Tan@gmail.com', '02343242343', 'Trà vinhs', '', 390000.00, 'cod', 'pending', 'completed', '2025-12-08 06:58:13', '2025-12-08 06:59:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 1, 'ORD20251209071453433', 'Phát', 'nguyenduyphat2019@gmail.com', '33743242', 'đường đồng khởi nối dài phường 6 thành phố trà vinh', '', 600000.00, 'bank_transfer', 'pending', 'pending', '2025-12-09 06:14:53', '2025-12-09 06:14:53', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 1, 'ORD20251209071910340', 'Tân', 'tan@gmail.com', '032423382', 'Đường võ nguyên giáp', '', 380000.00, 'bank_transfer', 'pending', 'completed', '2025-12-09 06:19:10', '2025-12-09 07:00:54', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 1, 'ORD20251209072440200', 'Administrator', 'admin@trangsuc.com', '92168217', 'ấp chợ xã tân sơn trà vinh vĩnh long', '', 450000.00, 'bank_transfer', 'pending', 'completed', '2025-12-09 06:24:40', '2025-12-09 07:00:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 1, 'ORD20251223080857757', 'Duy Phát', 'phat@gmail.com', '0835729714', 'd;ihdiaohsdoiasda, Cần Thơ', '', 790000.00, 'bank_transfer', 'pending', 'pending', '2025-12-23 07:08:57', '2025-12-23 07:08:57', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 1, 'ORD20251223081517856', 'Tú', 'Tu9@gmail.com', '092683503', 'sdasdasda, Hưng Yên', '', 390000.00, 'bank_transfer', 'pending', 'pending', '2025-12-23 07:15:17', '2025-12-23 07:15:17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 1, 'ORD20251223082919724', 'tân', 'tan@gmail.com', '0714275381', 'đâs, Hải Dương', '', 880000.00, 'bank_transfer', 'paid', 'completed', '2025-12-23 07:29:19', '2025-12-23 07:33:47', NULL, 0.00, NULL, NULL, NULL, '2025-12-23 14:33:24', NULL, NULL),
(14, 1, 'ORD20251223083442652', 'Mẫn', 'man@gmail.com', '0282472832', 'dsadasd, An Giang', 'sdasda', 375000.00, 'bank_transfer', 'paid', 'completed', '2025-12-23 07:34:42', '2025-12-23 07:47:23', 'uploads/transfer_proofs/tf_694a48ec7574c4.73954752.jpg', 100000.00, NULL, 'Mb bank', '23123123', '2025-12-23 14:46:52', NULL, NULL),
(15, 3, 'ORD20251223092653291', 'Duy Phát', 'nguyenduyphat2019@gmail.com', '092372323', 'sdada, Bình Dương', '', 780000.00, 'bank_transfer', 'paid', 'completed', '2025-12-23 08:26:53', '2025-12-23 08:27:57', 'uploads/transfer_proofs/tf_694a526316c1e6.43627242.jpg', 100000.00, NULL, 'Mb bank', '23123123', '2025-12-23 15:27:15', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `product_name`, `product_image`, `price`, `quantity`, `total`) VALUES
(1, 1, 4, 'Dây chuyền bạc cỏ 4 lá', 'day chuyen co 4 la.jpg', 360000.00, 1, 360000.00),
(2, 1, 2, 'Dây chuyền bạc mặt ngọc trai', 'sp-gmddddw002968-mat-day-chuyen-kim-cuong-vang-trang-14k-pnj-1.png', 750000.00, 4, 3000000.00),
(3, 1, 3, 'Dây chuyền bạc infinity', 'day chuyen bac infinity 1.jpg', 320000.00, 1, 320000.00),
(4, 2, 4, 'Dây chuyền bạc cỏ 4 lá', 'day chuyen co 4 la.jpg', 360000.00, 1, 360000.00),
(5, 3, 2, 'Dây chuyền bạc mặt ngọc trai', 'sp-gmddddw002968-mat-day-chuyen-kim-cuong-vang-trang-14k-pnj-1.png', 750000.00, 1, 750000.00),
(6, 4, 4, 'Dây chuyền bạc cỏ 4 lá', 'day chuyen co 4 la.jpg', 360000.00, 1, 360000.00),
(7, 5, 6, 'Dây chuyền bạc mặt sao', 'day chuyen bac mat sao.webp', 330000.00, 1, 330000.00),
(8, 6, 4, 'Dây chuyền bạc cỏ 4 lá', 'day chuyen co 4 la.jpg', 360000.00, 1, 360000.00),
(9, 7, 4, 'Dây chuyền bạc cỏ 4 lá', 'day chuyen co 4 la.jpg', 360000.00, 1, 360000.00),
(10, 8, 12, 'Lắc tay bạc đính đá pha lê', 'lac tay bac dinh pha le.jpg', 600000.00, 1, 600000.00),
(11, 9, 1, 'Dây chuyền bạc trái tim đính đá', 'Day chuyen bac trai tim dinh da.jpg', 350000.00, 1, 350000.00),
(12, 10, 7, 'Dây chuyền bạc mặt chữ C', 'day chuyen bac mat chu c.png', 420000.00, 1, 420000.00),
(13, 11, 2, 'Dây chuyền bạc mặt ngọc trai', 'sp-gmddddw002968-mat-day-chuyen-kim-cuong-vang-trang-14k-pnj-1.png', 750000.00, 1, 750000.00),
(14, 12, 4, 'Dây chuyền bạc cỏ 4 lá', 'day chuyen co 4 la.jpg', 360000.00, 1, 360000.00),
(15, 13, 9, 'Dây chuyền bạc đính ngọc Ruby', 'day chuyen bac dinh ngoc ruby.png', 850000.00, 1, 850000.00),
(16, 14, 6, 'Dây chuyền bạc mặt sao', 'day chuyen bac mat sao.webp', 330000.00, 1, 330000.00),
(17, 15, 2, 'Dây chuyền bạc mặt ngọc trai', 'sp-gmddddw002968-mat-day-chuyen-kim-cuong-vang-trang-14k-pnj-1.png', 750000.00, 1, 750000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `images` text DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `weight` decimal(8,2) DEFAULT NULL,
  `material` varchar(100) DEFAULT NULL,
  `is_new` tinyint(1) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `sale_price`, `image`, `images`, `quantity`, `weight`, `material`, `is_new`, `is_featured`, `status`, `views`, `created_at`, `updated_at`) VALUES
(1, 1, 'Dây chuyền bạc trái tim đính đá', 'day-chuyen-trai-tim', 'Dây chuyền bạc 925 thiết kế trái tim đính đá CZ lấp lánh, sang trọng và tinh tế', 450000.00, 350000.00, 'Day chuyen bac trai tim dinh da.jpg', NULL, 49, NULL, 'Bạc 925', 1, 1, 1, 2, '2025-12-07 17:29:51', '2025-12-09 06:19:10'),
(2, 1, 'Dây chuyền bạc mặt ngọc trai', 'day-chuyen-ngoc-trai', 'Dây chuyền bạc Italia mặt ngọc trai thiên nhiên, quý phái', 850000.00, 750000.00, 'sp-gmddddw002968-mat-day-chuyen-kim-cuong-vang-trang-14k-pnj-1.png', NULL, 23, NULL, 'Bạc Italia', 1, 1, 1, 4, '2025-12-07 17:29:51', '2025-12-23 08:26:53'),
(3, 1, 'Dây chuyền bạc infinity', 'day-chuyen-infinity', 'Dây chuyền bạc biểu tượng vô cực, ý nghĩa về tình yêu bất tận', 380000.00, 320000.00, 'day chuyen bac infinity 1.jpg', NULL, 59, NULL, 'Bạc 925', 0, 1, 1, 1, '2025-12-07 17:29:51', '2025-12-08 06:51:42'),
(4, 1, 'Dây chuyền bạc cỏ 4 lá', 'day-chuyen-co-4-la', 'Dây chuyền bạc may mắn cỏ 4 lá đính đá xanh', 420000.00, 360000.00, 'day chuyen co 4 la.jpg', NULL, 39, NULL, 'Bạc 925', 1, 0, 1, 9, '2025-12-07 17:29:51', '2025-12-23 07:15:17'),
(5, 1, 'Dây chuyền bạc mặt tròn đơn giản', 'day-chuyen-tron', 'Dây chuyền bạc thiết kế tối giản, phong cách Hàn Quốc', 280000.00, 250000.00, 'Day chuyen bac mat tron don gian.jpg', NULL, 70, NULL, 'Bạc 925', 0, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(6, 1, 'Dây chuyền bạc mặt sao', 'day-chuyen-sao', 'Dây chuyền bạc ngôi sao lấp lánh đính đá pha lê', 390000.00, 330000.00, 'day chuyen bac mat sao.webp', NULL, 53, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-23 07:34:42'),
(7, 1, 'Dây chuyền bạc mặt chữ C', 'day-chuyen-chu-c', 'Dây chuyền bạc mặt chữ cái C đính đá sang trọng', 480000.00, 420000.00, 'day chuyen bac mat chu c.png', NULL, 39, NULL, 'Bạc 925', 0, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-09 06:24:40'),
(8, 1, 'Dây chuyền bạc mặt trăng', 'day-chuyen-mat-trang', 'Dây chuyền bạc mặt trăng khuyết thời trang', 350000.00, 300000.00, 'day chuyen bac mat trang.jpeg', NULL, 50, NULL, 'Bạc 925', 1, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(9, 1, 'Dây chuyền bạc đính ngọc Ruby', 'day-chuyen-ruby', 'Dây chuyền bạc cao cấp đính đá Ruby đỏ quyến rũ', 920000.00, 850000.00, 'day chuyen bac dinh ngoc ruby.png', NULL, 24, NULL, 'Bạc Italia', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-23 07:29:19'),
(10, 1, 'Dây chuyền bạc mặt bướm', 'day-chuyen-buom', 'Dây chuyền bạc hình bướm xinh xắn cho nữ', 340000.00, 290000.00, 'day chuyen mat buom.jpeg', NULL, 65, NULL, 'Bạc 925', 0, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(11, 2, 'Lắc tay bạc xoắn ốc', 'lac-tay-xoan-oc', 'Lắc tay bạc thiết kế xoắn ốc độc đáo, bắt mắt', 520000.00, 450000.00, 'lac tay bac xoan oc.jpg', NULL, 45, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(12, 2, 'Lắc tay bạc đính đá pha lê', 'lac-tay-pha-le', 'Lắc tay bạc nữ đính đá pha lê lấp lánh cao cấp', 680000.00, 600000.00, 'lac tay bac dinh pha le.jpg', NULL, 34, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-09 06:14:53'),
(13, 2, 'Lắc tay bạc charm trái tim', 'lac-tay-charm', 'Lắc tay bạc có charm trái tim dễ thương', 420000.00, 380000.00, 'lac tay bac charm trai tim.jpg', NULL, 50, NULL, 'Bạc 925', 0, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(14, 2, 'Lắc tay bạc chuỗi bi tròn', 'lac-tay-bi-tron', 'Lắc tay bạc chuỗi bi tròn đơn giản, thanh lịch', 350000.00, 310000.00, 'lac tay bac chuoi bi tron.jpg', NULL, 60, NULL, 'Bạc 925', 0, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(15, 2, 'Lắc tay bạc đính ngọc trai', 'lac-tay-ngoc-trai', 'Lắc tay bạc kết hợp ngọc trai tự nhiên sang trọng', 780000.00, 720000.00, 'lac tay bac dinh ngoc trai.jpg', NULL, 28, NULL, 'Bạc Italia', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(16, 2, 'Lắc tay bạc hình lá', 'lac-tay-hinh-la', 'Lắc tay bạc thiết kế hình lá olive tinh tế', 460000.00, 410000.00, 'lac tay bac hinh la.jpg', NULL, 42, NULL, 'Bạc 925', 1, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(17, 2, 'Lắc tay bạc đơn giản', 'lac-tay-don-gian', 'Lắc tay bạc mảnh kiểu dáng tối giản hiện đại', 290000.00, 260000.00, 'lac tay bac don giann.jpg', NULL, 70, NULL, 'Bạc 925', 0, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(18, 2, 'Lắc tay bạc đính đá Zirconia', 'lac-tay-zirconia', 'Lắc tay bạc cao cấp đính đá Zirconia như kim cương', 850000.00, 780000.00, 'lac tay bac dinh da zicronia.jpg', NULL, 30, NULL, 'Bạc Italia', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(19, 2, 'Lắc tay bạc họa tiết hoa', 'lac-tay-hoa', 'Lắc tay bạc khắc họa tiết hoa văn cổ điển', 540000.00, 490000.00, 'OIP (9).jpg', NULL, 38, NULL, 'Bạc 925', 0, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(20, 2, 'Lắc tay bạc infinity', 'lac-tay-infinity', 'Lắc tay bạc biểu tượng vô cực, món quà ý nghĩa', 480000.00, 430000.00, 'OIP (10).jpg', NULL, 48, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(21, 3, 'Nhẫn bạc đính đá chủ lớn', 'nhan-da-lon', 'Nhẫn bạc nữ đính đá CZ lớn kiêu sa, sang trọng', 580000.00, 520000.00, 'nhan bac dinh da chu lon.jpg', NULL, 40, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(22, 3, 'Nhẫn bạc vương miện', 'nhan-vuong-mien', 'Nhẫn bạc thiết kế vương miện công chúa đính đá', 650000.00, 590000.00, 'nhan vuong vien.jpg', NULL, 35, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(23, 3, 'Nhẫn bạc đơn giản mảnh', 'nhan-don-gian', 'Nhẫn bạc nữ mảnh kiểu dáng tối giản Hàn Quốc', 220000.00, 190000.00, 'nhan bac don gian manh.jpg', NULL, 80, NULL, 'Bạc 925', 0, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(24, 3, 'Nhẫn bạc hoa tuyết', 'nhan-hoa-tuyet', 'Nhẫn bạc hình hoa tuyết đính đá lấp lánh', 380000.00, 340000.00, 'nhan bac hoa tuyet.jpg', NULL, 55, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(25, 3, 'Nhẫn bạc mặt oval đá xanh', 'nhan-oval-xanh', 'Nhẫn bạc nữ mặt oval đá xanh sang trọng', 720000.00, 660000.00, 'nhan bac mat oval da xanh.jpg', NULL, 30, NULL, 'Bạc Italia', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(26, 3, 'Nhẫn bạc xoắn', 'nhan-xoan', 'Nhẫn bạc thiết kế xoắn độc đáo, thời trang', 320000.00, 280000.00, 'nhan bac xoan.jpg', NULL, 60, NULL, 'Bạc 925', 0, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(27, 3, 'Nhẫn bạc mặt trái tim', 'nhan-trai-tim', 'Nhẫn bạc hình trái tim đính đá hồng dễ thương', 450000.00, 400000.00, 'nhan bac trai tim.jpg', NULL, 45, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(28, 3, 'Nhẫn bạc ba vòng', 'nhan-ba-vong', 'Nhẫn bạc ba vòng đính đá pha lê hiện đại', 520000.00, 470000.00, 'nhan bac ba vong.jpg', NULL, 38, NULL, 'Bạc 925', 0, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(29, 3, 'Nhẫn bạc đính ngọc trai', 'nhan-ngoc-trai', 'Nhẫn bạc cao cấp đính ngọc trai trắng quý phái', 880000.00, 820000.00, 'nhan bac dinh ngoc trai.jpg', NULL, 25, NULL, 'Bạc Italia', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(30, 3, 'Nhẫn bạc cỏ 4 lá', 'nhan-co-4-la', 'Nhẫn bạc may mắn cỏ 4 lá đính đá xanh', 410000.00, 370000.00, 'nhan bac co 4 la.jpg', NULL, 50, NULL, 'Bạc 925', 1, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(31, 4, 'Bông tai bạc giọt nước', 'bong-tai-giot-nuoc', 'Bông tai bạc hình giọt nước đính đá pha lê sang trọng', 480000.00, 430000.00, 'bong tai bac giot nuoc.jpg', NULL, 42, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(32, 4, 'Bông tai bạc tròn đính đá', 'bong-tai-tron', 'Bông tai bạc tròn cổ điển đính đá CZ lấp lánh', 420000.00, 380000.00, 'bong tai bac tron dinh da.jpg', NULL, 50, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(33, 4, 'Bông tai bạc dài thả', 'bong-tai-dai', 'Bông tai bạc dài thả chuỗi đá pha lê quyến rũ', 560000.00, 510000.00, 'bong tai bac dai tha.jpg', NULL, 38, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(34, 4, 'Bông tai bạc hình bướm', 'bong-tai-buom', 'Bông tai bạc hình bướm xinh xắn cho nữ', 350000.00, 310000.00, 'bong tai bac hinh buom.jpg', NULL, 55, NULL, 'Bạc 925', 0, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(35, 4, 'Bông tai bạc ngọc trai', 'bong-tai-ngoc-trai', 'Bông tai bạc đính ngọc trai tự nhiên cao cấp', 720000.00, 670000.00, 'bong tai bac ngoc trai.jpg', NULL, 28, NULL, 'Bạc Italia', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(36, 4, 'Bông tai bạc vòng tròn to', 'bong-tai-vong', 'Bông tai bạc vòng tròn to cá tính, thời trang', 380000.00, 340000.00, 'bong tai bac vong tron to.jpg', NULL, 60, NULL, 'Bạc 925', 0, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(37, 4, 'Bông tai bạc hoa tuyết', 'bong-tai-hoa-tuyet', 'Bông tai bạc hình bông hoa tuyết đính đá', 440000.00, 400000.00, 'bong tai bac hoa tuyet.jpg', NULL, 45, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(38, 4, 'Bông tai bạc đơn giản nhỏ', 'bong-tai-nho', 'Bông tai bạc nhỏ đơn giản, thanh lịch', 250000.00, 220000.00, 'bong tai bac don gian nho.jpg', NULL, 70, NULL, 'Bạc 925', 0, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(39, 4, 'Bông tai bạc đính Ruby', 'bong-tai-ruby', 'Bông tai bạc cao cấp đính đá Ruby đỏ quyến rũ', 850000.00, 790000.00, 'bong tai dinh ruby.jpg', NULL, 22, NULL, 'Bạc Italia', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(40, 4, 'Bông tai bạc trái tim', 'bong-tai-trai-tim', 'Bông tai bạc hình trái tim đính đá dễ thương', 390000.00, 350000.00, 'bong tai bac trai tim.jpg', NULL, 48, NULL, 'Bạc 925', 1, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(41, 5, 'Nhẫn đôi khắc tên', 'nhan-doi-khac-ten', 'Nhẫn đôi bạc khắc tên theo yêu cầu, ý nghĩa', 580000.00, 520000.00, 'nhan doi khac ten.jpg', NULL, 40, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(42, 5, 'Nhẫn đôi infinity', 'nhan-doi-infinity', 'Nhẫn đôi bạc biểu tượng vô cực tình yêu', 640000.00, 590000.00, 'nhan doi intifily.jpg', NULL, 35, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(43, 5, 'Nhẫn đôi đính đá đơn giản', 'nhan-doi-don-gian', 'Nhẫn đôi bạc đính đá nhỏ kiểu dáng đơn giản', 520000.00, 470000.00, 'nhan doi dinh da don gian.jpg', NULL, 45, NULL, 'Bạc 925', 0, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(44, 5, 'Nhẫn đôi trái tim kép', 'nhan-doi-trai-tim', 'Nhẫn đôi bạc hai trái tim khắc tên lãng mạn', 680000.00, 630000.00, 'nhan doi trai tim kep.jpg', NULL, 38, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(45, 5, 'Nhẫn đôi mặt phẳng', 'nhan-doi-mat-phang', 'Nhẫn đôi bạc mặt phẳng khắc chữ cổ điển', 550000.00, 500000.00, 'nhan doi mat phang.jpg', NULL, 42, NULL, 'Bạc 925', 0, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(46, 5, 'Nhẫn đôi vương miện', 'nhan-doi-vuong-mien', 'Nhẫn đôi bạc vương miện và vòng đơn giản', 720000.00, 670000.00, 'nhan doi vuong mien.jpg', NULL, 30, NULL, 'Bạc Italia', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(47, 5, 'Nhẫn đôi xoắn đôi', 'nhan-doi-xoan', 'Nhẫn đôi bạc thiết kế xoắn đôi độc đáo', 590000.00, 540000.00, 'nhan doi xoan duoi.jpg', NULL, 36, NULL, 'Bạc 925', 1, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(48, 5, 'Nhẫn đôi đính đá CZ', 'nhan-doi-cz', 'Nhẫn đôi bạc đính đá CZ lấp lánh cao cấp', 780000.00, 720000.00, 'nhan doi dinh ca cz.jpg', NULL, 28, NULL, 'Bạc Italia', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(49, 5, 'Nhẫn đôi họa tiết hoa', 'nhan-doi-hoa', 'Nhẫn đôi bạc khắc họa tiết hoa văn tinh tế', 620000.00, 570000.00, 'nhan doi hoa tiet hoa.jpg', NULL, 32, NULL, 'Bạc 925', 0, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(50, 5, 'Nhẫn đôi giản đơn', 'nhan-doi-gian-don', 'Nhẫn đôi bạc kiểu dáng giản đơn thanh lịch', 480000.00, 440000.00, 'nhan doi don gian.jpg', NULL, 50, NULL, 'Bạc 925', 0, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(51, 6, 'Lắc chân bạc chuông nhỏ', 'lac-chan-chuong', 'Lắc chân bạc có chuông nhỏ xinh xắn, dễ thương', 320000.00, 280000.00, 'lac chan bac chuong nho.jpg', NULL, 45, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(52, 6, 'Lắc chân bạc hai tầng', 'lac-chan-hai-tang', 'Lắc chân bạc hai tầng thiết kế độc đáo, cá tính', 420000.00, 380000.00, 'lac chan bac 2 tang.jpg', NULL, 38, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(53, 6, 'Lắc chân bạc đơn giản', 'lac-chan-don-gian', 'Lắc chân bạc mảnh đơn giản, thanh lịch', 240000.00, 210000.00, 'lac chan bac don gian.jpg', NULL, 60, NULL, 'Bạc 925', 0, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(54, 6, 'Lắc chân bạc trái tim', 'lac-chan-trai-tim', 'Lắc chân bạc charm trái tim đính đá dễ thương', 350000.00, 310000.00, 'lac chan bac trai tim.jpg', NULL, 50, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(55, 6, 'Lắc chân bạc đính đá pha lê', 'lac-chan-pha-le', 'Lắc chân bạc đính đá pha lê lấp lánh quyến rũ', 480000.00, 440000.00, 'lac chan bac dinh da pha le.jpg', NULL, 35, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(56, 6, 'Lắc chân bạc ngôi sao', 'lac-chan-sao', 'Lắc chân bạc charm ngôi sao xinh xắn', 290000.00, 260000.00, 'lac chan bac ngoi sao.jpg', NULL, 55, NULL, 'Bạc 925', 0, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(57, 6, 'Lắc chân bạc chuỗi bi', 'lac-chan-chuoi-bi', 'Lắc chân bạc chuỗi bi tròn đơn giản', 280000.00, 250000.00, 'lac chan bac chuoi bi.jpg', NULL, 58, NULL, 'Bạc 925', 0, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(58, 6, 'Lắc chân bạc hình lá', 'lac-chan-la', 'Lắc chân bạc charm hình lá olive tinh tế', 340000.00, 300000.00, 'lac chan bac hinh la.jpg', NULL, 42, NULL, 'Bạc 925', 1, 0, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(59, 6, 'Lắc chân bạc infinity', 'lac-chan-infinity', 'Lắc chân bạc biểu tượng vô cực ý nghĩa', 360000.00, 320000.00, 'lac chan bac infinily.jpg', NULL, 48, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(60, 6, 'Lắc chân bạc ba tầng', 'lac-chan-ba-tang', 'Lắc chân bạc ba tầng cá tính, thời trang', 520000.00, 480000.00, 'lac chan bac ba tang.jpg', NULL, 30, NULL, 'Bạc 925', 1, 1, 1, 0, '2025-12-07 17:29:51', '2025-12-07 17:29:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `phone`, `password`, `address`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@trangsuc.com', NULL, '0192023a7bbd73250516f069df18b500', NULL, 'admin', 1, '2025-12-07 17:29:51', '2025-12-07 17:29:51'),
(2, 'ngoctan', 'Tan@gmail.com', '0866996041', '$2y$10$OaTepv/hNFm7hVMAt7pIbOcsbLJZPe2NpyCHiojOybob1OuxqIEza', NULL, 'user', 1, '2025-12-07 17:32:06', '2025-12-07 17:32:06'),
(3, 'Duy Phát', 'nguyenduyphat2019@gmail.com', '92354721', '25f9e794323b453885f5181f1b624d0b', 'đường đồng khởi nối dài phường 6 thành phố trà vinh', 'user', 1, '2025-12-09 08:52:41', '2025-12-09 08:59:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedbacks_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
