<?php
    ob_start(); // Bật bộ đệm đầu ra
    include '../include/connect.php';

    // Khởi tạo mảng lỗi
    $errors = [];
    $success = "";

    // Phân trang
    if (!isset($_GET['page'])) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }
    $max_results = 10;
    $from = (($page * $max_results) - $max_results);

    $hienthi = mysqli_query($conn, "SELECT * FROM danhmuc LIMIT $from, $max_results");
    if (!$hienthi) {
        $errors[] = "Lỗi truy vấn: " . mysqli_error($conn);
    }

    // Xử lý xóa danh mục
    if (isset($_GET['delete_id'])) {
        $delete_id = (int)$_GET['delete_id'];

        // Kiểm tra danh mục có sản phẩm
        $sql_check_products = "SELECT COUNT(*) as count FROM sanpham WHERE madm = ?";
        if ($stmt_check = $conn->prepare($sql_check_products)) {
            $stmt_check->bind_param("i", $delete_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
            if ($result_check->fetch_assoc()['count'] > 0) {
                $errors[] = "Danh mục đang được sử dụng bởi sản phẩm, không thể xóa";
            }
            $stmt_check->close();
        } else {
            $errors[] = "Lỗi kiểm tra sản phẩm: " . $conn->error;
        }

        // Kiểm tra danh mục có danh mục con
        if (empty($errors)) {
            $sql_check_sub = "SELECT COUNT(*) as count FROM danhmuc WHERE dequi = ?";
            if ($stmt_check = $conn->prepare($sql_check_sub)) {
                $stmt_check->bind_param("i", $delete_id);
                $stmt_check->execute();
                $result_check = $stmt_check->get_result();
                if ($result_check->fetch_assoc()['count'] > 0) {
                    $errors[] = "Danh mục có danh mục con, không thể xóa";
                }
                $stmt_check->close();
            } else {
                $errors[] = "Lỗi kiểm tra danh mục con: " . $conn->error;
            }
        }

        // Nếu không có lỗi, xóa danh mục
        if (empty($errors)) {
            $sql_delete = "DELETE FROM danhmuc WHERE madm = ?";
            if ($stmt_delete = $conn->prepare($sql_delete)) {
                $stmt_delete->bind_param("i", $delete_id);
                if ($stmt_delete->execute()) {
                    $success = "Xóa danh mục thành công!";
                } else {
                    $errors[] = "Xóa thất bại: " . $stmt_delete->error;
                }
                $stmt_delete->close();
            } else {
                $errors[] = "Lỗi chuẩn bị câu lệnh xóa: " . $conn->error;
            }
        }
    }

    $dem = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM danhmuc"));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh mục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .custom-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        .custom-table td, .custom-table th {
            vertical-align: middle;
            text-align: center;
        }
        .custom-table tr:hover {
            background-color: #eef7ff;
        }
        .btn-success, .btn-danger, .btn-warning {
            border: none;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .btn-warning:hover {
            background-color: #e0a800;
        }
        .card-header h5 {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .quanlysp {
            margin-bottom: 20px;
        }
        #check {
            margin-top: 10px;
        }
        #phantrang_sp {
            margin-top: 20px;
            text-align: center;
        }
        .trang, .so {
            padding: 5px 10px;
            margin: 0 5px;
            border: 1px solid #ddd;
            background-color: #fff;
            cursor: pointer;
        }
        .trang:hover, .so:hover {
            background-color: #17a2b8;
            color: #fff;
            border-color: #17a2b8;
        }
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
            border: 1px solid #dc3545;
            padding: 10px;
            background-color: #ffeeee;
        }
        .error-list {
            list-style: disc;
            margin-left: 20px;
        }
        .success-message {
            color: #28a745;
            margin-bottom: 15px;
            border: 1px solid #28a745;
            padding: 10px;
            background-color: #e6f4ea;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container my-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="mb-0 text-uppercase">QUẢN LÝ DANH MỤC</h5>
        </div>
        <div class="card-body">
            <div class="quanlysp d-flex justify-content-between align-items-center mb-3">
                <p class="mb-0">Có tổng <span class="text-danger font-weight-bold"><?php echo $dem; ?></span> danh mục</p>
            </div>
            <?php if (!empty($errors)) { ?>
                <div class="error-message">
                    <strong>Có lỗi xảy ra:</strong>
                    <ul class="error-list">
                        <?php foreach ($errors as $error) { ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <?php if ($success) { ?>
                <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
            <?php } ?>
            <form action="?admin=xulydm" method="post" name="frmTest">
                <div class="table-responsive">
                    <table class="table table-bordered custom-table">
                        <thead class="text-center">
                            <tr>
                                <th><input type="checkbox" class="checkbox" onclick="checkall('item', this)"></th>
                                <th>Mã DM</th>
                                <th>Tên DM</th>
                                <th>Thuộc</th>
                                <th>
                                    <a href="?admin=themdm" class="btn btn-sm btn-success">Thêm danh mục</a>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php
                            if ($dem > 0) {
                                while ($bien = mysqli_fetch_array($hienthi)) {
                                    $madm = htmlspecialchars($bien['madm']);
                                    $tendm = htmlspecialchars($bien['tendm']);
                            ?>
                                <tr>
                                    <td><input type="checkbox" name="id[]" class="item checkbox" value="<?php echo $madm; ?>"/></td>
                                    <td><?php echo $madm; ?></td>
                                    <td><strong><?php echo $tendm; ?></strong></td>
                                    <td>
                                        <?php
                                        if ($bien['dequi'] == 0) {
                                            echo "Danh mục chính";
                                        } elseif ($bien['dequi'] == 1) {
                                            echo "Điện thoại";
                                        } else {
                                            echo "Phụ kiện";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="?admin=suadm&madm=<?php echo $madm; ?>" class="btn btn-sm btn-warning me-2" title="Sửa">
                                            <i class="bi bi-pencil-square"></i> Sửa
                                        </a>
                                        <a href="?admin=hienthidm&delete_id=<?php echo $madm; ?>" class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="bi bi-trash"></i> Xóa
                                        </a>
                                    </td>
                        </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='5'>Không có danh mục nào</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </form>
            <div id="phantrang_sp">
                <?php
                    $total_results = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as Num FROM danhmuc"))['Num'];
                    $total_pages = ceil($total_results / $max_results);

                    if ($total_pages > 0) {
                        if ($page > 1) {
                            $prev = ($page - 1);
                            echo "<a href=\"".$_SERVER['PHP_SELF']."?admin=hienthidm&page=$prev\"><button class='trang'>Trang trước</button></a> ";
                        }

                        for ($i = 1; $i <= $total_pages; $i++) {
                            if ($page == $i) {
                                echo "<span class='btn btn-sm btn-primary disabled'>$i</span> ";
                            } else {
                                echo "<a href=\"".$_SERVER['PHP_SELF']."?admin=hienthidm&page=$i\"><button class='so'>$i</button></a> ";
                            }
                        }

                        if ($page < $total_pages) {
                            $next = ($page + 1);
                            echo "<a href=\"".$_SERVER['PHP_SELF']."?admin=hienthidm&page=$next\"><button class='trang'>Trang sau</button></a>";
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
function checkall(className, sourceCheckbox) {
    var checkboxes = document.querySelectorAll('.' + className);
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = sourceCheckbox.checked;
    });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php ob_end_flush(); ?>
</body>
</html>