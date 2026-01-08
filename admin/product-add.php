<?php
require_once 'check_admin.php';

$page_title = 'Thêm Sản Phẩm';

$error = '';
$message = '';

// Get categories
$categories = mysqli_query($conn, "SELECT * FROM categories WHERE status = 1 ORDER BY name");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_id = (int)$_POST['category_id'];
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $slug = mysqli_real_escape_string($conn, strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['name']))));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $price = (float)$_POST['price'];
    $sale_price = !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : 'NULL';
    $quantity = (int)$_POST['quantity'];
    $material = mysqli_real_escape_string($conn, trim($_POST['material']));
    $weight = !empty($_POST['weight']) ? (float)$_POST['weight'] : 'NULL';
    $is_new = isset($_POST['is_new']) ? 1 : 0;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $status = isset($_POST['status']) ? 1 : 0;
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = uniqid() . '.' . $ext;
            $upload_path = '../assets/images/products/' . $new_filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image = $new_filename;
            }
        }
    }
    
    if (empty($name) || empty($image)) {
        $error = 'Vui lòng điền đầy đủ thông tin và chọn hình ảnh!';
    } else {
        $query = "INSERT INTO products (category_id, name, slug, description, price, sale_price, image, quantity, material, weight, is_new, is_featured, status) 
                 VALUES ($category_id, '$name', '$slug', '$description', $price, $sale_price, '$image', $quantity, '$material', $weight, $is_new, $is_featured, $status)";
        
        if (mysqli_query($conn, $query)) {
            header('Location: products.php?msg=added');
            exit;
        } else {
            $error = 'Có lỗi xảy ra!';
        }
    }
}

include 'includes/header.php';
?>

<?php if($error): ?>
<div class="alert alert-error"><?php echo $error; ?></div>
<?php endif; ?>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fas fa-plus"></i> Thêm Sản Phẩm Mới</h3>
        <a href="products.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay Lại
        </a>
    </div>
    <div class="admin-card-body">
        <form method="POST" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div>
                    <div class="admin-form-group">
                        <label>Tên sản phẩm <span style="color: red;">*</span></label>
                        <input type="text" name="name" class="admin-form-control" required>
                    </div>

                    <div class="admin-form-group">
                        <label>Danh mục <span style="color: red;">*</span></label>
                        <select name="category_id" class="admin-form-control" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="admin-form-group">
                        <label>Mô tả</label>
                        <textarea name="description" class="admin-form-control" rows="5"></textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="admin-form-group">
                            <label>Giá gốc <span style="color: red;">*</span></label>
                            <input type="number" name="price" class="admin-form-control" required min="0" step="1000">
                        </div>

                        <div class="admin-form-group">
                            <label>Giá khuyến mãi</label>
                            <input type="number" name="sale_price" class="admin-form-control" min="0" step="1000">
                        </div>
                    </div>
                </div>

                <div>
                    <div class="admin-form-group">
                        <label>Hình ảnh <span style="color: red;">*</span></label>
                        <input type="file" name="image" class="admin-form-control" required accept="image/*" 
                               onchange="previewImage(this, 'preview')">
                        <img id="preview" class="image-preview" style="margin-top: 15px; max-width: 100%;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="admin-form-group">
                            <label>Số lượng <span style="color: red;">*</span></label>
                            <input type="number" name="quantity" class="admin-form-control" required min="0" value="0">
                        </div>

                        <div class="admin-form-group">
                            <label>Trọng lượng (gram)</label>
                            <input type="number" name="weight" class="admin-form-control" min="0" step="0.1">
                        </div>
                    </div>

                    <div class="admin-form-group">
                        <label>Chất liệu</label>
                        <input type="text" name="material" class="admin-form-control" placeholder="VD: Bạc 925">
                    </div>

                    <div class="admin-form-group">
                        <label style="display: flex; align-items: center; cursor: pointer; margin-bottom: 10px;">
                            <input type="checkbox" name="is_new" style="margin-right: 8px;">
                            <span>Sản phẩm mới</span>
                        </label>

                        <label style="display: flex; align-items: center; cursor: pointer; margin-bottom: 10px;">
                            <input type="checkbox" name="is_featured" style="margin-right: 8px;">
                            <span>Sản phẩm nổi bật</span>
                        </label>

                        <label style="display: flex; align-items: center; cursor: pointer;">
                            <input type="checkbox" name="status" checked style="margin-right: 8px;">
                            <span>Hiển thị</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 16px;">
                        <i class="fas fa-save"></i> Lưu Sản Phẩm
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
