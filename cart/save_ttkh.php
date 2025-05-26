<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['checkout'] = [
        'hoten' => $_POST['hoten'] ?? '',
        'dienthoai' => $_POST['dienthoai'] ?? '',
        'diachi' => $_POST['diachi'] ?? '',
        'email' => $_POST['email'] ?? '',
        'phuongthuc' => $_POST['phuongthuc'] ?? '',
        'momo_sdt' => $_POST['momo_sdt'] ?? '',
        'momo_ten' => $_POST['momo_ten'] ?? ''
    ];
    echo 'OK';
}
?>
