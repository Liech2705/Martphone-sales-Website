<?php
require __DIR__ . '/../include/connect.php';

// Xử lý cập nhật xoá sản phẩm (nếu có)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idsp = (int)($_GET['idsp'] ?? 0);
    if (isset($_POST['sl'])) {
        // Cập nhật số lượng
        $sl = max(1, (int)$_POST['sl']);
        $_SESSION['cart'][$idsp] = $sl;
    }
    if (isset($_POST['huy'])) {
        // Xóa 1 sản phẩm
        unset($_SESSION['cart'][$idsp]);
    }
    header('Location: index.php');
    exit;
}

// Xóa toàn bộ giỏ hàng
if (isset($_GET['action']) && $_GET['action'] === 'xoa') {
    unset($_SESSION['cart']);
    header('Location: index.php');
    exit;
}

// Tổng tiền ban đầu = 0
$total = 0;

// Lấy danh sách IDSP trong giỏ
$ids = array_filter(array_keys($_SESSION['cart'] ?? []), function($id){
    return ($_SESSION['cart'][$id] ?? 0) > 0;
});
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bán Điện Thoại</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
  /* Overlay tối mờ */
  .modal-backdrop.show {
    background-color: rgba(0, 0, 0, 0.5);
  }

  /* Modal custom */
  #loginModal .modal-content {
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
  }
  #loginModal .modal-header {
    background: linear-gradient(45deg, #4b6cb7, #182848);
    border-bottom: none;
  }
  #loginModal .modal-title {
    color: #fff;
    font-weight: 600;
  }
  /* Tabs in header */
  #loginModal .nav-tabs {
    background: transparent;
    border-bottom: none;
  }
  #loginModal .nav-tabs .nav-link {
    color: #fff;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 2rem;
    margin-left: 0.5rem;
    transition: all 0.3s;
    opacity: 0.8;
  }
  #loginModal .nav-tabs .nav-link.active {
    background: rgba(255,255,255,0.2);
    color: #fff;
    opacity: 1;
  }
  .form-label {
    font-weight: 500;
    color: #333;
  }
  .btn-primary {
    background: #4b6cb7;
    border: none;
    border-radius: 2rem;
    padding: 0.5rem 1.75rem;
    transition: background 0.3s;
  }
  .btn-primary:hover {
    background: #3a539b;
  }
  .btn-success {
    border-radius: 2rem;
  }
  .form-control, .form-select, textarea {
    border-radius: 0.5rem;
    padding: 0.75rem;
  }
  /* Responsive */
  @media (max-width: 576px) {
    #loginModal .modal-dialog {
      margin: 1rem;
    }
    #loginModal .nav-tabs .nav-link {
      padding: 0.4rem 0.8rem;
      font-size: 0.85rem;
      margin-left: 0.25rem;
    }
  }
</style>
</head>
<body>
<header class="container-fluid bg-light py-3 border-bottom">
	<!-- Header chuyển thành navbar -->
	<nav class="container navbar navbar-expand-lg navbar-light bg-light border-bottom">
		<div class="container-fluid">

			<!-- Logo -->
			<a class="navbar-brand" href="../index.php">MOBISTORE</a>

			<!-- Nút toggle cho mobile -->
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
				<span class="navbar-toggler-icon"></span>
			</button>

			<!-- Nội dung nav -->
			<div class="collapse navbar-collapse" id="mainNavbar">

				<!-- Menu ngang -->
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item">
                        <a class="nav-link" href="../index.php">TRANG CHỦ</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="mainMenuDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-grid-fill"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="mainMenuDropdown">
                            <li><a class="dropdown-item" href="index.php?content=gioithieu">GIỚI THIỆU</a></li>
                            <li><a class="dropdown-item" href="index.php?content=sanpham">SẢN PHẨM</a></li>
                            <li><a class="dropdown-item" href="index.php?content=phukien">PHỤ KIỆN</a></li>
                            <li><a class="dropdown-item" href="index.php?content=khuyenmai">KHUYẾN MÃI</a></li>
                            <li><a class="dropdown-item" href="index.php?content=tintuc">TIN TỨC</a></li>
                            <li><a class="dropdown-item" href="index.php?content=hotro">HỖ TRỢ</a></li>
                        </ul>
                    </li>
				</ul>

				<!-- Form tìm kiếm -->
				<form class="d-flex flex-grow-1 me-3" action="../index.php" method="get" role="search" style="max-width: 500px;">
					<input type="hidden" name="content" value="timkiem">

					<input 
						class="form-control form-control-sm me-2 w-100" 
						type="search" 
						name="timkiem" 
						placeholder="Tìm sản phẩm..." 
						aria-label="Search">

					<select name="gia" class="form-select form-select-sm me-2" style="width: 180px;">
						<option value="">Giá</option>
						<option value="1">Dưới 1 triệu</option>
						<option value="2">1 - 3 triệu</option>
						<option value="3">3 - 5 triệu</option>
						<option value="4">5 - 8 triệu</option>
						<option value="5">8 - 10 triệu</option>
						<option value="6">Trên 10 triệu</option>
					</select>

					<button class="btn btn-sm btn-primary" name="btntk" type="submit">
						<i class="bi bi-search"></i>
					</button>
				</form>

				<!-- Tài khoản + Giỏ hàng -->
                <div class="d-flex align-items-center gap-3 flex-wrap justify-content-end">

                <!-- Giỏ hàng -->
                <a href="./cart/index.php" class="btn btn-sm btn-outline-primary position-relative d-flex align-items-center">
                    <i class="bi bi-cart-fill fs-5 me-1"></i> 
                    <span>Giỏ hàng</span>
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= count($_SESSION['cart']) ?>
                        </span>
                    <?php endif; ?>
                </a>

                <!-- Tài khoản -->
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle fs-5 text-secondary"></i>
                        <span class="text-nowrap">Xin chào, <strong><?= $_SESSION['user'] ?></strong></span>
                        <a href="../logout.php" class="btn btn-sm btn-outline-danger">Đăng xuất</a>
                    </div>
                <?php else: ?>
                    <button class="btn btn-sm btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
                    </button>
                <?php endif; ?>
                </div>
			</div>
		</div>
	</nav>
