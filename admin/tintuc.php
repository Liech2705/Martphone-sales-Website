<script type="text/javascript" src="js/checkbox.js"></script>
<?php
	include ('../include/connect.php');
	
    $select = "SELECT * FROM tintuc";
    $query = mysqli_query($conn, $select);
    $dem = mysqli_num_rows($query);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tin tức</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/admin/css/style.css">

</head>
<body>
<div class="container my-4">
    <div class="card shadow mt-5">
        <div class="card-header bg-info text-black text-center">
            <h5 class="mb-0">QUẢN LÝ TIN TỨC</h5>
        </div>
        <div class="card-body">
            <div class="quanlysp">
                <p>Có tổng <font color=red><b><?php echo $dem ?></b></font> tin tức</p>
                <form action="admin.php?admin=xulytt" method="post">
                    
            </div>
            <div class="table-responsive">
                <table class="table table-bordered custom-table mb-0">
                    <tr class='tieude_hienthi_sp'>
                        <td width="30"><input type="checkbox" name="check" class="checkbox" onclick="checkall('item', this)"></td>
                        <td>ID</td>
                        <td>Tiêu đề</td>
                        <td>Nội dung ngắn</td>
                        <td>Hình ảnh</td>
                        <td>Tác giả</td>
                        <td><a href='?admin=themtt'class="btn  btn-success">Thêm tin tức</a></td>
                    </tr>
                    <?php
                    // Phân trang
                    if(!isset($_GET['page'])){
                        $page = 1;
                    } else {
                        $page = $_GET['page'];
                    }

                    $max_results = 5;
                    $from = (($page * $max_results) - $max_results);    

                    $sql = mysqli_query($conn, "SELECT * FROM tintuc ORDER BY matt DESC LIMIT $from, $max_results");

                    if($dem > 0)
                        while ($bien = mysqli_fetch_array($sql))
                        {
                    ?>
                            <tr class='noidung_hienthi_sp'>
                                <td class="masp_hienthi_sp"><input type="checkbox" name="id[]" class="item" class="checkbox" value="<?=$bien['matt']?>"/></td>
                                <td class="masp_hienthi_sp" width="30"><?php echo $bien['matt'] ?></td>
                                <td class="stt_hienthi_sp"><?php echo $bien['tieude'] ?></td>
                                <td class="img_hienthi_sp" width="300"><?php echo $bien['ndngan'] ?></td>
                                <td class="sl_hienthi_sp"><img src="../img/tintuc/<?php echo $bien['hinhanh'] ?>" width="80" height="60"/></td>
                                <td class="sl_hienthi_sp"><?php echo $bien['tacgia'] ?></td>
                                <td class="active_hienthi_sp">
                                    <a href='?admin=suatt&matt=<?php echo $bien['matt'] ?>' class="btn  btn-warning">Sửa</a>
                                    <div id="check">
                                        <p>
                                            <input type="submit" name="xoa" value="Xóa" class="btn btn-danger" />
                                        </p>
                                    </div>
                                </td>
                            </tr>
                    <?php 
                        }
                        else echo "<tr><td colspan='6'>Không có tin tức</td></tr>";
                    ?>
                </table>
            </form>
            </div>
            <div id="phantrang_sp">
                <?php
                     $result_count = mysqli_query($conn, "SELECT COUNT(*) as Num FROM tintuc");
                     $row_count = mysqli_fetch_assoc($result_count);
                     $total_results = $row_count['Num'];
                     $total_pages = ceil($total_results / $max_results);

                    if($page > 1){
                        $prev = ($page - 1);
                        echo "<a href=\"".$_SERVER['PHP_SELF']."?admin=hienthitt&page=$prev\"><button class='btn btn-sm btn-primary '>Trang trước</button></a> ";
                    }

                    for($i = 1; $i <= $total_pages; $i++){
                        if(($page) == $i){
                            echo "<span class='btn btn-sm btn-primary disabled'>$i</span> ";
                        } else {
                            echo "<a href=\"".$_SERVER['PHP_SELF']."?admin=hienthitt&page=$i\"><button cclass='btn btn-sm btn-outline-primary'>$i</button></a> ";
                        }
                    }

                    if($page < $total_pages){
                        $next = ($page + 1);
                        echo "<a href=\"".$_SERVER['PHP_SELF']."?admin=hienthitt&page=$next\"><button class='btn btn-sm btn-primary '>Trang sau</button></a>";
                    }
                    echo "</center>";
                ?>
            </div>
            
        </div>
    </div>
</div>
<!-- Bootstrap JS and Popper.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>