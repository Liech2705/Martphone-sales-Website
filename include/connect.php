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
