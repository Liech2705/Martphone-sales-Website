<?php
include './include/connect.php'; // Kết nối MySQLi, trả về $conn
$conn->set_charset('utf8');

// Truy vấn danh mục
$sql_dm = "SELECT * FROM danhmuc WHERE dequi = 1 ORDER BY madm";
$result_dm = mysqli_query($conn, $sql_dm);

if ($result_dm && mysqli_num_rows($result_dm) > 0) {
    while ($row = mysqli_fetch_assoc($result_dm)) {
        $madm = (int)$row['madm'];
        $tendm = htmlspecialchars($row['tendm']);

        // Truy vấn sản phẩm theo danh mục
        $sql_sp = "SELECT * FROM sanpham WHERE madm = $madm ORDER BY idsp DESC LIMIT 6";
        $result_sp = mysqli_query($conn, $sql_sp);
        $products = [];

        if ($result_sp && mysqli_num_rows($result_sp) > 0) {
            while ($product = mysqli_fetch_assoc($result_sp)) {
                $products[] = $product;
            }
        }

        if (count($products) > 0):
?>
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0"><?= $tendm ?></h2>
        <a href="index.php?madm=<?= $madm ?>" class="text-decoration-none">Xem thêm »</a>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php foreach ($products as $product): 
            $idsp = (int)$product['idsp'];
            $tensp = htmlspecialchars($product['tensp']);
            $hinhanh = htmlspecialchars($product['hinhanh']);
            $khuyenmai1 = (int)$product['khuyenmai1'];
            $gia = (int)$product['gia'];
            $giaKM = $khuyenmai1 > 0 ? $gia * ((100 - $khuyenmai1) / 100) : $gia;
        ?>
        <div class="col">
            <div class="card h-100 position-relative shadow-sm">
                <?php if ($khuyenmai1 > 0): ?>
                    <div class="position-absolute top-0 start-0 badge bg-danger">
                        -<?= $khuyenmai1 ?>%
                    </div>
                <?php endif; ?>

                <a href="index.php?content=chitietsp&idsp=<?= $idsp ?>">
                    <img src="img/uploads/<?= $hinhanh ?>" class="card-img-top" alt="<?= $tensp ?>">
                </a>

                <div class="card-body d-flex flex-column">
                    <h6 class="card-title">
                        <a href="index.php?content=chitietsp&idsp=<?= $idsp ?>" class="text-decoration-none text-dark"><?= $tensp ?></a>
                    </h6>
                    <div class="mb-3">
                        <?php if ($khuyenmai1 > 0): ?>
                            <p class="text-muted mb-0"><s><?= number_format($gia, 0, ',', '.') ?> ₫</s></p>
                        <?php endif; ?>
                        <p class="text-danger fw-bold"><?= number_format($giaKM, 0, ',', '.') ?> ₫</p>
                    </div>

                    <div class="mt-auto d-flex justify-content-between flex-column">
                        <a class="btn btn-outline-primary btn-sm mb-2" href="index.php?content=chitietsp&idsp=<?= $idsp ?>">Chi tiết</a>
                        <a class="btn btn-outline-success btn-sm" href="index.php?content=cart&action=add&idsp=<?= $idsp ?>">Cho vào giỏ</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php
        endif;
    }
} else {
    echo "<div class='alert alert-warning container my-5'>Không có danh mục nào phù hợp.</div>";
}
?>