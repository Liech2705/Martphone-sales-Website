<?php 
    session_start();
    if (!isset($_SESSION['username']) || ($_SESSION['phanquyen'] == 1)) {
        header('location:login.php');
        exit();
    }

    // Get the current admin parameter to determine active menu item
    $currentAdmin = isset($_GET['admin']) ? $_GET['admin'] : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script language="javascript" src="ckeditor/ckeditor.js"></script>
    <title>Mobistore</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome và Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <style>
        .sidebar {
            width: 250px;
            height: 100vh; /* Chiếm toàn bộ chiều cao viewport */
            overflow-y: auto;
            position: fixed; /* Đặt fixed để sidebar cố định */
            top: 0;
            left: 0;
            z-index: 1000; /* Đảm bảo sidebar nằm trên nội dung khác */
        }
        .offcanvas-body {
            overflow-y: auto;
        }
        .nav-link {
            transition: background-color 0.3s;
        }
        .nav-link.active {
            background-color: #6c757d !important;
            color: #fff !important;
        }
        .nav-link:hover {
            background-color: #495057;
            color: #fff;
        }
        .navbar-brand img {
            height: 40px;
        }
        .content-area {
            flex-grow: 1;
            margin-left: 250px; /* Đẩy nội dung sang phải để tránh chồng lấn sidebar */
            min-height: 100vh; /* Đảm bảo nội dung cũng full chiều cao */
            padding: 20px;
        }
        @media (max-width: 991px) {
            .sidebar {
                display: none; /* Ẩn sidebar trên mobile */
            }
            .content-area {
                margin-left: 0; /* Xóa margin trên mobile */
            }
        }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex flex-column min-vh-100">
        <!-- Main Content -->
        <div class="d-flex flex-grow-1">
            <div class="bg-dark text-white d-none d-lg-block sidebar">
                <div class="">
                    <h3 class="text-white mb-4 text-center">ADMIN</h3>
                    <p class="text-white text-center mb-4">Chào bạn: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
                    <nav class="nav flex-column">
                        <a class="nav-link text-white <?php echo ($currentAdmin == '' && basename($_SERVER['PHP_SELF']) == 'admin.php') ? 'active' : ''; ?>" href="admin.php">
                            <i class="fas fa-tachometer-alt me-2"></i> Trang chủ
                        </a>
                        <a class="nav-link text-white <?php echo $currentAdmin == 'hienthisp' ? 'active' : ''; ?>" href="?admin=hienthisp">
                            <i class="fas fa-mobile-alt me-2"></i> Quản lý sản phẩm
                        </a>
                        <a class="nav-link text-white <?php echo $currentAdmin == 'hienthidm' ? 'active' : ''; ?>" href="?admin=hienthidm">
                            <i class="fas fa-list me-2"></i> Quản lý danh mục
                        </a>
                        <a class="nav-link text-white <?php echo $currentAdmin == 'hienthihd' ? 'active' : ''; ?>" href="?admin=hienthihd">
                            <i class="fas fa-file-invoice me-2"></i> Quản lý hóa đơn
                        </a>
                        <a class="nav-link text-white <?php echo $currentAdmin == 'hienthind' ? 'active' : ''; ?>" href="?admin=hienthind">
                            <i class="fas fa-users me-2"></i> Quản lý người dùng
                        </a>
                        <a class="nav-link text-white <?php echo $currentAdmin == 'hienthitt' ? 'active' : ''; ?>" href="?admin=hienthitt">
                            <i class="fas fa-newspaper me-2"></i> Quản lý tin tức
                        </a>
                        <a class="nav-link text-white <?php echo $currentAdmin == 'hienthiht' ? 'active' : ''; ?>" href="?admin=hienthiht">
                            <i class="fas fa-headset me-2"></i> Quản lý hỗ trợ
                        </a>
                        <a class="nav-link text-white" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                        </a>
                    </nav>
                </div>
            </div>

          

            <!-- Main Content Area -->
            <div class="content-area p-3 bg-white border-start">
                <div class="container-fluid">
                    <?php
                        include("content_admin.php");
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>