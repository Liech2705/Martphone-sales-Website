<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mobistore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>
<?php session_start(); include("include/connect.php"); ?>
<?php include("dangnhap.php"); ?>

<header class="container-fluid bg-light py-3 border-bottom">
	<nav class="container navbar navbar-expand-lg navbar-light bg-light border-bottom">
		<div class="container-fluid">

			<a class="navbar-brand" href="index.php">MOBISTORE</a>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="mainNavbar">

				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item">
                        <a class="nav-link" href="index.php">TRANG CHỦ</a>
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
				<form class="d-flex flex-grow-1 me-3" action="index.php" method="get" role="search" style="max-width: 500px;">
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
                        <a href="logout.php" class="btn btn-sm btn-outline-danger">Đăng xuất</a>
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

<?php if (isset($_GET['login_error'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = new bootstrap.Modal(document.getElementById('loginModal'));
    modal.show();
});
</script>
<?php endif; ?>
<style>.banner-carousel {
    width: 1200px;
    height: 250px;
    overflow: hidden;
    margin: 0 auto; /* căn giữa nếu cần */
}

.banner-carousel img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
<!-- Carousel -->
<div id="mainCarousel" class="carousel slide mx-auto" data-bs-ride="carousel" style="max-width: 1200px;">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="img/slide/1.webp" class="d-block w-100" alt="Slide 1">
        </div>
        <div class="carousel-item">
            <img src="img/slide/2.webp" class="d-block w-100" alt="Slide 2">
        </div>
        <div class="carousel-item">
            <img src="img/slide/3.webp" class="d-block w-100" alt="Slide 3">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- Main Content -->
<main class="container my-4">
    <div class="row g-4">
        <aside class="col-12 col-md-3">
            <?php include("home_include/left_content.php"); ?>
        </aside>

        <section class="col-12 col-md-9">
            <?php include("content_page.php"); ?>
        </section>
    </div>
</main>

<!-- Đối tác -->
<section class="bg-light py-5">
  <div class="container">
    <!-- Tiêu đề -->
    <h4 class="text-center mb-4 fw-bold text-primary">Đối tác chiến lược</h4>
    <!-- Logo đối tác -->
    <div class="row justify-content-center align-items-center gy-4">
      <!-- Mỗi logo trong một cột auto để responsive -->
      <div class="col-6 col-sm-4 col-md-2 text-center">
        <a href="#" class="d-block p-3 bg-white rounded shadow-sm">
          <img src="img/samsung.png" alt="Samsung" class="img-fluid" style="max-height: 60px;">
        </a>
      </div>
      <div class="col-6 col-sm-4 col-md-2 text-center">
        <a href="#" class="d-block p-3 bg-white rounded shadow-sm">
          <img src="img/sony.png" alt="Sony" class="img-fluid" style="max-height: 60px;">
        </a>
      </div>
      <div class="col-6 col-sm-4 col-md-2 text-center">
        <a href="#" class="d-block p-3 bg-white rounded shadow-sm">
          <img src="img/lg.png" alt="LG" class="img-fluid" style="max-height: 60px;">
        </a>
      </div>
      <div class="col-6 col-sm-4 col-md-2 text-center">
        <a href="#" class="d-block p-3 bg-white rounded shadow-sm">
          <img src="img/nokia.png" alt="Nokia" class="img-fluid" style="max-height: 60px;">
        </a>
      </div>
      <!-- Thêm đối tác khác tương tự -->
    </div>
  </div>
</section>


<!-- Footer -->
<footer class="bg-dark text-light pt-5 pb-3">
  <div class="container">
    <div class="row gy-4">
      <!-- Logo & Giới thiệu -->
      <div class="col-12 col-md-4">
        <a href="index.php" class="d-flex align-items-center mb-3 text-decoration-none">
          <i class="bi bi-shop fs-2 text-primary me-2"></i>
          <span class="fs-4 fw-bold">MOBISTORE</span>
        </a>
        <p class="small">
          MOBISTORE – Nơi mang đến cho bạn những mẫu điện thoại và phụ kiện chính hãng, chất lượng cao, giá
          cả cạnh tranh và dịch vụ tận tâm.
        </p>
      </div>

      <!-- Thông tin liên hệ -->
      <div class="col-12 col-md-4">
        <h6 class="text-uppercase text-primary mb-3">Liên hệ</h6>
        <ul class="list-unstyled small">
          <li class="mb-2">
            <i class="bi bi-geo-alt-fill me-2"></i>Hoàng Mai, Hà Nội
          </li>
          <li class="mb-2">
            <i class="bi bi-telephone-fill me-2"></i>0386 922 767
          </li>
          <li class="mb-2">
            <i class="bi bi-envelope-fill me-2"></i>dienthoaiabc1@gmail.com
          </li>
          <li>
            <i class="bi bi-globe2 me-2"></i><a href="#" class="text-light text-decoration-none">www.mobistore.vn</a>
          </li>
        </ul>
      </div>

      <!-- Nhóm & mạng xã hội -->
      <div class="col-12 col-md-4 text-md-end">
        <h6 class="text-uppercase text-primary mb-3">Nhóm dự án</h6>
        <p class="small mb-3">
          VIPPRO – CNTT2211<br>
          nhomvippro@gmail.com
        </p>
        <div>
          <a href="#" class="text-light fs-4 me-3"><i class="bi bi-facebook"></i></a>
          <a href="#" class="text-light fs-4 me-3"><i class="bi bi-instagram"></i></a>
          <a href="#" class="text-light fs-4"><i class="bi bi-youtube"></i></a>
        </div>
      </div>
    </div>

    <hr class="bg-secondary mt-4">

    <div class="row">
      <div class="col text-center small">
        &copy; <?= date('Y') ?> MOBISTORE. All rights reserved.
      </div>
    </div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
