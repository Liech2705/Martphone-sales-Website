
<script type="text/javascript" src="js/checkbox.js"></script>
<?php
	include ('../include/connect.php'); // Đảm bảo đã kết nối đúng với MySQLi

    $select = "SELECT * FROM nguoidung";
    // Sử dụng mysqli_query thay cho mysql_query
    $query = mysqli_query($conn, $select);
    $dem = mysqli_num_rows($query);
?>


<div class="container my-4">
    <div class="card shadow">
        <div class="card-header bg-info text-black text-center">
            <h5 class="mb-0">QUẢN LÝ NGƯỜI DÙNG</h5>
        </div>
        <div class="card-body">
            <form action="admin.php?admin=xulynd" method="post">
            Có tổng <b class="text-danger"><?php echo $dem ?></b> người dùng</p>

                <div class="table-responsive">
                    <table class="table table-bordered custom-table">
                        <thead class="text-center">
                            <tr>
							<th><input type="checkbox" class="checkbox" onclick="checkall('item', this)"></th>
                                <th>ID</th>
                                <th>Tên người dùng</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Điên thoại</th>
                                <th>Quyền</th>
                                <th>Active</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
						<?php
	
							if(!isset($_GET['page'])){  
								$page = 1;  
							} else {  
								$page = $_GET['page'];  
							}  

							$max_results = 5;  

							$from = (($page * $max_results) - $max_results);  

							$sql = mysqli_query($conn, "SELECT * FROM nguoidung LIMIT $from, $max_results"); 
												
							if($dem > 0) {
								while ($bien = mysqli_fetch_array($sql)) {
						?>
                             <tr class='noidung_hienthi_sp'>
							 <td><input type="checkbox" name="id[]" class="item checkbox" value="<?= $bien['idnd'] ?>"/></td>
                <td class="masp_hienthi_sp"><?php  echo $bien['idnd'] ?></td>
                <td class="stt_hienthi_sp"><?php echo $bien['tennd'] ?></td>
                <td class="img_hienthi_sp"> <?php echo $bien['username'] ?>  </td>
				<td class="sl_hienthi_sp"><?php echo $bien['email'] ?></td>
				<td class="sl_hienthi_sp">0<?php echo $bien['dienthoai'] ?></td>
				<td class="sl_hienthi_sp"><?php 
					if($bien['phanquyen'] == 0)
						echo "Administrator";
					else 
						echo "Người dùng";
				?></td>

								<td>
                                    <a href='admin.php?admin=suand&idnd=<?=  $bien['idnd'] ?>' class="btn btn-sm btn-warning" title="Sửa">
                                        <i class="bi bi-pencil-square"></i> Sửa
                                    </a>
									
									<div class="mb-3 mt-2">
									<a href="xoa_nguoidung.php?idnd=<?= $bien['idnd'] ?>" 
   onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');"
   class="btn btn-sm btn-danger">
   Xóa
</a>

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
                    $result_count = mysqli_query($conn, "SELECT COUNT(*) as Num FROM nguoidung");
                    $row_count = mysqli_fetch_assoc($result_count);
                    $total_results = $row_count['Num'];
                    $total_pages = ceil($total_results / $max_results);

                    if ($page > 1) {
                        $prev = $page - 1;
                        echo "<a href=\"".$_SERVER['PHP_SELF']."?admin=hienthind&page=$prev\" class='btn btn-sm btn-secondary'>Trang trước</a> ";
                    }

                    for ($i = 1; $i <= $total_pages; $i++) {
                        if ($page == $i) {
                            echo "<span class='btn btn-sm btn-primary disabled'>$i</span> ";
                        } else {
                            echo "<a href=\"".$_SERVER['PHP_SELF']."?admin=hienthind&page=$i\" class='btn btn-sm btn-outline-primary'>$i</a> ";
                        }
                    }

                    if ($page < $total_pages) {
                        $next = $page + 1;
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript">
    function checkdel(idnd)
    {
        var idnd = idnd;
        var link = "xoa_nguoidung.php?idnd=" + idnd;
        if(confirm("Bạn có chắc chắn muốn xóa người dùng này?") == true)
            window.open(link, "_self", 1);
    }function checkall(className, sourceCheckbox) {
    var checkboxes = document.querySelectorAll('.' + className);
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = sourceCheckbox.checked;
    });
}
</script>