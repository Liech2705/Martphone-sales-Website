<?php
include ('../include/connect.php');

// Kiểm tra và bảo vệ tham số GET
$mahd = isset($_GET['mahd']) ? intval($_GET['mahd']) : 0;

// Tránh lỗi khi không có tham số 'mahd'
if ($mahd > 0) {
    $select = "SELECT * FROM chitiethoadon WHERE mahd = ?";
    $stmt = mysqli_prepare($conn, $select);
    mysqli_stmt_bind_param($stmt, 'i', $mahd); // 'i' là kiểu dữ liệu integer
    mysqli_stmt_execute($stmt);
    $query = mysqli_stmt_get_result($stmt);
    $dem = mysqli_num_rows($query);
} else {
    $dem = 0;
}
?>

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
    .card-header h3 {
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .quanlysp {
        margin-bottom: 20px;
    }
    .total-row {
        font-size: 20px;
        font-weight: bold;
        padding: 10px 20px;
    }
        /* Giảm độ sáng của màu bg-success */
.bg-success-custom {
    background-color:rgb(129, 210, 132); /* Màu xanh nhẹ hơn */
    color: black; /* Giữ chữ màu đen */
}

    
</style>

<div class="container my-4">
    <div class="card shadow mt-5">
        <div class="card-header bg-success-custom text-black text-center">
            <h3 class="mb-0">CHI TIẾT HÓA ĐƠN</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered custom-table mb-0">
                    <thead>
                        <tr>
                            <th>Mã HD</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tong = 0;
                        if ($dem > 0) {
                            while ($bien = mysqli_fetch_array($query)) {
                                $thanhtien = $bien['gia'] * $bien['soluong'];
                                $tong += $thanhtien;
                        ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($bien['mahd']); ?></td>
                                    <td><?php echo htmlspecialchars($bien['tensp']); ?></td>
                                    <td><?php echo htmlspecialchars($bien['soluong']); ?></td>
                                    <td><?php echo number_format($bien['gia'], 0, ",", "."); ?></td>
                                    <td><?php echo number_format($thanhtien, 0, ",", "."); ?></td>
                                </tr>
                        <?php
                            }
                        ?>
                            <tr>
                                <td colspan="5" class="total-row text-right">
                                    Tổng: <span class="text-danger"><?php echo number_format($tong, 0, ",", "."); ?></span>
                                </td>
                            </tr>
                        <?php
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>Không có sản phẩm trong CSDL</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>