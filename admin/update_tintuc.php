<?php
include('../include/connect.php');
include('function/function.php');

// Lấy dữ liệu từ form
$tieude = $_POST['tieude'];
$ndngan = $_POST['ndngan'];
$noidung = $_POST['noidung'];
$tacgia = $_POST['tacgia'];

// Xử lý file hình ảnh
$upload_image = "../img/tintuc/";
$file_tmp = isset($_FILES['hinhanh']['tmp_name']) ? $_FILES['hinhanh']['tmp_name'] : "";
$file_name = isset($_FILES['hinhanh']['name']) ? $_FILES['hinhanh']['name'] : "";
$file_type = isset($_FILES['hinhanh']['type']) ? $_FILES['hinhanh']['type'] : "";
$file_size = isset($_FILES['hinhanh']['size']) ? $_FILES['hinhanh']['size'] : "";
$file_error = isset($_FILES['hinhanh']['error']) ? $_FILES['hinhanh']['error'] : "";

// Lấy thời gian hiện tại
$dmyhis = date("Y") . date("m") . date("d") . date("H") . date("i") . date("s");

// Tạo tên file hình ảnh
$file__name__ = $dmyhis . $file_name;
$ma = $_GET['matt'];

// Nếu có hình ảnh được tải lên
if ($_FILES['hinhanh']['name'] != "") {
    // Di chuyển file hình ảnh
    move_uploaded_file($file_tmp, $upload_image . $file__name__);

    // Chuẩn bị câu lệnh UPDATE
    $sql_update = "
        UPDATE tintuc SET 
            tieude = ?,
            ndngan = ?,
            noidung = ?,
            hinhanh = ?,
            tacgia = ?
        WHERE matt = ?
    ";

    // Sử dụng prepared statement để tránh SQL injection
    if ($stmt = $conn->prepare($sql_update)) {
        $stmt->bind_param("sssssi", $tieude, $ndngan, $noidung, $file__name__, $tacgia, $ma);
        $update = $stmt->execute();
        $stmt->close();
    }
} else {
    // Nếu không có hình ảnh, cập nhật mà không thay đổi hình ảnh
    $sql_update = "
        UPDATE tintuc SET 
            tieude = ?,
            ndngan = ?,
            noidung = ?,
            tacgia = ?
        WHERE matt = ?
    ";

    // Sử dụng prepared statement để tránh SQL injection
    if ($stmt = $conn->prepare($sql_update)) {
        $stmt->bind_param("ssssi", $tieude, $ndngan, $noidung, $tacgia, $ma);
        $update = $stmt->execute();
        $stmt->close();
    }
}

if ($update) {
    echo "Sửa tin tức thành công";
    echo '<center><meta http-equiv="refresh" content="2;url=admin.php?admin=hienthitt"></center>';
} else {
    echo "Sửa tin tức thất bại: " . $conn->error;
}
?>
