<?php
    // Kết nối cơ sở dữ liệu với mysqli
    include '../include/connect.php'; // Giả sử bạn đã có kết nối ở file này

    // Lấy mã tin tức (matt) từ URL
    $matt = $_GET['matt'];

    // Câu lệnh SELECT với mysqli
    $sql = "SELECT * FROM tintuc WHERE matt = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Gắn tham số vào câu lệnh
        $stmt->bind_param("s", $matt);
        
        // Thực thi câu lệnh
        $stmt->execute();
        
        // Lấy kết quả
        $result = $stmt->get_result();
        
        // Kiểm tra nếu có dữ liệu
        if ($row = $result->fetch_assoc()) {
            // Gán dữ liệu vào các biến để sử dụng trong form
            $tieude = $row['tieude'];
            $ndngan = $row['ndngan'];
            $noidung = $row['noidung'];
            $hinhanh = $row['hinhanh'];
            $tacgia = $row['tacgia'];
        }
        
        // Đóng statement
        $stmt->close();
    }
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="/admin/css/form.css">
<div class="fix_form">
<form action="update_tintuc.php?matt=<?php echo $matt; ?>" method="post" name="frm" class="form" enctype="multipart/form-data">
    <table>
        <h2>Sửa Tin Tức</h2>
        <tr>
            <td>Tiêu đề</td>
            <td><input type="text" name="tieude" size="50" value="<?php echo htmlspecialchars($tieude); ?>"/></td>
        </tr>
        <tr>
        <tr>
            <td>Nội dung ngắn</td>
            <td><textarea name="ndngan" rows="4" cols="50"><?php echo htmlspecialchars($ndngan); ?></textarea></td>
        </tr>
        <tr>
            <td>Nội dung chi tiết</td>
            <td><textarea name="noidung" id="chitiet" rows="10" cols="50"><?php echo htmlspecialchars($noidung); ?></textarea></td>
        </tr>  
        <tr>
            <td>Hình ảnh</td>
            <td>
                <img src="../img/tintuc/<?php echo htmlspecialchars($hinhanh); ?>" width="80" height="120"/>
                <br /><br />
                <input type="file" name="hinhanh" />
            </td>
        </tr>
        <tr>
            <td>Tác giả</td>
            <td><input type="text" name="tacgia" value="<?php echo htmlspecialchars($tacgia); ?>"/></td>
        </tr>
        <tr>
            <td colspan=2 class="funtion">
                <input type="submit" name="update" value="Update" />
            </td>
        </tr>
    </table>
</form>
</div>
<script>function checkall(className, sourceCheckbox) {
    var checkboxes = document.querySelectorAll('.' + className);
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = sourceCheckbox.checked;
    });
}</script>