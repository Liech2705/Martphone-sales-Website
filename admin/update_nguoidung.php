<?php
include('../include/connect.php');
include('function/function.php');

// Kiểm tra nếu dữ liệu POST tồn tại
if (isset($_POST['tennd'], $_POST['user'], $_POST['email'], $_POST['dienthoai'], $_POST['phanquyen'], $_GET['idnd'])) {
    // Lấy dữ liệu từ POST và GET
    $tennd = $_POST['tennd'];
    $user = $_POST['user'];
    $email = $_POST['email'];
    $dienthoai = $_POST['dienthoai'];
    $phanquyen = $_POST['phanquyen'];
    $id = $_GET['idnd'];

    // Kiểm tra dữ liệu đầu vào (ví dụ: email hợp lệ)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email không hợp lệ.";
        exit;
    }

    // Chuẩn bị câu lệnh UPDATE SQL
    $sql_update = "
        UPDATE nguoidung SET
            tennd = ?,
            username = ?,
            email = ?,
            dienthoai = ?,
            phanquyen = ?
        WHERE idnd = ?
    ";

    // Dùng prepared statement để tránh SQL injection
    if ($stmt = $conn->prepare($sql_update)) {
        // Liên kết các tham số với câu lệnh
        $stmt->bind_param("ssssii", $tennd, $user, $email, $dienthoai, $phanquyen, $id);
        
        // Thực thi câu lệnh
        if ($stmt->execute()) {
            // Chuyển hướng nếu thành công
            header("Location: admin.php?admin=hienthind&msg=" . urlencode("Bạn đã sửa thành công người dùng."));
            exit();
        } else {
            // Nếu không thành công, hiển thị lỗi
            echo "Lỗi khi sửa người dùng: " . $stmt->error;
        }

        // Đóng statement
        $stmt->close();
    } else {
        // Nếu câu lệnh không thể chuẩn bị được
        echo "Lỗi khi chuẩn bị câu lệnh: " . $conn->error;
    }
} else {
    echo "Thiếu dữ liệu đầu vào.";
}
?>
