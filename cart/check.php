<?php
session_start();
include('./include/connect.php'); // đảm bảo kết nối CSDL

// KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['user'])) {
    echo '<script>alert("Bạn cần đăng nhập để thanh toán."); window.location.href="./cart/index.php";</script>';
    exit;
}

$loi = 0;

foreach ($_SESSION['cart'] as $stt => $soluong) {
    $sql = "SELECT soluong, tensp, daban FROM sanpham WHERE idsp = $stt";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sl = intval($soluong);

        // Nếu số lượng mua > còn lại
        $conlai = intval($row['soluong']) - intval($row['daban']);
        if ($conlai < $sl) {
            echo '<meta http-equiv="refresh" content="2;index.php">';
            echo "<script>
                alert('Sản phẩm \"" . addslashes($row['tensp']) . "\" không đủ hàng trong kho.');
                window.location.href = './cart/index.php';
            </script>";
            $loi += 1;
            exit;
        }
    } else {
        echo "Không tìm thấy sản phẩm có ID: $stt<br>";
        $loi += 1;
    }
}

// Nếu không có lỗi thì chuyển sang thanh toán
if ($loi == 0) {
    echo '<meta http-equiv="refresh" content="0;index.php?content=cart&action=thanhtoan">';
}
?>
