<?php
session_start();

if (isset($_SESSION['username'])) {
    if ($_SESSION['phanquyen'] == 1) {
        header("Location: ../index.php");
        exit;
    }
    if ($_SESSION['phanquyen'] == 0) {
        header("Location: admin.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập quản trị</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #e6f0fa; /* Light blue background to match the image */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .login-form {
            background-color: #ffffff;
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
        }

        .form-control {
            border: 1px solid #e1e1e1;
            border-radius: 10px;
            height: 45px;
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            color: #333;
        }

        .form-control::placeholder {
            color: #c1c1c1;
            font-size: 16px;
        }

        .forgot-password {
            display: block;
            text-align: left;
            color: #000;
            font-size: 14px;
            margin-bottom: 20px;
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .sign-in-btn {
            background-color: #1c2526;
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
                    <div class="login-title text-center">Login</div>

                    <?php
                    require("../include/connect.php");

                    if (isset($_POST['login'])) {
                        $username = $_POST['user'] ?? '';
                        $password = $_POST['pass'] ?? '';

                        if (empty($username) || empty($password)) {
                            echo "<p class='thongbao1'>Vui lòng nhập đầy đủ tài khoản và mật khẩu</p>";
                        } else {
                            $username = mysqli_real_escape_string($conn, $username);
                            $sql = "SELECT * FROM nguoidung WHERE username = '$username'";
                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) == 0) {
                                echo "<p class='thongbao1'>Tài khoản không tồn tại</p>";
                            } else {
                                $row = mysqli_fetch_assoc($result);

                                if (!password_verify($password, $row['password'])) {
                                    echo "<p class='thongbao1'>Mật khẩu không chính xác</p>";
                                } else {
                                    $_SESSION['username'] = $username;
                                    $_SESSION['phanquyen'] = $row['phanquyen'];
                                    $_SESSION['idnd'] = $row['idnd'];

                                    if ($row['phanquyen'] == 0) {
                                        echo "<script>
                                                alert('Đăng nhập quản trị thành công');
                                                window.location.href = 'admin.php';
                                              </script>";
                                    } else {
                                        header("Location: ../index.php");
                                        exit;
                                    }
                                }
                            }
                        }
                    }
                    ?>

                    <form action="" method="post">
                        <input type="text" name="user" class="form-control" placeholder="Enter Email" required>
                        <input type="password" name="pass" class="form-control" placeholder="Enter Password" required>
                        <button type="submit" name="login" class="sign-in-btn">Sign In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>