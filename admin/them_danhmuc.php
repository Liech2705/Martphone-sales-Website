<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Thêm Danh Mục</title>
</head>

<body>
<?php
include '../include/connect.php';

// Khởi tạo mảng lỗi
$errors = [];

// Xử lý thêm danh mục
if (isset($_POST['btnthem'])) {
    $tendm = trim($_POST['tendm']);
    $dequi = (int)$_POST['dequi'];

    // Kiểm tra tên danh mục trống
    if (empty($tendm)) {
        $errors[] = "Bạn chưa nhập tên danh mục";
    }

    // Kiểm tra tên danh mục trùng
    if (empty($errors)) {
        $sql_check = "SELECT COUNT(*) as count FROM danhmuc WHERE tendm = ?";
        if ($stmt_check = $conn->prepare($sql_check)) {
            $stmt_check->bind_param("s", $tendm);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
            $row = $result_check->fetch_assoc();
            if ($row['count'] > 0) {
                $errors[] = "Tên danh mục '$tendm' đã tồn tại";
            }
            $stmt_check->close();
        } else {
            $errors[] = "Lỗi kiểm tra trùng tên: " . $conn->error;
        }
    }

    // Nếu không có lỗi, thêm danh mục
    if (empty($errors)) {
        $sql = "INSERT INTO danhmuc (tendm, dequi) VALUES (?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("si", $tendm, $dequi);
            if ($stmt->execute()) {
                echo "<p align='center'>Thêm danh mục <font color='red'><b>$tendm</b></font> thành công!</p>";
                echo '<meta http-equiv="refresh" content="1;url=admin.php?admin=hienthidm">';
            } else {
                $errors[] = "Thêm thất bại: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errors[] = "Lỗi chuẩn bị câu lệnh: " . $conn->error;
        }
    }
}

// Xử lý xóa danh mục
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];

    // Kiểm tra danh mục có sản phẩm
    $sql_check_products = "SELECT COUNT(*) as count FROM sanpham WHERE madm = ?";
    if ($stmt_check = $conn->prepare($sql_check_products)) {
        $stmt_check->bind_param("i", $delete_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        if ($result_check->fetch_assoc()['count'] > 0) {
            $errors[] = "Danh mục đang được sử dụng bởi sản phẩm, không thể xóa";
        }
        $stmt_check->close();
    }

    // Kiểm tra danh mục có danh mục con
    if (empty($errors)) {
        $sql_check_sub = "SELECT COUNT(*) as count FROM danhmuc WHERE dequi = ?";
        if ($stmt_check = $conn->prepare($sql_check_sub)) {
            $stmt_check->bind_param("i", $delete_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
            if ($result_check->fetch_assoc()['count'] > 0) {
                $errors[] = "Danh mục có danh mục con, không thể xóa";
            }
            $stmt_check->close();
        }
    }

    // Nếu không có lỗi, xóa danh mục
    if (empty($errors)) {
        $sql_delete = "DELETE FROM danhmuc WHERE madm = ?";
        if ($stmt_delete = $conn->prepare($sql_delete)) {
            $stmt_delete->bind_param("i", $delete_id);
            if ($stmt_delete->execute()) {
                echo "<p align='center'>Xóa danh mục thành công!</p>";
                echo '<meta http-equiv="refresh" content="1;url=admin.php?admin=hienthidm">';
            } else {
                $errors[] = "Xóa thất bại: " . $stmt_delete->error;
            }
            $stmt_delete->close();
        } else {
            $errors[] = "Lỗi chuẩn bị câu lệnh xóa: " . $conn->error;
        }
    }
}
?>
<link rel="stylesheet" href="/admin/css/form.css">
<style>
.error-message {
    color: red;
    margin-bottom: 15px;
    border: 1px solid red;
    padding: 10px;
    background-color: #ffeeee;
}
.error-list {
    list-style: disc;
    margin-left: 20px;
}
</style>
<div class="fix_form">
<?php if (!empty($errors)): ?>
    <div class="error-message">
        <strong>Có lỗi xảy ra:</strong>
        <ul class="error-list">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form action="" method="post" class="form" onsubmit="return kiemtra();">
    <table>
        <h2>Thêm Danh Mục</h2>
        <tr>
            <td>Mã danh mục</td>
            <td><input type="text" disabled="disabled" name="madm" size="5" /></td>
        </tr>
        <tr>
            <td>Tên danh mục</td>
            <td><input type="text" name="tendm" value="<?php echo isset($tendm) ? htmlspecialchars($tendm) : ''; ?>" /></td>
        </tr>
        <tr>
            <td>Thuộc</td>
            <td>
                <select name="dequi">
                    <option value="0">Danh mục chính</option>
                    <?php
                        $sql = "SELECT * FROM danhmuc WHERE dequi = 0";
                        if ($result = $conn->query($sql)) {
                            while ($show1 = $result->fetch_assoc()) {
                                $madm = $show1['madm'];
                                $tendm = $show1['tendm'];
                                $selected = (isset($dequi) && $dequi == $madm) ? 'selected' : '';
                                echo "<option value='$madm' $selected>$tendm</option>";
                                
                                $sql2 = "SELECT * FROM danhmuc WHERE dequi = ?";
                                if ($stmt2 = $conn->prepare($sql2)) {
                                    $stmt2->bind_param("i", $madm);
                                    $stmt2->execute();
                                    $result2 = $stmt2->get_result();
                                    while ($show3 = $result2->fetch_assoc()) {
                                        $madm1 = $show3['madm'];
                                        $tendm1 = $show3['tendm'];
                                        $selected = (isset($dequi) && $dequi == $madm1) ? 'selected' : '';
                                        echo "<option value='$madm1' $selected> - $tendm1</option>";
                                    }
                                    $stmt2->close();
                                }
                            }
                            $result->free();
                        }
                    ?>
                </select>
            </td>
        </tr>
    </table>
    <div class="funtion">
        <input type="submit" name="btnthem" value="Thêm" />
    </div>
</form>

<script>
function kiemtra() {
    var tendm = document.forms[0].tendm.value.trim();
    if (!tendm) {
        alert('Bạn chưa nhập tên danh mục');
        document.forms[0].tendm.focus();
        return false;
    }
    return true;
}
</script>
</body>
</html>