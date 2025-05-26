<script type="text/javascript" src="js/checkbox.js"></script>

<style>
    .search-form {
        width:100%;
        margin-bottom: 10px;
    }
    .search-form {
    width: 100%;
}

.search-form .form-control {
    border-radius: 20px 0 0 20px;
    padding: 10px 15px;
    font-size: 16px;
}

.search-form .btn {
    border-radius: 0 20px 20px 0;
    padding: 10px 20px;
}

</style>

<?php
include ('../include/connect.php');

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$select = "SELECT * FROM sanpham INNER JOIN danhmuc ON sanpham.madm = danhmuc.madm";
if (!empty($search)) {
    $select .= " WHERE sanpham.tensp LIKE '%$search%'";
}
$query = mysqli_query($conn, $select);
$dem = mysqli_num_rows($query);
?>

<link rel="stylesheet" href="/admin/css/style.css">
<div class="container my-4">
    <div class="card shadow">
        <div class="card-header bg-info text-black text-center">
            <h5 class="mb-0">QUẢN LÝ SẢN PHẨM</h5>
        </div>

        <div class="card-body">
            <!-- Search Form -->
            <div class="d-flex justify-content-center my-3">
    <form method="GET" action="" class="search-form">
        <input type="hidden" name="admin" value="hienthisp">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên sản phẩm..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-secondary">Tìm</button>
        </div>
    </form>
</div>


            <form action="admin.php?admin=xulysp" method="post">
                Có tổng <b class="text-danger"><?php echo $dem ?></b> sản phẩm</p>

                <div class="table-responsive">
                    <table class="table table-bordered custom-table">
                        <thead class="text-center">
                            <tr>
                                <th><input type="checkbox" class="checkbox" onclick="checkall('item', this)"></th>
                                <th>IDSP</th>
                                <th>Hình ảnh và Tên SP</th>
                                <th>Số lượng</th>
                                <th>Đã bán</th>
                                <th>Giá</th>
                                <th>Danh mục</th>
                                <th>
                                    <a href='?admin=themsp' class="btn btn-sm btn-success">Thêm sản phẩm</a>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                        <?php
                            // Phân trang
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $max_results = 5;
                            $from = ($page * $max_results) - $max_results;

                            // SQL query for pagination with search
                            $sql = "SELECT * FROM sanpham INNER JOIN danhmuc ON sanpham.madm = danhmuc.madm";
                            if (!empty($search)) {
                                $sql .= " WHERE sanpham.tensp LIKE '%$search%'";
                            }
                            $sql .= " ORDER BY idsp DESC LIMIT $from, $max_results";
                            $result = mysqli_query($conn, $sql);

                            if ($dem > 0) {
                                while ($bien = mysqli_fetch_array($result)) {
                        ?>
                            <tr>
                                <td><input type="checkbox" name="id[]" class="item checkbox" value="<?= $bien['idsp'] ?>"/></td>
                                <td><?= $bien['idsp'] ?></td>
                                <td>
                                    <img src="../img/uploads/<?= $bien['hinhanh'] ?>" width="60" height="60" class="img-thumbnail"><br>
                                    <strong><?= $bien['tensp'] ?></strong>
                                </td>
                                <td><?= $bien['soluong'] ?></td>
                                <td><?= $bien['daban'] ?></td>
                                <td><?= number_format($bien['gia']) . ' VNĐ' ?></td>
                                <td><?= $bien['tendm'] ?></td>
                                <td>
                                    <a href='admin.php?admin=suasp&idsp=<?= $bien['idsp'] ?>' class="btn btn-sm btn-warning" title="Sửa">
                                        <i class="bi bi-pencil-square"></i> Sửa
                                    </a>
                                    <div class="mb-3 mt-2">
                                        <input type="submit" name="xoa" value="Xóa" class="btn btn-sm btn-danger" />
                                    </div>
                                </td>
                            </tr>
                        <?php
                                }
                            } else {
                                echo "<tr><td colspan='8'>Không có sản phẩm trong CSDL</td></tr>";
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </form>

            <!-- Phân trang -->
            <div class="mt-3 text-center">
                <?php
                    // Count total results for pagination
                    $count_sql = "SELECT COUNT(*) as Num FROM sanpham";
                    if (!empty($search)) {
                        $count_sql .= " WHERE tensp LIKE '%$search%'";
                    }
                    $result_count = mysqli_query($conn, $count_sql);
                    $row_count = mysqli_fetch_assoc($result_count);
                    $total_results = $row_count['Num'];
                    $total_pages = ceil($total_results / $max_results);

                    if ($page > 1) {
                        $prev = $page - 1;
                        echo "<a href=\"".$_SERVER['PHP_SELF']."?admin=hienthisp&page=$prev&search=".urlencode($search)."\" class='btn btn-sm btn-secondary'>Trang trước</a> ";
                    }

                    for ($i = 1; $i <= $total_pages; $i++) {
                        if ($page == $i) {
                            echo "<span class='btn btn-sm btn-primary disabled'>$i</span> ";
                        } else {
                            echo "<a href=\"".$_SERVER['PHP_SELF']."?admin=hienthisp&page=$i&search=".urlencode($search)."\" class='btn btn-sm btn-outline-primary'>$i</a> ";
                        }
                    }

                    if ($page < $total_pages) {
                        $next = $page + 1;
                        echo "<a href=\"".$_SERVER['PHP_SELF']."?admin=hienthisp&page=$next&search=".urlencode($search)."\" class='btn btn-sm btn-secondary'>Trang sau</a>";
                    }
                ?>
            </div>
        </div>
    </div>
</div>