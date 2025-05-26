<?php 
session_start();

// Xóa toàn bộ session liên quan đến người dùng
unset($_SESSION['phanquyen']);
unset($_SESSION['username']);
unset($_SESSION['idnd']);
unset($_SESSION['user']);


// Hoặc xóa toàn bộ session nếu không dùng cho mục đích khác
// session_destroy();

echo "
<script>
    alert('Bạn đã đăng xuất thành công.');
    window.location.href = 'index.php';
</script>
";
?>
