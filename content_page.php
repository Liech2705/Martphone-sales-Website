<?php
require __DIR__ . '/include/connect.php';

if (!empty($_GET['timkiem'])) {
    include("timkiem.php"); // Nếu có từ khóa tìm kiếm, hiển thị kết quả
    return; // Dừng xử lý phần sau
}

$action = $_GET['action'] ?? '';
$content = $_GET['content'] ?? '';

if (!empty($content)) {
    switch ($content) {
        case "gioithieu": include('gioithieu.php'); break;
        case "timkiem": include('timkiem.php'); break;
        case "dangky": include('dangky.php'); break;
        case "dangnhap": include('dangnhap.php'); break;
        case "chitietsp": include('chitietsp.php'); break;
        case "cart": include('cart/index.php'); break;
        case "hotro": include('hotro.php'); break;
        case "sanpham": include('sanpham.php'); break;
        case "phukien": include('phukien.php'); break;
        case "tintuc": include('tintuc.php'); break;
        case "chitiettintuc": include('chitiettintuc.php'); break;
        case "hethang": include('hethang.php'); break;
        case "khuyenmai": include('khuyenmai.php'); break;
        default:
            include("404.html");
            break;
    }
} elseif (isset($_GET['madm'])) {
    $madm = mysqli_real_escape_string($conn, $_GET['madm']);
    $sql = "SELECT * FROM sanpham WHERE madm = '$madm'";
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $max_results = 12;
    $from = ($page * $max_results) - $max_results;

    $sql_paginated = "$sql LIMIT $from, $max_results";
    $query = mysqli_query($conn, $sql_paginated);
    $total = mysqli_num_rows($query);

    if ($total > 0) {
        $sql1 = "SELECT tendm FROM danhmuc WHERE madm = '$madm'";
        $query1 = mysqli_query($conn, $sql1);
        $row = mysqli_fetch_assoc($query1);
        echo "<div class='container my-4'><h2 class='mb-4'>" . htmlspecialchars($row['tendm']) . "</h2><div class='row row-cols-1 row-cols-md-4 g-4'>";
        while ($result = mysqli_fetch_assoc($query)) {
            $discountPrice = $result['gia'] * ((100 - $result['khuyenmai1']) / 100);
            echo "<div class='col'><div class='card h-100'>";
            if ($result['khuyenmai1'] > 0) {
                echo "<div class='position-absolute top-0 start-0 badge bg-danger'>-{$result['khuyenmai1']}%</div>";
            }
            echo "<a href='index.php?content=chitietsp&idsp={$result['idsp']}'><img src='img/uploads/" . htmlspecialchars($result['hinhanh']) . "' class='card-img-top' alt='...'></a>";
            echo "<div class='card-body'><h5 class='card-title'><a class='text-black text-decoration-none' href='index.php?content=chitietsp&idsp={$result['idsp']}'>" . htmlspecialchars($result['tensp']) . "</a></h5>";
            echo "<p class='card-text text-danger fw-bold'>" . number_format($discountPrice, 0, ',', '.') . " VNĐ</p></div>";
            echo "<div class='card-footer d-flex justify-content-between flex-column'><a href='index.php?content=chitietsp&idsp={$result['idsp']}' class='btn btn-outline-primary btn-sm mb-2'>Chi tiết</a>";
            echo "<a href='index.php?content=cart&action=add&idsp={$result['idsp']}' class='btn btn-outline-success btn-sm'>Cho vào giỏ</a></div></div></div>";
        }
        echo "</div></div>";

        // Pagination
        $total_results = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as Num FROM sanpham WHERE madm = '$madm'"))['Num'];
        $total_pages = ceil($total_results / $max_results);

        echo "<nav><ul class='pagination justify-content-center mt-4'>";
        if ($page > 1) {
            $prev = $page - 1;
            echo "<li class='page-item'><a class='page-link' href='?madm=$madm&page=$prev'>&laquo;</a></li>";
        }
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = $i == $page ? 'active' : '';
            echo "<li class='page-item $active'><a class='page-link' href='?madm=$madm&page=$i'>$i</a></li>";
        }
        if ($page < $total_pages) {
            $next = $page + 1;
            echo "<li class='page-item'><a class='page-link' href='?madm=$madm&page=$next'>&raquo;</a></li>";
        }
        echo "</ul></nav>";
    } else {
        echo "<div class='alert alert-info container mt-4'>Không có sản phẩm nào trong danh mục này</div>";
    }
} else {
    // Sản phẩm bán chạy và sản phẩm mới
    $titles = ['ĐIỆN THOẠI BÁN CHẠY' => 'daban DESC', 'ĐIỆN THOẠI MỚI' => 'idsp DESC'];
    foreach ($titles as $title => $order) {
        $sql = "SELECT * FROM sanpham INNER JOIN danhmuc ON sanpham.madm = danhmuc.madm WHERE dequi = 1 ORDER BY $order LIMIT 6";
        $result = mysqli_query($conn, $sql);
        echo "<div class='container my-4'><h2 class='mb-4'>$title</h2><div class='row row-cols-1 row-cols-md-4 g-4'>";
        while ($row = mysqli_fetch_assoc($result)) {
            $discountPrice = $row['gia'] * ((100 - $row['khuyenmai1']) / 100);
            echo "<div class='col'><div class='card h-100'>";
            if ($row['khuyenmai1'] > 0) {
                echo "<div class='position-absolute top-0 start-0 badge bg-danger'>-{$row['khuyenmai1']}%</div>";
            }
            echo "<a href='index.php?content=chitietsp&idsp={$row['idsp']}'><img src='img/uploads/" . htmlspecialchars($row['hinhanh']) . "' class='card-img-top' alt='...'></a>";
            echo "<div class='card-body'><h5 class='card-title'><a class='text-decoration-none text-black' href='index.php?content=chitietsp&idsp={$row['idsp']}'>" . htmlspecialchars($row['tensp']) . "</a></h5>";
            echo "<p class='card-text text-danger fw-bold'>" . number_format($discountPrice, 0, ',', '.') . " VNĐ</p></div>";
            echo "<div class='card-footer d-flex justify-content-between flex-column'><a href='index.php?content=chitietsp&idsp={$row['idsp']}' class='btn btn-outline-primary btn-sm mb-2'>Chi tiết</a>";
            echo "<a href='index.php?content=cart&action=add&idsp={$row['idsp']}' class='btn btn-outline-success btn-sm'>Cho vào giỏ</a></div></div></div>";
        }
        echo "</div></div>";
    }
}
?>