</header>
<div class="container py-4">
    <h3 class="mb-4"><i class="bi bi-cart3 text-primary me-2"></i>Giỏ hàng của bạn</h3>

    <?php
    include('../include/connect.php');
    $cart = $_SESSION['cart'] ?? [];
    $ids = array_filter(array_keys($cart), fn($id) => $cart[$id] > 0);
    $tongtien = 0;

    if (empty($ids)): ?>
        <div class="alert alert-info">Giỏ hàng của bạn chưa có sản phẩm nào.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="text-start">Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Lấy thông tin SP trong giỏ
                $in  = implode(',', $ids);
                $sql = "SELECT * FROM sanpham WHERE idsp IN ($in)";
                $res = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($res)):
                    $idsp     = $row['idsp'];
                    $qty      = $cart[$idsp];
                    $orig     = $row['gia'];
                    $disc     = $row['khuyenmai1'];
                    $price    = $orig * (100 - $disc) / 100;
                    $subtotal = $price * $qty;
                    $tongtien += $subtotal;
                ?>
                    <tr>
                        <!-- Ảnh + Tên -->
                        <td class="text-start">
                            <div class="d-flex align-items-center">
                                <img src="../img/uploads/<?= htmlspecialchars($row['hinhanh']) ?>"
                                     alt="SP" class="me-3" style="width:64px; height:64px; object-fit:cover; border-radius:.5rem;">
                                <a href="../index.php?content=chitietsp&idsp=<?= $idsp ?>"
                                   class="text-dark text-decoration-none fw-semibold">
                                    <?= htmlspecialchars($row['tensp']) ?>
                                </a>
                            </div>
                        </td>
                        <!-- Giá -->
                        <td class="text-danger fw-bold">
                            <?= number_format($price,0,',','.') ?>₫
                        </td>
                        <!-- Số lượng -->
                        <td style="width:120px;">
                        <input type="number"
                                name="sl"
                                value="<?= $qty ?>"
                                min="1"
                                class="form-control form-control-sm quantity-input"
                                data-id="<?= $idsp ?>">
                        </td>
                        <!-- Thành tiền -->
                        <td class="text-danger fw-bold">
                            <?= number_format($subtotal,0,',','.') ?>₫
                        </td>
                        <!-- Xóa -->
                        <td>
                            <form action="index.php?action=update&idsp=<?= $idsp ?>" method="POST">
                                <button type="submit" name="huy" value="1"
                                        class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Tổng & Hành động -->
        <div class="row mt-4 gx-3">
            <div class="col-md-6 mb-2">
                <a href="../index.php?content=cart&action=xoa"
                   class="btn btn-outline-danger w-100">
                    <i class="bi bi-trash3 me-1"></i> Xóa toàn bộ giỏ hàng
                </a>
            </div>
            <div class="col-md-6 text-md-end mb-2">
                <h5 class="text-danger">Tổng cộng: 
                    <strong><?= number_format($tongtien,0,',','.') ?>₫</strong>
                </h5>
            </div>
            <div class="col-md-6 mb-2">
                <a href="../index.php"
                   class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left me-1"></i> Tiếp tục mua hàng
                </a>
            </div>
            <div class="col-md-6 mb-2">
                <a href="../index.php?content=cart&action=check"
                   class="btn btn-success w-100">
                    <i class="bi bi-cash-coin me-1"></i> Thanh toán
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>


