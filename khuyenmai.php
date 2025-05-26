<?php
include './include/connect.php'; // Kết nối MySQLi, trả về $conn
$conn->set_charset('utf8');

$sql = "SELECT * 
        FROM sanpham 
        WHERE khuyenmai1 > 0 
           OR (khuyenmai2 IS NOT NULL AND khuyenmai2 != '')";
$result = $conn->query($sql);

if (!$result) {
    exit('<div class="alert alert-danger">Lỗi truy vấn: ' . $conn->error . '</div>');
}

$products = $result->fetch_all(MYSQLI_ASSOC);
$total = count($products);
?>

<div class="container my-4">
    <h3 class="text-center text-success mb-3">SẢN PHẨM KHUYẾN MÃI</h3>
    
    <?php if ($total === 0): ?>
        <div class="alert alert-warning text-center">Không có sản phẩm khuyến mãi nào</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>Tên sản phẩm</th>
                        <th>Giảm giá</th>
                        <th>Khuyến mãi</th>
                        <th>Giá KM</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $idx = 1; ?>
                    <?php foreach ($products as $row): 
                        $giaGoc = (int)$row['gia'];
                        $giamGia = (int)$row['khuyenmai1'];
                        $giaKM = $giamGia > 0 ? $giaGoc * ((100 - $giamGia) / 100) : $giaGoc;
                    ?>
                        <tr>
                            <td><?= $idx++; ?></td>
                            <td class="text-start">
                                <a class="text-decoration-none" href="index.php?content=chitietsp&idsp=<?= (int)$row['idsp'] ?>">
                                    <?= htmlspecialchars($row['tensp']) ?>
                                </a>
                            </td>
                            <td><span class="badge bg-danger"><?= $giamGia ?>%</span></td>
                            <td><?= htmlspecialchars($row['khuyenmai2'] ?: 'Không có') ?></td>
                            <td class="text-danger fw-bold"><?= number_format($giaKM, 0, ",", ".") ?> VNĐ</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php
$result->free();
$conn->close();
?>
