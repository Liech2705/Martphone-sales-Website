<?php
if (isset($_POST['id'])) {
    foreach ($_POST['id'] as $mahd) {
        $_SESSION['id'][$mahd] = 1;
    }

    if (isset($_POST['giaohang'])) {
        foreach ($_SESSION['id'] as $mahd => $value) {
            if ($value == 1) {
                $sql = "UPDATE hoadon SET trangthai = 2 WHERE mahd = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 's', $mahd);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
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
    else if (isset($_POST['huy'])) {
        foreach ($_SESSION['id'] as $mahd => $value) {
            if ($value == 1) {
                $sql = "UPDATE hoadon SET trangthai = 3 WHERE mahd = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 's', $mahd);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
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
    else {
        foreach ($_SESSION['id'] as $mahd => $value) {
            if ($value == 1) {
                $sql = "DELETE FROM hoadon WHERE mahd = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 's', $mahd);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                $sql1 = "DELETE FROM chitiethoadon WHERE mahd = ?";
                $stmt1 = mysqli_prepare($conn, $sql1);
                mysqli_stmt_bind_param($stmt1, 's', $mahd);
                mysqli_stmt_execute($stmt1);
                mysqli_stmt_close($stmt1);
            }
        }
        unset($_SESSION['id']);
        echo "
            <script language='javascript'>
                alert('Xóa thành công');
                window.open('admin.php?admin=hienthihd','_self', 1);
            </script>
        ";
    }
} else {
    echo "
        <script language='javascript'>
            alert('Bạn chưa chọn hóa đơn cần xử lý');
            window.open('admin.php?admin=hienthihd','_self', 1);
        </script>
    ";
}
?>
