<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
};

include(__DIR__ . '/../include/connect.php');

// Xử lý cập nhật số lượng bằng AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax']) && $_POST['ajax'] == 'update_quantity') {
    $id = intval($_POST['id']);
    $sl = intval($_POST['sl']);

    if ($id > 0 && $sl > 0) {
        $_SESSION['cart'][$id] = $sl;

        $sql = "SELECT gia, khuyenmai1 FROM sanpham WHERE idsp = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        $giamgia = $row['gia'] * ((100 - $row['khuyenmai1']) / 100);
        $thanhtien = $giamgia * $sl;

        // Tính tổng tiền giỏ hàng
        $tongtien = 0;
        foreach ($_SESSION['cart'] as $idsp => $soluong) {
            $sp = mysqli_query($conn, "SELECT gia, khuyenmai1 FROM sanpham WHERE idsp = $idsp");
            $sp_row = mysqli_fetch_assoc($sp);
            $giaKM = $sp_row['gia'] * ((100 - $sp_row['khuyenmai1']) / 100);
            $tongtien += $soluong * $giaKM;
        }

        echo json_encode([
            'thanhtien' => number_format($thanhtien, 0, ",", ".") . 'VNĐ',
            'tongtien' => number_format($tongtien, 0, ",", ".") . 'VNĐ'
        ]);
        exit;
    }
}


$stt = isset($_GET['idsp']) ? intval($_GET['idsp']) : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case "xoa":
        unset($_SESSION['cart']);
        echo "<script>alert('Bạn đã xóa thành công'); window.location.href='./cart/index.php';</script>";
        break;

    case "check":
        include('check.php');
        break;

    case "thanhtoan":
        include('ttkh.php');
        break;

    case "insert":
        include('insert.php');
        break;

    case "add":
        $sql = "SELECT soluong FROM sanpham WHERE idsp = $stt";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if (!$row || $row['soluong'] == 0) {
            echo '<script>alert("Sản phẩm này tạm thời hết hàng"); history.back();</script>';
        } elseif (empty($_POST['soluongmua']) || intval($_POST['soluongmua']) == 0) {
            $_SESSION['cart'][$stt] = 1;
            echo '<script>alert("Sản phẩm đã được thêm vào giỏ hàng của bạn"); window.location.href="index.php";</script>';
        } else {
            $_SESSION['cart'][$stt] = intval($_POST['soluongmua']);
            echo '<script>alert("Sản phẩm đã được thêm vào giỏ hàng của bạn"); window.location.href="index.php";</script>';
        }
        break;

    case "update":
        $sl = isset($_POST['sl']) ? intval($_POST['sl']) : 0;
        if ($sl <= 0 || isset($_POST['huy'])) {
            unset($_SESSION['cart'][$stt]);
        } else {
            $_SESSION['cart'][$stt] = $sl;
        }
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            echo '<script>alert("Cập nhật thành công"); window.location.href="index.php?content=cart";</script>';
        }
        exit;
        break;
    case "ATM":
        include('checkout_VNPay.php');
        break;

    default:
        // View cart
        include('viewcart.php');
        break;
}
?>
