<?php
include './include/connect.php'; // Đảm bảo đây đúng đường dẫn đến file của bạn
// $conn phải là một mysqli instance

if (!isset($conn) || !($conn instanceof mysqli)) {
    die("Lỗi: Kết nối MySQLi chưa được khởi tạo đúng.");
}

if (isset($_POST['submit'])) {
    // Lấy và xử lý dữ liệu đầu vào
    $chude     = trim($_POST['chude']     ?? '');
    $hoten     = trim($_POST['hoten']     ?? '');
    $email     = trim($_POST['email']     ?? '');
    $dienthoai = trim($_POST['dienthoai'] ?? '');
    $noidung   = trim($_POST['noidung']   ?? '');

    // Kiểm tra rỗng
    if (empty($chude) || empty($hoten) || empty($email) || empty($dienthoai) || empty($noidung)) {
        echo "<script>alert('Vui lòng điền đầy đủ các trường thông tin.'); history.back();</script>";
        exit;
    }

    // Kiểm tra định dạng email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Định dạng email không hợp lệ.'); history.back();</script>";
        exit;
    }

    // Chuẩn bị dữ liệu và câu lệnh SQL
    $ngaygui = date("Y-m-d H:i:s");
    $sql = "INSERT INTO hotro (chude, noidung, hoten, dienthoai, email, ngaygui)
            VALUES (?, ?, ?, ?, ?, ?)";

    // Thử prepare
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Lỗi chuẩn bị câu lệnh SQL: " . htmlspecialchars($conn->error));
    }

    // Bind tham số
    if (!$stmt->bind_param("ssssss", $chude, $noidung, $hoten, $dienthoai, $email, $ngaygui)) {
        die("Lỗi bind_param: " . htmlspecialchars($stmt->error));
    }

    // Thực thi
    if ($stmt->execute()) {
        header("Location: index.php?success=1");
        exit;
    } else {
        echo "<script>alert('Lỗi khi gửi dữ liệu: " . htmlspecialchars($stmt->error) . "'); history.back();</script>";
    }

    $stmt->close();
}
?>
