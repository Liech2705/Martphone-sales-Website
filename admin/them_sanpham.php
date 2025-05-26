<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Thêm Sản Phẩm</title>
</head>

<body>
<?php
include '../include/connect.php';

// Khởi tạo biến lỗi
$errors = [];

if (isset($_POST['submit'])) {
    $ten_sanpham = $_POST['tensp'];
    $gia = $_POST['gia'];
    $mau = $_POST['mau'];
    $chitiet = $_POST['chitiet'];
    $soluong = $_POST['soluong'];
    $khuyenmai1 = $_POST['khuyenmai1'];
    $khuyenmai2 = $_POST['khuyenmai2'];
    $madm = $_POST['madm'];

    // Kiểm tra trường bắt buộc
    if (empty($ten_sanpham)) {
        $errors[] = "Bạn chưa nhập tên sản phẩm";
    }
    
    if (empty($gia)) {
        $errors[] = "Bạn chưa nhập giá sản phẩm";
    } else {
        $gia = str_replace(',', '.', $gia);
        if (!is_numeric($gia)) {
            $errors[] = "Giá phải là số";
        } elseif ($gia < 0) {
            $errors[] = "Giá không được âm";
        }
    }
    
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
    
    if (!empty($khuyenmai1)) {
        if (!is_numeric($khuyenmai1)) {
            $errors[] = "Phần trăm giảm giá phải là số";
        } elseif ($khuyenmai1 < 0 || $khuyenmai1 > 100) {
            $errors[] = "Phần trăm giảm giá phải từ 0-100%";
        }
    }
    
    if (empty($madm)) {
        $errors[] = "Bạn chưa chọn danh mục";
    } else {
        $madm = (int)$madm;
    }

    $upload_image = "../img/uploads/";
    $file_tmp = $_FILES['hinhanh']['tmp_name'] ?? "";
    $file_name = $_FILES['hinhanh']['name'] ?? "";
    
    if (empty($file_name)) {
        $errors[] = "Bạn chưa chọn hình ảnh";
    }

    if (empty($errors)) {
        $dmyhis = date("YmdHis");
        $ngay = date("Y-m-d H:i:s");
        $file__name__ = $dmyhis . $file_name;

        $trangthai = 0;
        $luotxem = 0;

        $insert = "INSERT INTO sanpham VALUES('', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ssssissssssi',
                $ten_sanpham, $file__name__, $mau, $chitiet, $soluong, $trangthai,
                $gia, $khuyenmai1, $khuyenmai2, $madm, $ngay, $luotxem
            );

            if (mysqli_stmt_execute($stmt)) {
                if (!empty($file_tmp)) {
                    move_uploaded_file($file_tmp, $upload_image . $file__name__);
                }
                echo "<script>
                    alert('Thêm sản phẩm thành công');
                    window.location.href = '/admin/admin.php?admin=hienthisp';
                </script>";
            } else {
                $errors[] = "Lỗi khi thêm sản phẩm: " . mysqli_stmt_error($stmt);
            }
        } else {
            $errors[] = "Không thể chuẩn bị truy vấn: " . mysqli_error($conn);
        }
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
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="" method="post" enctype="multipart/form-data" class="form" name="frm" onsubmit="return kiemtra()">
    <table>
        <h2 class="text-center">Thêm Sản Phẩm</h2>
        <tr>
            <td>Tên SP</td>
            <td><input type="text" name="tensp" value="<?php echo isset($ten_sanpham) ? htmlspecialchars($ten_sanpham) : ''; ?>"/></td>
        </tr>
        <tr>
            <td>Hình ảnh</td>
            <td><input type="file" name="hinhanh"/></td>
        </tr>
        <tr>
            <td>Màu</td>
            <td><input type="text" name="mau" value="<?php echo isset($mau) ? htmlspecialchars($mau) : ''; ?>"/></td>
        </tr>
        <tr>
            <td>Chi tiết</td>
            <td><textarea name="chitiet"><?php echo isset($chitiet) ? htmlspecialchars($chitiet) : ''; ?></textarea></td>
        </tr>
        <tr>
            <td>Số lượng</td>
            <td><input type="text" name="soluong" size="5" value="<?php echo isset($soluong) ? htmlspecialchars($soluong) : ''; ?>"/></td>
        </tr>
        <tr>
            <td>Giá</td>
            <td><input type="text" name="gia" value="<?php echo isset($gia) ? htmlspecialchars($gia) : ''; ?>"/></td>
        </tr>
        <tr>
            <td>Giảm giá %</td>
            <td><input type="text" name="khuyenmai1" size="1" value="<?php echo isset($khuyenmai1) ? htmlspecialchars($khuyenmai1) : ''; ?>"/></td>
        </tr>
        <tr>
            <td>Tặng thêm</td>
            <td><textarea name="khuyenmai2"><?php echo isset($khuyenmai2) ? htmlspecialchars($khuyenmai2) : ''; ?></textarea></td>
        </tr>
        <tr>
            <td>Danh mục</td>
            <td>
                <select name="madm">
                    <option value="">Chọn danh mục</option>
                    <?php
                        $show = mysqli_query($conn, "SELECT * FROM danhmuc WHERE dequi=0");
                        while ($show1 = mysqli_fetch_array($show)) {
                            $madm1 = $show1['madm'];    
                            $tendm1 = $show1['tendm'];
                            $selected = (isset($madm) && $madm == $madm1) ? 'selected' : '';
                            echo "<option value='".$madm1."' ".$selected.">".$tendm1."</option>";    
                            $show2 = mysqli_query($conn, "SELECT * FROM danhmuc WHERE dequi='".$madm1."'");
                            while ($show3 = mysqli_fetch_array($show2)) {
                                $madm2 = $show3['madm'];    
                                $tendm2 = $show3['tendm'];
                                $selected = (isset($madm) && $madm == $madm2) ? 'selected' : '';
                                echo "<option value='".$madm2."' ".$selected."> - ".$tendm2."</option>";
                            }
                        }
                    ?>
                </select>
            </td>
        </tr>
    </table>
    
    <div class="funtion">
        <input type="submit" name="submit" value="Thêm" />
    </div>
