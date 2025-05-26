<?php
    ob_start(); // Bật bộ đệm đầu ra
    include '../include/connect.php';

    $error = "";
    $idnd = filter_var($_GET['idnd'] ?? 0, FILTER_SANITIZE_NUMBER_INT);

    // Lấy thông tin người dùng
    $row = null;
    $sql = "SELECT * FROM nguoidung WHERE idnd = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $idnd);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
    } else {
        $error = "Lỗi truy vấn: " . $conn->error;
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-header h5 {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-success, .btn-danger {
            border: none;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
            border: 1px solid #dc3545;
            padding: 10px;
            background-color: #ffeeee;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container my-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="mb-0">SỬA NGƯỜI DÙNG</h5>
        </div>
        <div class="card-body">
            <?php if ($error) { ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php } ?>
            <?php if ($row) { ?>
                <form action="update_nguoidung.php?idnd=<?php echo htmlspecialchars($idnd); ?>" method="post" name="frm" onsubmit="return kiemtra()" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="tennd" class="form-label">Tên người dùng</label>
                        <input type="text" class="form-control" id="tennd" name="tennd" value="<?php echo htmlspecialchars($row['tennd']); ?>">
                    </div>
                    <div class="form-group">
                    <label for="user" class="form-label">Username</label>
                        <input type="text" class="form-control" id="user" name="user" value="<?php echo htmlspecialchars($row['username']); ?>" disabled>
                        <input type="hidden" name="original_user" value="<?php echo htmlspecialchars($row['username']); ?>">
                    
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="dienthoai" class="form-label">Điện thoại</label>
                        <input type="text" class="form-control" id="dienthoai" name="dienthoai" value="<?php echo htmlspecialchars($row['dienthoai']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="phanquyen" class="form-label">Quyền</label>
                        <select class="form-select" id="phanquyen" name="phanquyen">
                            <option value="0" <?php if ($row['phanquyen'] == 0) echo 'selected'; ?>>0</option>
                            <option value="1" <?php if ($row['phanquyen'] == 1) echo 'selected'; ?>>1</option>
                        </select>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" name="update" class="btn btn-success">Update</button>
                        <button type="reset" class="btn btn-danger">Hủy</button>
                    </div>
                </form>
            <?php } else { ?>
                <div class="error-message">Không tìm thấy người dùng</div>
            <?php } ?>
        </div>
    </div>
</div>

<script>
function kiemtra() {
    // Kiểm tra tên người dùng
    if (frm.tennd.value.trim() == "") {
        alert("Bạn chưa nhập tên. Vui lòng kiểm tra lại");
        frm.tennd.focus();
        return false;
    }
    if (frm.tennd.value.length < 6) {
        alert("Tên quá ngắn. Vui lòng điền đầy đủ tên");
        frm.tennd.focus();
        return false;
    }

    // Kiểm tra username
    if (frm.user.value.trim() == "") {
        alert("Bạn chưa nhập tên đăng nhập. Vui lòng kiểm tra lại");
        frm.user.focus();
        return false;
    }
    if (frm.user.value.length < 5) {
        alert("Tên đăng nhập phải lớn hơn 5 ký tự");
        frm.user.focus();
        return false;
    }
    if (frm.user.value != frm.original_user.value) {
        alert("Không được thay đổi username");
        frm.user.focus();
        return false;
    }

    // Kiểm tra email
    if (frm.email.value.trim() == "") {
        alert("Bạn chưa nhập email");
        frm.email.focus();
        return false;
    }
    var emailPattern = /^([A-Za-z0-9])+[@][a-z]+[.][a-z]+([.][a-z]+)?$/;
    if (!emailPattern.test(frm.email.value)) {
        alert("Nhập sai định dạng email. Vui lòng kiểm tra lại");
        frm.email.focus();
        return false;
    }

    // Kiểm tra số điện thoại
    var phonePattern = /^[0-9]+$/;
    if (!phonePattern.test(frm.dienthoai.value)) {
        alert("Số điện thoại không hợp lệ. Chỉ được nhập số");
        frm.dienthoai.focus();
        return false;
    }
    if (frm.dienthoai.value.length < 10 || frm.dienthoai.value.length > 11) {
        alert("Số điện thoại không hợp lệ. Độ dài phải từ 10-11 số");
        frm.dienthoai.focus();
        return false;
    }

    return true;
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php ob_end_flush(); ?>
</body>
</html>