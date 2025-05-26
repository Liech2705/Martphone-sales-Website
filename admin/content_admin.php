<?php
require("../include/connect.php"); // đảm bảo kết nối CSDL có sẵn qua $conn

if (isset($_GET['admin'])) {
    switch ($_GET['admin']) {
        case 'hienthisp':
            include("sanpham.php");
            break;
        case 'themsp':
            include("them_sanpham.php");
            break;
        case 'suasp':
            include("sua_sanpham.php");
            break;
        case 'hienthidm':
            include("danhmuc.php");
            break;
        case 'themdm':
            include("them_danhmuc.php");
            break;
        case 'suadm':
            include("sua_danhmuc.php");
            break;
        case 'hienthind':
            include("nguoidung.php");
            break;
        case 'themnd':
            include("them_nguoidung.php");
            break;
        case 'suand':
            include("sua_nguoidung.php");
            break;
        case 'xulyhd':
            include("xulyhd.php");
            break;
        case 'hienthitt':
            include("tintuc.php");
            break;
        case 'themtt':
            include("them_tintuc.php");
            break;
        case 'suatt':
            include("sua_tintuc.php");
            break;
        case 'hienthiht':
            include("hotro.php");
            break;
        case 'hienthihd':
            include("hoadon.php");
            break;
        case 'chitiethoadon':
            include("chitiethoadon.php");
            break;
        case 'xulyht':
            include("xulyht.php");
            break;
        case 'xulysp':
            include("xulysp.php");
            break;
        case 'xulytt':
            include("xulytt.php");
            break;
        default:
            include("sanpham.php");
            break;
    }
} else {
    ?>
    <style>
        .custom-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        .custom-table td, .custom-table th {
            vertical-align: middle;
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
        .card-header h5 {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>


    <div class="container my-4">
        <div class="card shadow mt-5">
            <div class="card-header bg-warning text-black text-center">
                <h5 class="mb-0">Đơn hàng cần được xử lý</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered custom-table mb-0">
                        <thead>
                            <tr class="text-center">
                                <th scope="col" >STT</th>
                                <th scope="col">Họ tên</th>
                                <th scope="col">Địa chỉ</th>
                                <th scope="col">Điện thoại</th>
                                <th scope="col">Ngày đặt hàng</th>
                                <th scope="col">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php 
                            $i = 1;
                            $sql = mysqli_query($conn, "SELECT * FROM hoadon WHERE trangthai = '1'");
                            while ($row = mysqli_fetch_array($sql)) {
                            ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= htmlspecialchars($row['hoten']); ?></td>
                                <td><?= htmlspecialchars($row['diachi']); ?></td>
                                <td><?= htmlspecialchars($row['dienthoai']); ?></td>
                                <td><?= htmlspecialchars($row['ngaydathang']); ?></td>
                                <td>
                                <a href="admin.php?admin=hienthihd" class="btn btn-sm btn-info">
                                        Chi tiết
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
        <?php 
}
?>
