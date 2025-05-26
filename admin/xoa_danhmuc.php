<?php
include '../include/connect.php';

if (isset($_GET['madm'])) {
    $madm = mysqli_real_escape_string($conn, $_GET['madm']);

    $delete = "DELETE FROM danhmuc WHERE madm='$madm'";
    $del = mysqli_query($conn, $delete);

    if ($del) {
        // Sử dụng header thay cho redirect nếu không có hàm đó
        header("Location: admin.php?admin=hienthidm&msg=Xoa thanh cong");
        exit;
    } else {
        echo "Xóa danh mục thất bại: " . mysqli_error($conn);
    }
} else {
    echo "Thiếu mã danh mục";
}
?>
