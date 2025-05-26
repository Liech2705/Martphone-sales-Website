<?php
include 'include/connect.php';

// Lấy danh mục có dequi = 2
$sql = "SELECT * FROM danhmuc WHERE dequi = 2 ORDER BY madm";
$result_dm = mysqli_query($conn, $sql);

if ($result_dm && mysqli_num_rows($result_dm) > 0) {
    while ($row = mysqli_fetch_assoc($result_dm)) {
        $madm = (int)$row['madm'];
        $tendm = htmlspecialchars($row['tendm']);

        // Lấy 6 sản phẩm thuộc danh mục
        $sql_sp = "SELECT * FROM sanpham WHERE madm = $madm ORDER BY idsp LIMIT 6";
        $result_sp = mysqli_query($conn, $sql_sp);
        if ($result_sp && mysqli_num_rows($result_sp) > 0) {
?>
        <div class="container my-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0"><?= $tendm ?></h3>
                <a class="btn btn-link" href="index.php?madm=<?= $madm ?>">Xem thêm >></a>
            </div>
            <div class="row g-4">
                <?php while ($product = mysqli_fetch_assoc($result_sp)):
                    $idsp = (int)$product['idsp'];
                    $tensp = htmlspecialchars($product['tensp']);
                    $hinhanh = htmlspecialchars($product['hinhanh']);
                    $gia = (int)$product['gia'];
                    $km = (int)$product['khuyenmai1'];
                    $giaKM = $km > 0 ? $gia * ((100 - $km) / 100) : $gia;
                ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 position-relative">
                            <?php if ($km > 0): ?>
                                <span class="position-absolute top-0 start-0 badge bg-danger rounded-0 px-3 py-2">-<?= $km ?>%</span>
                            <?php endif; ?>
                            <a href="index.php?content=chitietsp&idsp=<?= $idsp ?>">
                                <img src="img/uploads/<?= $hinhanh ?>" class="card-img-top" alt="<?= $tensp ?>" style="height: 180px; object-fit: contain;">
                            </a>
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title text-truncate"><?= $tensp ?></h6>
                                <p class="text-danger fw-bold mb-2"><?= number_format($giaKM, 0, ",", ".") ?> VNĐ</p>
                                <div class="mt-auto d-grid gap-2">
                                    <a href="index.php?content=chitietsp&idsp=<?= $idsp ?>" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                    <a href="index.php?content=cart&action=add&idsp=<?= $idsp ?>" class="btn btn-sm btn-outline-success">Cho vào giỏ</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
<?php
        }
    }
} else {
    echo "<div class='alert alert-warning text-center'>Không có danh mục nào phù hợp.</div>";
}
?>
