<?php
session_start();

include("../administrator/connect.php");
include("../administrator/function.php");

echo "<meta charset='UTF-8' />";

if (isset($_POST['login'])) {
    $username = $_POST['user'];
    $password = $_POST['pass']; 

    // Sử dụng MySQLi để kiểm tra xem username có tồn tại không
    $sql_check = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $sql_check->bind_param("s", $username); // "s" là kiểu dữ liệu cho chuỗi
    $sql_check->execute();
    $result = $sql_check->get_result();
    $dem = $result->num_rows;

    if ($dem == 0) {
        $_SESSION['thongbaolo'] = "Tài khoản không tồn tại";
        echo "<script>
                alert('Tài khoản không tồn tại');
                window.location.href = 'login.php';
              </script>";
    } else {
        // Kiểm tra username và password
        $sql_check2 = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $sql_check2->bind_param("ss", $username, $password); // "ss" là kiểu dữ liệu cho 2 chuỗi
        $sql_check2->execute();
        $result2 = $sql_check2->get_result();
        $dem2 = $result2->num_rows;

        if ($dem2 == 0) {
            echo "<script>
                    alert('Mật khẩu đăng nhập không đúng');
                    window.location.href = 'login.php';
                  </script>";
        } else {
            // Lấy thông tin người dùng
            while ($rows = $result2->fetch_object()) {
                $phanquyen = $rows->phanquyen;
                if ($phanquyen == '1') {
                    $_SESSION['admin'] = $username;
                    echo "<script>
                            alert('Đăng nhập thành công');
                            window.open('trangchu.php','_self',1);
                          </script>";
                } else {
                    $_SESSION['user'] = $username;
                    echo "<script>
                            alert('Đăng nhập thành công!');
                            window.location.href = '../index.php';
                          </script>";
                }
            }
        }
    }
}
?>

