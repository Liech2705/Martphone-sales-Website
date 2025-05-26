<?php
session_start();
require_once("include/connect.php");

// Include PHPMailer for sending emails
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require './PHPMailer-master/src/Exception.php';
require './PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/src/SMTP.php';

if (isset($_POST['reset_password'])) {
    $email = trim($_POST['email'] ?? '');

    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Vui lòng nhập địa chỉ email hợp lệ";
        echo "<script>
                alert('Vui lòng nhập địa chỉ email hợp lệ');
                window.location.href = 'forgot_password.php';
              </script>";
        exit;
    }

    // Check if email exists in database
    $stmt = mysqli_prepare($conn, "SELECT * FROM nguoidung WHERE email = ?");
    if ($stmt === false) {
        $_SESSION['message'] = "Lỗi cơ sở dữ liệu: " . mysqli_error($conn);
        echo "<script>
                alert('Lỗi cơ sở dữ liệu. Vui lòng thử lại sau');
                window.location.href = 'forgot_password.php';
              </script>";
        exit;
    }
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$user) {
        $_SESSION['message'] = "Email không tồn tại trong hệ thống";
        echo "<script>
                alert('Email không tồn tại trong hệ thống');
                window.location.href = 'forgot_password.php';
              </script>";
        exit;
    }

    // Generate reset token
    $token = bin2hex(random_bytes(32));
    $expires = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token valid for 1 hour

    // Store token in database
    $stmt = mysqli_prepare($conn, "INSERT INTO reset_tokens (email, token, expires) VALUES (?, ?, ?)");
    if ($stmt === false) {
        $_SESSION['message'] = "Lỗi cơ sở dữ liệu: " . mysqli_error($conn);
        echo "<script>
                alert('Lỗi cơ sở dữ liệu. Vui lòng thử lại sau');
                window.location.href = 'forgot_password.php';
              </script>";
        exit;
    }
    mysqli_stmt_bind_param($stmt, "sss", $email, $token, $expires);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Send reset email
    $resetLink = "http://localhost/dienthoai/reset_password.php?token=" . $token; // Adjust domain as needed
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->CharSet = 'utf-8';
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'autran152@gmail.com';
        $mail->Password = 'dhlb lcsq sfio othm';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('autran152@gmail.com', 'Websit Điện thoại');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Yêu cầu đặt lại mật khẩu';
        $mail->Body = "Chào bạn,<br><br>
                       Bạn đã yêu cầu đặt lại mật khẩu. Vui lòng nhấp vào liên kết sau để đặt lại mật khẩu của bạn:<br>
                       <a href='$resetLink'>Đặt lại mật khẩu</a><br><br>
                       Liên kết này sẽ hết hạn sau 1 giờ.<br>
                       Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.<br><br>
                       Trân trọng,<br>Website điện thoại";

        $mail->send();
        $_SESSION['message'] = "Liên kết đặt lại mật khẩu đã được gửi đến email của bạn";
        echo "<script>
                alert('Liên kết đặt lại mật khẩu đã được gửi đến email của bạn');
                window.location.href = 'forgot_password.php';
              </script>";
        exit;
    } catch (Exception $e) {
        $_SESSION['message'] = "Không thể gửi email. Lỗi: {$mail->ErrorInfo}";
        echo "<script>
                alert('Không thể gửi email. Vui lòng thử lại sau');
                window.location.href = 'forgot_password.php';
              </script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu</title>
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
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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
            background-color:rgb(39, 135, 159);
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            height: 50px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .sign-in-btn:hover {
            background-color: #2f3a3b;
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
                    <div class="login-title">Quên Mật Khẩu</div>

                    <?php if (isset($_SESSION['message'])): ?>
                        <p class="thongbao1"><?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></p>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <input type="email" name="email" class="form-control" placeholder="Nhập Email" required>
                        <a href="index.php" class="back-link">Quay lại đăng nhập</a>
                        <button type="submit" name="reset_password" class="sign-in-btn">Gửi Liên Kết Đặt Lại</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>