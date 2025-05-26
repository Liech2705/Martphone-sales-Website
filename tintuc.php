<?php
include './include/connect.php';
$conn->set_charset('utf8');

// Phân trang
$max_results = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$from = ($page - 1) * $max_results;
?>

<div class="container my-4">
    <h3 class="text-center text-primary mb-4">Tin Tức</h3>
    <div class="row g-4">
        <?php
        // Lấy danh sách tin tức
        $sql = "SELECT * FROM tintuc ORDER BY matt DESC LIMIT ?, ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $from, $max_results);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()):
        ?>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <img src="img/tintuc/<?= htmlspecialchars($row['hinhanh']) ?>" 
                         class="card-img-top" alt="<?= htmlspecialchars($row['tieude']) ?>" 
                         style="object-fit: cover; height: 300px;">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="index.php?content=chitiettintuc&matt=<?= $row['matt'] ?>" 
                               class="text-decoration-none text-dark">
                                <?= htmlspecialchars($row['tieude']) ?>
                            </a>
                        </h5>
                        <p class="card-text"><?= htmlspecialchars($row['ndngan']) ?></p>
                    </div>
                    <div class="card-footer bg-white">
                        <small class="text-muted">Ngày đăng: <?= htmlspecialchars($row['ngaydangtin']) ?></small>
                        <a href="index.php?content=chitiettintuc&matt=<?= $row['matt'] ?>" 
                           class="btn btn-outline-primary btn-sm float-end">
                            Xem thêm &gt;&gt;
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; $stmt->close(); ?>
    </div>

    <!-- PHÂN TRANG -->
    <?php
    // Tổng số bài viết
    $count_sql = "SELECT COUNT(*) AS total FROM tintuc";
    $count_res = $conn->query($count_sql);
    $total = (int)$count_res->fetch_assoc()['total'];
    $total_pages = ceil($total / $max_results);
    ?>

    <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?content=tintuc&page=<?= $page - 1 ?>">Trang trước</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?content=tintuc&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?content=tintuc&page=<?= $page + 1 ?>">Trang sau</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<?php $conn->close(); ?>
