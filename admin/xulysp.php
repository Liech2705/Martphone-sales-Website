<?php
include '../include/connect.php';

if (isset($_POST['xoa']) && isset($_POST['id']) && is_array($_POST['id'])) {
    $ids = $_POST['id'];

    // Chuẩn bị câu lệnh DELETE với placeholder
    $sql = "DELETE FROM sanpham WHERE idsp = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        foreach ($ids as $id) {
            // Phòng ngừa SQL Injection
            $id = (int)$id;
            mysqli_stmt_bind_param($stmt, 'i', $id);
            
            if (!mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Lỗi khi xóa sản phẩm ID $id: " . mysqli_error($conn) . "');</script>";
            }
        }
        mysqli_stmt_close($stmt);

        // Xây dựng URL redirect dựa trên các tham số GET
        $redirect_url = 'admin.php?admin=hienthisp';
        if (!empty($_GET['search'])) {
            $redirect_url .= '&search=' . urlencode($_GET['search']);
        }
        if (!empty($_GET['page'])) {
            $redirect_url .= '&page=' . (int)$_GET['page'];
        }

        echo "<script>alert('Xóa sản phẩm thành công'); window.location='$redirect_url';</script>";
    } else {
        echo "<script>alert('Không thể chuẩn bị truy vấn');</script>";
    }
} else {
    // Xử lý lỗi: quay lại trang trước đó với các tham số
    $redirect_url = 'admin.php?admin=hienthisp';
    if (!empty($_GET['search'])) {
        $redirect_url .= '&search=' . urlencode($_GET['search']);
    }
    if (!empty($_GET['page'])) {
        $redirect_url .= '&page=' . (int)$_GET['page'];
    }

    echo "<script>alert('Vui lòng chọn ít nhất một sản phẩm để xóa'); window.location='$redirect_url';</script>";
}

mysqli_close($conn);
?>