<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header d-flex flex-column align-items-start py-3">
        <div class="d-flex w-100 justify-content-between align-items-center">
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>
        <ul class="nav nav-tabs mt-3" id="authTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#loginTabPane" type="button" role="tab">Đăng nhập</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#registerTabPane" type="button" role="tab">Đăng ký</button>
          </li>
        </ul>
      </div>
      <div class="modal-body px-4">
        <div class="tab-content">
          <!-- Đăng nhập -->
          <div class="tab-pane fade show active" id="loginTabPane" role="tabpanel">
            <form method="POST" action="../kiemtra_dangnhap.php">
              <?php if (isset($_SESSION['thongbaolo'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <?= htmlspecialchars($_SESSION['thongbaolo']); unset($_SESSION['thongbaolo']); ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
              <?php endif; ?>
              <div class="mb-3">
                <label for="user" class="form-label">Tên đăng nhập</label>
                <input type="text" class="form-control" id="user" name="user" placeholder="Nhập tên đăng nhập" required>
              </div>
              <div class="mb-3">
                <label for="pass" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="pass" name="pass" placeholder="Nhập mật khẩu" required>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <button type="submit" name="login" class="btn btn-primary">Đăng nhập</button>
              </div>
            </form>
          </div>

          <!-- Đăng ký -->
          <div class="tab-pane fade" id="registerTabPane" role="tabpanel">
            <form action="../update_dangky.php" method="post" name="frm" onsubmit="return dangky()">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Tên đăng nhập *</label>
                  <input type="text" name="user" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Tên người dùng *</label>
                  <input type="text" name="tennd" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Mật khẩu *</label>
                  <input type="password" name="pass" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Xác nhận mật khẩu *</label>
                  <input type="password" name="pass1" class="form-control" placeholder="Nhập lại mật khẩu" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Email *</label>
                  <input type="email" name="email" class="form-control" placeholder="example@mail.com" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Điện thoại *</label>
                  <input type="text" name="dienthoai" class="form-control" placeholder="10-11 chữ số" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Ngày sinh</label>
                  <input type="date" name="ngaysinh" class="form-control">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Giới tính</label>
                  <select name="gioitinh" class="form-select">
                    <option value="">-Chọn-</option>
                    <option value="nam">Nam</option>
                    <option value="nu">Nữ</option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label">Địa chỉ</label>
                  <textarea name="diachi" class="form-control" rows="2" placeholder="Nhập địa chỉ"></textarea>
                </div>
              </div>
              <div class="mt-4 d-flex justify-content-end gap-2">
                <button type="reset" class="btn btn-secondary">Hủy</button>
                <button type="submit" name="submit" class="btn btn-success">Đăng ký</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function dangky() {
    const frm = document.forms['frm'];

    if (frm.tennd.value.length < 6) {
        alert("Tên người dùng phải có ít nhất 6 ký tự");
        frm.tennd.focus(); return false;
    }
    if (frm.user.value.length < 6) {
        alert("Tên đăng nhập phải có ít nhất 6 ký tự");
        frm.user.focus(); return false;
    }

    let userTaken = false;

    // Gọi AJAX để kiểm tra tên đăng nhập
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../kiemtra_user.php", false); // false = đồng bộ
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.responseText.trim() === 'tontai') {
            alert("Tên đăng nhập đã tồn tại");
            frm.user.focus();
            userTaken = true;
        }
    };
    xhr.send("user=" + encodeURIComponent(frm.user.value));
    if (userTaken) return false;

    if (frm.pass.value.length < 6) {
        alert("Mật khẩu phải có ít nhất 6 ký tự");
        frm.pass.focus(); return false;
    }
    if (frm.pass.value !== frm.pass1.value) {
        alert("Mật khẩu không khớp"); frm.pass1.focus(); return false;
    }
    if (!/^[0-9]{10,11}$/.test(frm.dienthoai.value)) {
        alert("Số điện thoại không hợp lệ"); frm.dienthoai.focus(); return false;
    }
    const emailPattern = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
    if (!emailPattern.test(frm.email.value)) {
        alert("Email không hợp lệ"); frm.email.focus(); return false;
    }

    return true;
}

</script>
<?php $_SESSION['tongtien'] = $tongtien;?>
<script>
$(document).ready(function () {
    $('.quantity-input').on('change', function () {
        const idsp = $(this).data('id');
        const newQty = $(this).val();

        $.post('../index.php?content=cart&action=update&idsp=' + idsp, {
            sl: newQty
        }, function (response) {
            // Sau khi cập nhật thành công, reload lại viewcart qua AJAX (hoặc cập nhật từng phần nếu muốn)
            location.reload(); // hoặc bạn có thể update DOM theo response
        });
    });
});
</script>
</body>
</html>


