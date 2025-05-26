<script type="text/javascript" src="js/checkbox.js"></script>
<?php
    include ('../include/connect.php');
    
    // Query to fetch all rows from 'hotro'
    $select = "SELECT * FROM hotro";
    $query = mysqli_query($conn, $select);
    $dem = mysqli_num_rows($query);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý hỗ trợ</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="/admin/css/style.css">

</head>
<body>
<div class="container my-4">
    <div class="card shadow mt-5">
        <div class="card-header bg-primary text-black text-center">
            <h3 class="mb-0">QUẢN LÝ HỖ TRỢ</h3>
        </div>
        <div class="card-body">
            <div class="quanlysp">
                <p>Có tổng <font color=red><b><?php echo $dem ?></b></font> tin</p>
                <form action="admin.php?admin=xulyht" method="post">
                    
            </div>
            <div class="table-responsive">
                <table class="table table-bordered custom-table mb-0">
                    <tr class='tieude_hienthi_sp'>
                        <td width="30"><input type="checkbox" name="check" class="checkbox" onclick="checkall('item', this)"></td>
                        <td>ID</td>
                        <td>Chủ đề</td>
                        <td>Nội dung</td>
                        <td>Tên</td>
                        <td>Email</td>
						<td></td>
                    </tr>
                    <?php
                    if(!isset($_GET['page'])){  
                        $page = 1;  
                    } else {  
                        $page = $_GET['page'];  
                    }  

                    $max_results = 10;  
                    $from = (($page * $max_results) - $max_results);  

                    $sql = mysqli_query($conn, "SELECT * FROM hotro LIMIT $from, $max_results"); 

                    if($dem > 0)
                        while ($bien = mysqli_fetch_array($sql))
                        {
                    ?>
                            <tr class='noidung_hienthi_sp'>
                                <td class="masp_hienthi_sp"><input type="checkbox" name="id[]" class="item" class="checkbox" value="<?=$bien['idht']?>"/></td>
                                <td class="masp_hienthi_sp"><?php echo $bien['idht'] ?></td>
                                <td class="stt_hienthi_sp"><?php echo $bien['chude'] ?></td>
                                <td class="img_hienthi_sp"><?php echo $bien['noidung'] ?></td>
                                <td class="sl_hienthi_sp"><?php echo $bien['hoten'] ?></td>
                                <td class="sl_hienthi_sp"><?php echo $bien['email'] ?></td>
								<td><div id="check">
                        <p>
                            <input type="submit" name="xoa" value="Xóa" class="btn btn-danger" />
                        </p>
                    </div></td>
                            </tr>
                    <?php 
                        }
                        else echo "<tr><td colspan='6'>Không có tin nào</td></tr>";
                    ?>
                </table>
            </form>
            </div>
            <div id="phantrang_sp">
                <?php
                    $total_results = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as Num FROM hotro"))['Num'];  
                    $total_pages = ceil($total_results / $max_results);  

                    if($page > 1){  
                        $prev = ($page - 1);  
                        echo "<a href=\"".$_SERVER['PHP_SELF']."?admin=hienthiht&page=$prev\"><button class='btn btn-sm btn-primary '>Trang trước</button></a> ";  
                    }  

                    for($i = 1; $i <= $total_pages; $i++){  
                        if(($page) == $i){  
                            echo "<span class='btn btn-sm btn-primary disabled'>$i</span> ";
                        } else {  
                            echo "<a href=\"".$_SERVER['PHP_SELF']."?admin=hienthiht&page=$i\"><button class='btn btn-sm btn-outline-primary'>$i</button></a> ";  
                        }  
                    }  

                    if($page < $total_pages){  
                        $next = ($page + 1);  
                        echo "<a href=\"".$_SERVER['PHP_SELF']."?admin=hienthiht&page=$next\"><button class='btn btn-sm btn-primary '>Trang sau</button></a>";  
                    }  
                    echo "</center>";  
                ?>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript">
    function checkdel(idht)
    {
        var idht = idht;
        if(confirm("Bạn có chắc chắn muốn xóa tin này?") == true)
            window.open(link, "_self", 1);
    }function checkall(className, sourceCheckbox) {
    var checkboxes = document.querySelectorAll('.' + className);
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = sourceCheckbox.checked;
    });
}
</script>
<!-- Bootstrap JS and Popper.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>