</form>
</div>
<script language="javascript">
function kiemtra() {
    var tensp = document.frm.tensp.value;
    var hinhanh = document.frm.hinhanh.value;
    var soluong = document.frm.soluong.value;
    var gia = document.frm.gia.value;
    var madm = document.frm.madm.value;
    var khuyenmai1 = document.frm.khuyenmai1.value;
    
    if (tensp == "") {
        alert("Bạn chưa nhập tên SP. Vui lòng kiểm tra lại");
        document.frm.tensp.focus();
        return false;
    }
    if (hinhanh == "") {
        alert("Bạn chưa chọn hình ảnh");    
        document.frm.hinhanh.focus();
        return false;
    }
    if (soluong == "") {
        alert("Bạn chưa nhập số lượng");    
        document.frm.soluong.focus();
        return false;
    }
    
    if (isNaN(soluong) || soluong < 0) {
        alert("Số lượng phải là số dương");
        document.frm.soluong.focus();
        return false;
    }
    
    if (gia == "") {
        alert("Bạn chưa nhập giá sản phẩm");
        document.frm.gia.focus();
        return false;
    }
    
    gia = gia.replace(/,/g, '.');
    
    if (isNaN(gia) || gia < 0) {
        alert("Giá phải là số dương");
        document.frm.gia.focus();
        return false;
    }
    
    if (khuyenmai1 != "") {
        if (isNaN(khuyenmai1) || khuyenmai1 < 0 || khuyenmai1 > 100) {
            alert("Phần trăm giảm giá phải là số từ 0-100");
            document.frm.khuyenmai1.focus();
            return false;
        }
    }
    
    if (madm == "") {
        alert("Bạn chưa chọn danh mục");    
        document.frm.madm.focus();
        return false;
    }
    
    return true;
}
</script>

</body>
</html>