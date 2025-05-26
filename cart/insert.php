<?php
// index.php?content=cart&action=insert

$vnp_ResponseCode = $_GET['vnp_ResponseCode']; // Mã phản hồi
$vnp_SecureHash = $_GET['vnp_SecureHash']; // Mã hash để xác minh
// ... kiểm tra lại chữ ký hash tại đây nếu cần

if ($vnp_ResponseCode != '00') {
    // ➜ Thanh toán bị hủy hoặc thất bại, KHÔNG ghi đơn hàng
    echo "<script>alert('Thanh toán thất bại hoặc bị hủy'); window.location.href='./cart/index.php';</script>";
    exit();
}


if($action == "insert") {
    $hoten = $_SESSION['checkout']['hoten'] ?? '';
    $dienthoai = $_SESSION['checkout']['dienthoai'] ?? '';
    $diachi = $_SESSION['checkout']['diachi'] ?? '';
    $email = $_SESSION['checkout']['email'] ?? '';
    $phuongthuc = $_SESSION['checkout']['phuongthuc'] ?? '';
    $ngay = date('Y-m-d');

    // Kiểm tra người dùng đã đăng nhập chưa
    if (isset($_SESSION['idnd'])) {
        $sql = "SELECT * FROM nguoidung WHERE idnd = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['idnd']); // Sử dụng chuẩn bị câu lệnh SQL
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $idnd = $row['idnd'];
    }

    // Thực hiện INSERT vào bảng hoadon
    if (isset($idnd)) {
        $sql = "INSERT INTO hoadon(idnd, hoten, diachi, dienthoai, email, ngaydathang, trangthai) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssssi", $idnd, $hoten, $diachi, $dienthoai, $email, $ngay, $trangthai);
    } else {
        $sql = "INSERT INTO hoadon(hoten, diachi, dienthoai, email, ngaydathang, trangthai) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $hoten, $diachi, $dienthoai, $email, $ngay, $trangthai);
    }

    $trangthai = 1; // Giá trị trang thái là 1
    $stmt->execute();

    // Lấy mã hóa đơn vừa tạo
    $mahd = $conn->insert_id;

    // Thêm chi tiết hóa đơn vào bảng chitiethoadon
    foreach ($_SESSION['cart'] as $stt => $soluong) {
        $sql = "SELECT * FROM sanpham WHERE idsp = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $stt);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $tensp = $row['tensp'];
        $gia = $row['gia'] * ((100 - $row['khuyenmai1']) / 100);

        $sql1 = "INSERT INTO chitiethoadon(mahd, Tensp, Soluong, gia, phuongthucthanhtoan) 
                 VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql1);
        $stmt->bind_param("isdis", $mahd, $tensp, $soluong, $gia, $phuongthuc);
        $stmt->execute();
    }

    // Cập nhật số lượng đã bán của sản phẩm
    foreach ($_SESSION['cart'] as $stt => $soluong) {
        $sql = "SELECT * FROM sanpham WHERE idsp = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $stt);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $ban = $row['daban'] + $soluong;
        $sql = "UPDATE sanpham SET daban = ? WHERE idsp = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $ban, $stt);
        $stmt->execute();
    }

    // Xóa giỏ hàng
    unset($_SESSION['cart']);
    unset($_SESSION['checkout']);
}   
?>

<!-- Thông báo thành công -->
<script language="javascript">
    alert('Đơn hàng của bạn đã thiết lập thành công, chúng tôi sẽ chuyển hàng cho bạn trong thời gian sớm nhất');
    window.open('index.php', '_self', 3);
</script>
