
<?php
include 'include/connect.php'; // Kết nối cơ sở dữ liệu

// Định nghĩa hàm redirect
function redirect($url, $message = "", $seconds = 0) {
    if ($message != "") {
        echo "<p>$message</p>"; // Hiển thị thông báo nếu có
    }

    header("refresh:$seconds; url=$url"); // Chuyển hướng đến URL sau 1 khoảng thời gian
    exit(); // Dừng chương trình để không thực thi mã thêm
}

if (isset($_POST['submit'])) {
    // Lấy và làm sạch dữ liệu từ form
    $tennd     = htmlspecialchars(trim($_POST['tennd']));
    $username  = htmlspecialchars(trim($_POST['user']));
    $password  = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    $email     = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $ngaysinh  = $_POST['ngaysinh'];
    $gioitinh  = htmlspecialchars(trim($_POST['gioitinh'])); 
    $dienthoai = htmlspecialchars(trim($_POST['dienthoai'])); 
    $diachi    = htmlspecialchars(trim($_POST['diachi']));
    $ngaydangky = date("Y-m-d");

    try {
        $sql = "INSERT INTO nguoidung (tennd, username, password, ngaysinh, gioitinh, email, dienthoai, diachi, ngaydangky, phanquyen) 
                VALUES ('$tennd', '$username', '$password', '$ngaysinh', '$gioitinh', '$email', '$dienthoai', '$diachi', '$ngaydangky', 1)";

        if (mysqli_query($conn, $sql)) {
            redirect("index.php", "Bạn đã đăng ký thành công.", 2); // Redirect sau khi đăng ký thành công
        } else {
            echo "Đăng ký thất bại. Lỗi: " . mysqli_error($conn); // Lấy lỗi từ mysqli_error
        }
    } catch (Exception $e) {
        echo "Lỗi: " . $e->getMessage();
    }
}
?>

