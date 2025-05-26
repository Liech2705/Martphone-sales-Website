<?php 
include './include/connect.php'; // Kết nối MySQLi, trả về $conn

if (isset($_GET['matt'])) {
    $matt = mysqli_real_escape_string($conn, $_GET['matt']);

    $sql = "SELECT * FROM tintuc WHERE matt = '$matt'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
?>
<div class="chitiettintuc">
    <h3><?php echo htmlspecialchars($row['tieude']); ?></h3>
    <div class="noidungchitiettintuc">
        <img src="img/tintuc/<?php echo htmlspecialchars($row['hinhanh']); ?>" width="100%" height="100%">
        <p><?php echo nl2br(htmlspecialchars($row['ndngan'])); ?></p>
    </div>
    <div class="noidungfull">
        <p><?php echo nl2br(htmlspecialchars($row['noidung'])); ?></p>
        <span>Tác giả: <?php echo htmlspecialchars($row['tacgia']); ?></span>
    </div>
</div>
<?php
        }
    } else {
        echo "<p>Không tìm thấy tin tức phù hợp.</p>";
    }
} else {
    echo "<p>Không có mã tin tức được cung cấp.</p>";
}
?>
