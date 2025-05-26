<?php
if (isset($_GET['timkiem'])) {
    include './include/connect.php'; // Kết nối MySQLi, trả về $conn

    $tim = mysqli_real_escape_string($conn, $_GET['timkiem']);
    $gia = $_GET['gia'] ?? '';

    // Câu truy vấn cơ bản
    $sql = "SELECT * FROM sanpham WHERE tensp LIKE '%$tim%'";

    // Thêm điều kiện theo giá nếu có
    switch ($gia) {
        case "1":
            $sql .= " AND gia BETWEEN 0 AND 1000000";
            break;
        case "2":
            $sql .= " AND gia BETWEEN 1000000 AND 3000000";
            break;
        case "3":
            $sql .= " AND gia BETWEEN 3000000 AND 5000000";
            break;
        case "4":
            $sql .= " AND gia BETWEEN 5000000 AND 8000000";
            break;
        case "5":
            $sql .= " AND gia BETWEEN 8000000 AND 10000000";
            break;
        case "6":
            $sql .= " AND gia >= 10000000";
            break;
    }

    // Thực thi truy vấn
    $result = mysqli_query($conn, $sql);
    $tong = mysqli_num_rows($result);

    if ($tong == 0) {
        echo "<p>Không tìm được sản phẩm nào</p>";
    } else {
?>
        <div class="container my-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">
                    Từ khóa <span class="text-warning fw-bold"><?= htmlspecialchars($tim) ?></span>: có <?= $tong ?> kết quả
                </h2>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                <?php while ($row = mysqli_fetch_assoc($result)) {
                    $idsp = (int)$row['idsp'];
                    $tensp = htmlspecialchars($row['tensp']);
                    $hinhanh = htmlspecialchars($row['hinhanh']);
                    $khuyenmai1 = (int)$row['khuyenmai1'];
                    $gia = (int)$row['gia'];
                    $giaKM = $khuyenmai1 > 0 ? $gia * ((100 - $khuyenmai1) / 100) : $gia;
                ?>
                <div class="col">
                    <div class="card h-100 position-relative shadow-sm">
                        <?php if ($khuyenmai1 > 0): ?>
                            <div class="position-absolute top-0 start-0 badge bg-danger m-2">
                                -<?= $khuyenmai1 ?>%
                            </div>
                        <?php endif; ?>

                        <a href="index.php?content=chitietsp&idsp=<?= $idsp ?>">
                            <img src="img/uploads/<?= $hinhanh ?>" class="card-img-top img-fluid" alt="<?= $tensp ?>">
                        </a>

                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title text-truncate" title="<?= $tensp ?>">
                                <a href="index.php?content=chitietsp&idsp=<?= $idsp ?>" class="text-decoration-none text-dark"><?= $tensp ?></a>
                            </h6>
                            <p class="text-danger fw-bold mb-3"><?= number_format($giaKM, 0, ',', '.') ?> ₫</p>

                            <div class="mt-auto d-flex flex-column">
                                <a class="btn btn-outline-primary btn-sm mb-2" href="index.php?content=chitietsp&idsp=<?= $idsp ?>">Chi tiết</a>
                                <a class="btn btn-outline-success btn-sm" href="index.php?content=cart&action=add&idsp=<?= $idsp ?>">Cho vào giỏ</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>


<?php
    }
}
?>
