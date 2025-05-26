<?php
// Khởi động phiên với bảo mật
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Yêu cầu HTTPS
session_start();
require_once("include/connect.php");
mysqli_set_charset($conn, 'utf8');

// Lấy token từ URL
$token = $_GET['token'] ?? '';
$email = '';
$token_valid = false;

if (!empty($token)) {
    // Kiểm tra token trong bảng reset_tokens
    $stmt = mysqli_prepare($conn, "SELECT email, expires FROM reset_tokens WHERE token = ?");
    if ($stmt === false) {
        error_log("Lỗi cơ sở dữ liệu: " . mysqli_error($conn), 3, "errors.log");
        $_SESSION['message'] = "Lỗi hệ thống. Vui lòng thử lại sau.";
        header("Location: forgot_password.php");
        exit;
    }
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $token_data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($token_data) {
        $email = $token_data['email'];
        $expires = strtotime($token_data['expires']);
        $current_time = time();

        if ($expires > $current_time) {
            $token_valid = true;
        } else {
            // Xóa token hết hạn
            $stmt = mysqli_prepare($conn, "DELETE FROM reset_tokens WHERE token = ?");
            mysqli_stmt_bind_param($stmt, "s", $token);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $_SESSION['message'] = "Liên kết đặt lại mật khẩu đã hết hạn. Vui lòng yêu cầu lại.";
            header("Location: forgot_password.php");
            exit;
        }
    } else {
        $_SESSION['message'] = "Liên kết không hợp lệ. Vui lòng yêu cầu lại.";
        header("Location: forgot_password.php");
        exit;
    }
} else {
    $_SESSION['message'] = "Không tìm thấy liên kết đặt lại mật khẩu.";
    header("Location: forgot_password.php");
    exit;
}

// Xử lý form đặt lại mật khẩu
if (isset($_POST['reset_password']) && $token_valid) {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Kiểm tra mật khẩu
    if (empty($password) || empty($confirm_password)) {
        $_SESSION['message'] = "Vui lòng nhập đầy đủ mật khẩu.";
    } elseif ($password !== $confirm_password) {
        $_SESSION['message'] = "Mật khẩu xác nhận không khớp.";
    } elseif (strlen($password) < 6) {
        $_SESSION['message'] = "Mật khẩu phải có ít nhất 6 ký tự.";
    } else {
        // Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Cập nhật mật khẩu vào bảng nguoidung
        $stmt = mysqli_prepare($conn, "UPDATE nguoidung SET password = ? WHERE email = ?");
        if ($stmt === false) {
            error_log("Lỗi cơ sở dữ liệu: " . mysqli_error($conn), 3, "errors.log");
            $_SESSION['message'] = "Lỗi hệ thống. Vui lòng thử lại sau.";
            header("Location: reset_password.php?token=" . urlencode($token));
            exit;
        }
        mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Xóa token sau khi sử dụng
        $stmt = mysqli_prepare($conn, "DELETE FROM reset_tokens WHERE token = ?");
        mysqli_stmt_bind_param($stmt, "s", $token);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $_SESSION['message'] = "Mật khẩu đã được đặt lại thành công. Vui lòng đăng nhập.";
        echo "<script>
        alert('Mật khẩu đã được đặt lại thành công. Vui lòng đăng nhập.');
        window.location.href = 'index.php';
      </script>";        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Lại Mật Khẩu</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .login-form {
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(48, 204, 239, 0.1);
            padding: 25px;
            width: 100%;
            max-width: 400px;
        }

        .login-title {
            font-size: 24px;
            font-weight: 700;
            color: #000;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-control {
            border-radius: 10px;
            height: 45px;
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            color: #333;
        }

        .form-control::placeholder {
            font-size: 16px;
        }

        .back-link {
            display: block;
            text-align: left;
            color: #000;
            font-size: 14px;
            margin-bottom: 20px;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .sign-in-btn {
            background-color:rgb(79, 203, 216);
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            height: 50px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        

        .thongbao1 {
            color: #dc3545; /* Bootstrap danger color */
            font-size: 14px;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="login-form">
                    <div class="login-title">Đặt Lại Mật Khẩu</div>

                    <?php if (isset($_SESSION['message'])): ?>
                        <p class="thongbao1"><?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></p>
                    <?php endif; ?>

                    <?php if ($token_valid): ?>
                        <div id="error" class="thongbao1"></div>
                        <form method="POST" action="">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Mật khẩu mới" required>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Xác nhận mật khẩu" required>
                            <a href="index.php" class="back-link">Quay lại đăng nhập</a>
                            <button type="submit" name="reset_password" class="sign-in-btn">Đặt lại mật khẩu</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.querySelector('form')?.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const error = document.getElementById('error');

            if (password.length < 6) {
                e.preventDefault();
                error.textContent = 'Mật khẩu phải có ít nhất 6 ký tự';
            } else if (password !== confirmPassword) {
                e.preventDefault();
                error.textContent = 'Mật khẩu xác nhận không khớp';
            }
        });
    </script>
</body>
</html>