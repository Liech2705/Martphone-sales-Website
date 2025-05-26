<?php
    ob_start(); // Bật bộ đệm đầu ra
    include '../include/connect.php';

    // Khởi tạo mảng lỗi và thông báo thành công
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

    // Truy vấn hóa đơn
    $sql = mysqli_query($conn, "SELECT * FROM hoadon ORDER BY mahd DESC LIMIT $from, $max_results");
    $dem = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM hoadon"));

    // Xử lý xóa hóa đơn
    if (isset($_GET['delete_id'])) {
        $delete_id = (int)$_GET['delete_id'];

        // Kiểm tra trạng thái hóa đơn
        $sql_check_status = "SELECT trangthai FROM hoadon WHERE mahd = ?";
        if ($stmt_check = $conn->prepare($sql_check_status)) {
            $stmt_check->bind_param("i", $delete_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
            $row_check = $result_check->fetch_assoc();
            if ($row_check['trangthai'] == 1) {
                $errors[] = "Không được xóa hóa đơn đang xử lý";
            }
            $stmt_check->close();
        } else {
            $errors[] = "Lỗi kiểm tra trạng thái: " . $conn->error;
        }

        // Nếu không có lỗi, xóa hóa đơn
        if (empty($errors)) {
            $sql_delete = "DELETE FROM hoadon WHERE mahd = ?";
            if ($stmt_delete = $conn->prepare($sql_delete)) {
                $stmt_delete->bind_param("i", $delete_id);
                if ($stmt_delete->execute()) {
                    // Thêm script để hiển thị thông báo và reload trang
                    echo "<script>
                            alert('Xóa hóa đơn thành công!');
                            window.location.href = '" . $_SERVER['PHP_SELF'] . "?admin=hienthihd&page=$page';
                          </script>";
                    exit(); // Thoát để đảm bảo không chạy thêm mã PHP sau khi reload
                } else {
                    $errors[] = "Xóa thất bại: " . $stmt_delete->error;
                }
                $stmt_delete->close();
            } else {
                $errors[] = "Lỗi chuẩn bị câu lệnh xóa: " . $conn->error;
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý hóa đơn</title>
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
        .btn-info {
            background-color: #17a2b8;
            border: none;
        }
        .btn-info:hover {
            background-color: #138496;
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
    <div class="card shadow mt-5">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="mb-0 text-uppercase">QUẢN LÝ HÓA ĐƠN</h5>
        </div>
        <div class="card-body">
            <div class="quanlysp">
                <p>Có tổng <span class="text-danger font-weight-bold"><?php echo $dem; ?></span> hóa đơn</p>
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
                <form action="admin.php?admin=xulyhd" method="post">
                    <div id="check">
                        <button type="submit" name="giaohang" class="btn btn-success">Đã giao hàng</button>
                        <button type="submit" name="huy" class="btn btn-warning">Hủy</button>
                    </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered custom-table mb-0">
                    <thead>
                        <tr>
                            <th width="30"><input type="checkbox" name="check" class="checkbox" onclick="checkall('item', this)"></th>
                            <th>Mã HD</th>
                            <th>Họ Tên</th>
                            <th>Địa Chỉ</th>
                            <th>Điện Thoại</th>
                            <th>Email</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($dem > 0) {
                            while ($bien = mysqli_fetch_array($sql)) {
                        ?>
                                <tr>
                                    <td><input type="checkbox" name="id[]" class="item checkbox" value="<?php echo $bien['mahd']; ?>" /></td>
                                    <td><?php echo htmlspecialchars($bien['mahd']); ?></td>
                                    <td><?php echo htmlspecialchars($bien['hoten']); ?></td>
                                    <td><?php echo htmlspecialchars($bien['diachi']); ?></td>
                                    <td>0<?php echo htmlspecialchars($bien['dienthoai']); ?></td>
                                    <td><?php echo htmlspecialchars($bien['email']); ?></td>
                                    <td>
                                        <?php
                                        if ($bien['trangthai'] == 1) echo "Đang xử lý";
                                        elseif ($bien['trangthai'] == 2) echo "Đã giao hàng";
                                        else echo "Đã hủy đơn hàng";
                                        ?>
                                    </td>
                                    <td>
                                        <a href="admin.php?admin=chitiethoadon&mahd=<?php echo $bien['mahd']; ?>" class="btn btn-sm btn-info">Chi tiết</a>
                                    </td>
                                    <td>
                                        <a href="?admin=hienthihd&delete_id=<?php echo $bien['mahd']; ?>" class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="bi bi-trash"></i> Xóa
                                        </a>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center'>Không có hóa đơn trong CSDL</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            </form>
            <div id="phantrang_sp">
                <?php
                $total_results_query = mysqli_query($conn, "SELECT COUNT(*) as Num FROM hoadon");
                $total_results = mysqli_fetch_assoc($total_results_query)['Num'];
                $total_pages = ceil($total_results / $max_results);

                if ($total_pages > 0) {
                    if ($page > 1) {
                        $prev = ($page - 1);
                        echo "<a href=\"".$_SERVER['PHP_SELF']."?admin=hienthihd&page=$prev\"><button class='trang'>Trang trước</button></a> ";
                    }

                    for ($i = 1; $i <= $total_pages; $i++) {
                        if ($page == $i) {
                            echo "<span class='btn btn-sm btn-primary disabled'>$i</span> ";
                        } else {
                            echo "<a href=\"".$_SERVER['PHP_SELF']."?admin=hienthihd&page=$i\"><button class='so'>$i</button></a> ";
                        }
                    }

                    if ($page < $total_pages) {
                        $next = ($page + 1);
                        echo "<a href=\"".$_SERVER['PHP_SELF']."?admin=hienthihd&page=$next\"><button class='trang'>Trang sau</button></a>";
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