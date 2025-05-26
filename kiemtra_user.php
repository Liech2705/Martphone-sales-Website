<?php
$server = 'localhost';
$user = 'root';
$pass = '';
$database = 'dienthoai';

$conn = mysqli_connect($server, $user, $pass, $database);
if ($conn) {
    mysqli_query($conn, "SET NAMES 'utf8'");
    // hoặc thêm mysqli_set_charset($conn, "utf8");
} else {
    exit('Lỗi kết nối: ' . mysqli_connect_error());
}
?>
<?php
if (isset($_POST['user'])) {
    $user = trim($_POST['user']);
    $stmt = $conn->prepare("SELECT idnd FROM nguoidung WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();
    echo $stmt->num_rows > 0 ? 'tontai' : 'ok';
}
?>
