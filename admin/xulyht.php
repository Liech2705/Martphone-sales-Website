<?php
if (isset($_POST['id'])) {
    foreach ($_POST['id'] as $idht) {
        $_SESSION['id'][$idht] = 1;
    }

    if (isset($_POST['giaohang'])) {
        foreach ($_SESSION['id'] as $idht => $value) {
            if ($value == 1) {
                // Sử dụng MySQLi để thực hiện câu lệnh UPDATE
                $sql = "UPDATE hotro SET trangthai = 2 WHERE idht = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $idht);
                $stmt->execute();
                $stmt->close();
            }
        }
        unset($_SESSION['id']);
        echo "
            <script language='javascript'>
                alert('Đã xử lý');
                window.open('admin.php?admin=hienthiht', '_self', 1);
            </script>
        ";
    } else if (isset($_POST['huy'])) { 
        foreach ($_SESSION['id'] as $idht => $value) {
            if ($value == 1) {
                // Sử dụng MySQLi để thực hiện câu lệnh UPDATE
                $sql = "UPDATE hotro SET trangthai = 3 WHERE idht = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $idht);
                $stmt->execute();
                $stmt->close();
            }
        }
        unset($_SESSION['id']);
        echo "
            <script language='javascript'>
                alert('Đã huỷ');
                window.open('admin.php?admin=hienthiht', '_self', 1);
            </script>
        ";
    } else {
        foreach ($_SESSION['id'] as $idht => $value) {
            if ($value == 1) {
                // Sử dụng MySQLi để thực hiện câu lệnh DELETE
                $sql = "DELETE FROM hotro WHERE idht = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $idht);
                $stmt->execute();
                $stmt->close();
            }
        }
        unset($_SESSION['id']);
        echo "
            <script language='javascript'>
                alert('Xóa thành công');
                window.open('admin.php?admin=hienthiht', '_self', 1);
            </script>
        ";
    }
} else {
    echo "
        <script language='javascript'>
            alert('Bạn chưa chọn hỗ trợ cần xử lý');
            window.open('admin.php?admin=hienthiht', '_self', 1);
        </script>
    ";
}
?>
