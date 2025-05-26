<?php
include '../include/connect.php';

// Khởi tạo biến rỗng và mảng chứa lỗi
$tieude = '';
$ndngan = '';
$noidung = '';
$hinhanh = '';
$tacgia = '';
$errors = [];

if (isset($_POST['submit'])) {
    // Lấy và làm sạch dữ liệu
    $tieude = trim($_POST['tieude'] ?? '');
    $ndngan = trim($_POST['ndngan'] ?? '');
    $noidung = trim($_POST['noidung'] ?? '');
    $tacgia = trim($_POST['tacgia'] ?? '');

    // Kiểm tra dữ liệu bắt buộc
    if (empty($tieude)) {
        $errors[] = "Tiêu đề không được để trống";
    } elseif (strlen($tieude) > 255) {
        $errors[] = "Tiêu đề quá dài (tối đa 255 ký tự)";
    }

    if (empty($ndngan)) {
        $errors[] = "Nội dung ngắn không được để trống";
    }

    if (empty($noidung)) {
        $errors[] = "Nội dung chi tiết không được để trống";
    }

    if (empty($tacgia)) {
        $errors[] = "Tác giả không được để trống";
    }

    // Xử lý file hình ảnh
    $upload_image = "../img/tintuc/";
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB

    if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file_tmp = $_FILES['hinhanh']['tmp_name'];
        $file_name = $_FILES['hinhanh']['name'];
        $file_type = $_FILES['hinhanh']['type'];
        $file_size = $_FILES['hinhanh']['size'];
        $file_error = $_FILES['hinhanh']['error'];

        // Kiểm tra lỗi upload
        if ($file_error !== UPLOAD_ERR_OK) {
            $errors[] = "Lỗi khi tải lên hình ảnh";
        }

        // Kiểm tra loại file
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Chỉ chấp nhận file JPEG, PNG hoặc GIF";
        }

        // Kiểm tra kích thước file
        if ($file_size > $max_size) {
            $errors[] = "Kích thước file quá lớn (tối đa 5MB)";
        }

        // Kiểm tra xem file có phải là hình ảnh thực sự
        if (!getimagesize($file_tmp)) {
            $errors[] = "File tải lên không phải là hình ảnh hợp lệ";
        }
    } else {
        $errors[] = "Vui lòng chọn hình ảnh";
    }

    // Nếu không có lỗi, tiến hành lưu dữ liệu
    if (empty($errors)) {
        try {
            // Tạo tên file duy nhất
            $dmyhis = date("YmdHis");
            $file__name__ = $dmyhis . '_' . basename($file_name);
            $ngay = date("Y-m-d H:i:s");

            // Di chuyển file
            if (!move_uploaded_file($file_tmp, $upload_image . $file__name__)) {
                throw new Exception("Không thể lưu file hình ảnh");
            }

            // Chuẩn bị câu lệnh INSERT
            $insert = "INSERT INTO tintuc (tieude, ndngan, noidung, hinhanh, ngaydangtin, tacgia, trangthai) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($insert);
            if (!$stmt) {
                throw new Exception("Lỗi chuẩn bị câu lệnh: " . $conn->error);
            }

            $trangthai = 1;
            $stmt->bind_param("ssssssi", $tieude, $ndngan, $noidung, $file__name__, $ngay, $tacgia, $trangthai);

            if (!$stmt->execute()) {
                throw new Exception("Lỗi thực thi câu lệnh: " . $stmt->error);
            }

            echo "Thêm tin tức thành công";
            echo '<meta http-equiv="refresh" content="2;url=admin.php?admin=hienthitt">';
            
            $stmt->close();
        } catch (Exception $e) {
            $errors[] = "Lỗi: " . $e->getMessage();
            // Xóa file đã upload nếu có lỗi
            if (isset($file__name__) && file_exists($upload_image . $file__name__)) {
                unlink($upload_image . $file__name__);
            }
        }
    }
}

// Đóng kết nối
$conn->close();
?>

<link rel="stylesheet" href="/admin/css/form.css">
<div class="fix_form">
  

    <form action="" method="post" name="frm" class="form" enctype="multipart/form-data">
        
        <table>
            <h2 class="text-center">Thêm Tin Tức</h2>
            <?php if (!empty($errors)): ?>
        <div class="error-messages" style="color: red; margin-bottom: 15px;">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
            <tr>
                <td>Tiêu đề</td>
                <td><input type="text" name="tieude" size="50" value="<?php echo htmlspecialchars($tieude); ?>"/></td>
            </tr>
            <tr>
                <td>Nội dung ngắn</td>
                <td><textarea name="ndngan" rows="4" cols="50"><?php echo htmlspecialchars($ndngan); ?></textarea></td>
            </tr>
            <tr>
                <td>Nội dung chi tiết</td>
                <td><textarea name="noidung" id="chitiet" rows="10" cols="50"><?php echo htmlspecialchars($noidung); ?></textarea></td>
            </tr>  
            <tr>
                <td>Hình ảnh</td>
                <td>
                    <input type="file" name="hinhanh" accept="image/jpeg,image/png,image/gif" />
                </td>
            </tr>
            <tr>
                <td>Tác giả</td>
                <td><input type="text" name="tacgia" value="<?php echo htmlspecialchars($tacgia); ?>"/></td>
            </tr>
            <tr>
                <td colspan="2" class="funtion">
                    <input type="submit" name="submit" value="Thêm" />
                </td>
            </tr>
        </table>
    </form>
</div>