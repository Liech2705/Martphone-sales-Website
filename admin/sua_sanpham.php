<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Sửa Sản Phẩm</title>
</head>
<body>
<?php
include '../include/connect.php'; // $conn = mysqli_connect...

// Khởi tạo mảng lỗi
$errors = [];

// 1) Lấy idsp và kiểm tra
if (!isset($_GET['idsp'])) {
    die("<script>alert('Thiếu ID sản phẩm.'); window.location='danhsach_sanpham.php';</script>");
}
$idsp = (int)$_GET['idsp'];

// 2) Lấy dữ liệu sản phẩm
$stmt = $conn->prepare("SELECT * FROM sanpham WHERE idsp = ?");
$stmt->bind_param("i", $idsp);
$stmt->execute();
$result = $stmt->get_result();
if (!$product = $result->fetch_assoc()) {
    die("<script>alert('Không tìm thấy sản phẩm.'); window.location='danhsach_sanpham.php';</script>");
}
$stmt->close();

// 3) Xử lý form
if (isset($_POST['submit'])) {
    $tensp      = trim($_POST['tensp']);
    $mau        = trim($_POST['mau']);
    $chitiet    = trim($_POST['chitiet']);
    $soluong    = $_POST['soluong']; // Không ép kiểu ngay để kiểm tra
    $gia        = $_POST['gia'];
    $khuyenmai1 = trim($_POST['khuyenmai1']);
    $khuyenmai2 = trim($_POST['khuyenmai2']);
    $madm       = (int)$_POST['madm'];

    // Kiểm tra trường bắt buộc
    if (empty($tensp)) {
        $errors[] = "Bạn chưa nhập tên sản phẩm";
    }

    // Kiểm tra giá
    if (empty($gia)) {
        $errors[] = "Bạn chưa nhập giá sản phẩm";
    } else {
        $gia = str_replace(',', '.', $gia); // Chuyển dấu phẩy thành dấu chấm
        if (!is_numeric($gia)) {
            $errors[] = "Giá phải là số";
        } elseif ($gia < 0) {
            $errors[] = "Giá không được âm";
        }
    }

    // Kiểm tra số lượng
    if (empty($soluong)) {
        $errors[] = "Bạn chưa nhập số lượng";
    } else {
        if (!is_numeric($soluong)) {
            $errors[] = "Số lượng phải là số";
        } elseif ($soluong < 0) {
            $errors[] = "Số lượng không được âm";
        } else {
            $soluong = (int)$soluong;
        }
    }

    // Kiểm tra khuyến mãi
    if (!empty($khuyenmai1)) {
        if (!is_numeric($khuyenmai1)) {
            $errors[] = "Phần trăm giảm giá phải là số";
        } elseif ($khuyenmai1 < 0 || $khuyenmai1 > 100) {
            $errors[] = "Phần trăm giảm giá phải từ 0-100%";
        }
    }

    // Kiểm tra danh mục
    if ($madm <= 0) {
        $errors[] = "Bạn chưa chọn danh mục";
    }

    // Kiểm tra hình ảnh
    $newImage = $product['hinhanh'];
    if (!empty($_FILES['hinhanh']['tmp_name'])) {
        $ext = strtolower(pathinfo($_FILES['hinhanh']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ext, $allowed)) {
            $errors[] = "Hình ảnh phải có định dạng JPG, PNG hoặc GIF";
        } else {
            $newImage = date("YmdHis") . '_' . basename($_FILES['hinhanh']['name']);
        }
    }

    // Nếu không có lỗi, tiến hành cập nhật
    if (empty($errors)) {
        // Di chuyển file ảnh nếu có
        if (!empty($_FILES['hinhanh']['tmp_name'])) {
            move_uploaded_file($_FILES['hinhanh']['tmp_name'], __DIR__ . '/../img/uploads/' . $newImage);
        }

        // Câu lệnh UPDATE
        $sql = "UPDATE sanpham 
                SET tensp=?, hinhanh=?, mau=?, chitiet=?, soluong=?, gia=?, khuyenmai1=?, khuyenmai2=?, madm=? 
                WHERE idsp=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssiddsii",
            $tensp,
            $newImage,
            $mau,
            $chitiet,
            $soluong,
            $gia,
            $khuyenmai1,
            $khuyenmai2,
            $madm,
            $idsp
        );

        if ($stmt->execute()) {
            echo "<script>
                    alert('Cập nhật sản phẩm thành công');
                    window.location='admin.php?admin=hienthisp';
                  </script>";
        } else {
            $errors[] = "Lỗi cập nhật: " . htmlspecialchars($stmt->error);
        }
        $stmt->close();
    }
}
?>
<link rel="stylesheet" href="/admin/css/form.css">
<style>
.error-message {
    color: red;
    margin-bottom: 15px;
    border: 1px solid red;
    padding: 10px;
    background-color: #ffeeee;
}
.error-list {
    list-style: disc;
    margin-left: 20px;
}
textarea {
    width: 300px;
    height: 100px;
    vertical-align: top;
}
</style>
<div class="fix_form">
<?php if (!empty($errors)): ?>
    <div class="error-message">
        <strong>Có lỗi xảy ra:</strong>
        <ul class="error-list">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form action="" method="post" name="frm" enctype="multipart/form-data" class="form" onsubmit="return kiemtra();">
  <table>
      <h2>Sửa Sản Phẩm</h2>
    <tr>
      <td>Tên SP</td>
      <td><input type="text" name="tensp" value="<?= htmlspecialchars($product['tensp']) ?>" /></td>
    </tr>
    <tr>
      <td>Hình ảnh</td>
      <td class="img_hienthi_sp">
        <img src="../img/uploads/<?= htmlspecialchars($product['hinhanh']) ?>"
             width="80" height="120" alt="" /><br/><br/>
        <input type="file" name="hinhanh" accept="image/jpeg,image/png,image/gif" />
      </td>
    </tr>
    <tr>
      <td>Màu</td>
      <td><input type="text" name="mau" value="<?= htmlspecialchars($product['mau']) ?>" /></td>
    </tr>
    <tr>
      <td>Chi tiết</td>
      <td><textarea name="chitiet"><?php echo isset($product['chitiet']) ? htmlspecialchars($product['chitiet']) : ''; ?></textarea></td>
    </tr>
    <tr>
      <td>Số lượng</td>
      <td><input type="text" name="soluong" value="<?= (int)$product['soluong'] ?>" /></td>
    </tr>
    <tr>
      <td>Giá</td>
      <td><input type="text" name="gia" value="<?= number_format($product['gia'], 2, '.', ',') ?>" /></td>
    </tr>
    <tr>
      <td>Giảm giá</td>
      <td><input type="text" name="khuyenmai1" value="<?= (float)$product['khuyenmai1'] ?>" /></td>
    </tr>
    <tr>
      <td>Tặng thêm</td>
      <td><textarea name="khuyenmai2"><?php echo isset($product['khuyenmai2']) ? htmlspecialchars($product['khuyenmai2']) : ''; ?></textarea></td>
    </tr>
    <tr>
      <td>Mã DM</td>
      <td>
        <select name="madm">
          <option value="">Chọn danh mục</option>
          <?php
          $res = $conn->query("SELECT madm,tendm FROM danhmuc ORDER BY tendm");
          while ($dm = $res->fetch_assoc()):
             $sel = $dm['madm']==$product['madm']?' selected':'';
          ?>
            <option value="<?= $dm['madm'] ?>"<?= $sel ?>>
              <?= htmlspecialchars($dm['tendm']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </td>
    </tr>
  </table>
  <div class="funtion">
        <input type="submit" name="submit" value="Cập nhật"/>
  </div>
</form>
</div>
<script>
function kiemtra() {
    var f = document.frm;
    var tensp = f.tensp.value.trim();
    var soluong = f.soluong.value.trim();
    var gia = f.gia.value.trim();
    var khuyenmai1 = f.khuyenmai1.value.trim();
    var madm = f.madm.value;

    if (!tensp) {
        alert('Bạn chưa nhập tên SP');
        f.tensp.focus();
        return false;
    }

    if (!soluong) {
        alert('Bạn chưa nhập số lượng');
        f.soluong.focus();
        return false;
    }
    if (isNaN(soluong) || soluong < 0) {
        alert('Số lượng phải là số dương');
        f.soluong.focus();
        return false;
    }

    if (!gia) {
        alert('Bạn chưa nhập giá sản phẩm');
        f.gia.focus();
        return false;
    }
    gia = gia.replace(/,/g, '.');
    if (isNaN(gia) || gia < 0) {
        alert('Giá phải là số dương');
        f.gia.focus();
        return false;
    }

    if (khuyenmai1 !== '') {
        if (isNaN(khuyenmai1) || khuyenmai1 < 0 || khuyenmai1 > 100) {
            alert('Phần trăm giảm giá phải là số từ 0-100');
            f.khuyenmai1.focus();
            return false;
        }
    }

    if (!madm) {
        alert('Bạn chưa chọn danh mục');
        f.madm.focus();
        return false;
    }

    return true;
}
</script>
</body>
</html>