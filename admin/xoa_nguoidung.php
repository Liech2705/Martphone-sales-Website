<?php
include '../include/connect.php';

if (isset($_GET['idnd'])) {
    $idnd = intval($_GET['idnd']); // Ép kiểu số nguyên để bảo mật

    // Xóa người dùng
    $delete = "DELETE FROM nguoidung WHERE idnd = $idnd";
    $del = mysqli_query($conn, $delete);

    if ($del) {
        // Xóa thành công, chuyển hướng
        header("Location: admin.php?admin=hienthind&msg=success");
        exit;
    } else {
        echo "Xóa người dùng thất bại: " . mysqli_error($conn);
    }
} else {
    echo "Không có ID người dùng được cung cấp.";
}
?>
