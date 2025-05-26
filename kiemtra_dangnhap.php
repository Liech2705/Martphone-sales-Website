<?php
session_start();
require_once("include/connect.php");

if (isset($_POST['login'])) {
    $username = trim($_POST['user'] ?? '');
    $password = trim($_POST['pass'] ?? '');

    if (empty($username) || empty($password)) {
        $_SESSION['thongbaolo'] = "Vui lòng điền đầy đủ tên đăng nhập và mật khẩu";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT * FROM nguoidung WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if (!$user) {
            $_SESSION['thongbaolo'] = "Tài khoản không tồn tại";
        } elseif (!password_verify($password, $user['password'])) {
            $_SESSION['thongbaolo'] = "Mật khẩu không đúng";
        } else {
            $_SESSION['user'] = $user['username'];
            $_SESSION['phanquyen'] = $user['phanquyen'];
            $_SESSION['idnd'] = $user['idnd'];
            $redirect = $user['phanquyen'] == 0 ? 'index.php' : 'index.php';
            header("Location: $redirect");
            exit;
        }
    }

    // Nếu lỗi, quay lại trang chủ và mở lại modal
    header("Location: index.php?login_error=1");
    exit;
}
?>
