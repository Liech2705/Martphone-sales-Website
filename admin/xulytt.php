<?php
include('../include/connect.php'); // Kết nối cơ sở dữ liệu sử dụng mysqli

if (isset($_POST['id'])) {
    // Duyệt qua các id được chọn
    foreach ($_POST['id'] as $matt) {
        $_SESSION['id'][$matt] = 1;
    }

    // Kiểm tra nếu đã chọn giao hàng
    if (isset($_POST['giaohang'])) {
        foreach ($_SESSION['id'] as $matt => $value) {
            if ($value == 1) {
                // Cập nhật trạng thái giao hàng trong cơ sở dữ liệu
                $sql = "UPDATE hoadon SET trangthai = 2 WHERE matt = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("i", $matt);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }
        unset($_SESSION['id']);
        echo "
            <script language='javascript'>
                alert('Đã giao hàng');
                window.open('admin.php?admin=hienthihd','_self', 1);
            </script>
        ";
    } 
    // Kiểm tra nếu đã chọn hủy đơn hàng
    else if (isset($_POST['huy'])) { 
        foreach ($_SESSION['id'] as $matt => $value) {
            if ($value == 1) {
                // Cập nhật trạng thái hủy đơn hàng trong cơ sở dữ liệu
                $sql = "UPDATE hoadon SET trangthai = 3 WHERE matt = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("i", $matt);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }
        unset($_SESSION['id']);
        echo "
            <script language='javascript'>
                alert('Đã huỷ đơn hàng');
                window.open('admin.php?admin=hienthihd','_self', 1);
            </script>
        ";
    } 
    // Nếu không chọn giao hàng hay hủy, xóa tin tức
    else {
        foreach ($_SESSION['id'] as $matt => $value) {
            if ($value == 1) {
                // Xóa tin tức
                $sql = "DELETE FROM tintuc WHERE matt = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("i", $matt);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }
        unset($_SESSION['id']);
        echo "
            <script language='javascript'>
                alert('Xóa tin tức đã chọn thành công');
                window.open('admin.php?admin=hienthitt','_self', 1);
            </script>
        ";
    }
} else {
    echo "
        <script language='javascript'>
            alert('Bạn chưa chọn dòng cần xóa');
            window.open('admin.php?admin=hienthitt','_self', 1);
        </script>
    ";
}
?>
