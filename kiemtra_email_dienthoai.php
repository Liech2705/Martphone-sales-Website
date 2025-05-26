<?php
session_start();
include 'include/connect.php';

// Set kiểu trả về là JSON
header('Content-Type: application/json');

// Chỉ chấp nhận method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Kiểm tra email
if (isset($_POST['email'])) {
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    if (!$email) {
        echo json_encode(['error' => 'Email không hợp lệ']);
        exit;
    }
    
    $stmt = $conn->prepare("SELECT id FROM nguoidung WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    echo json_encode(['exists' => $stmt->num_rows > 0]);
    exit;
}

// Kiểm tra số điện thoại
if (isset($_POST['dienthoai'])) {
    $dienthoai = preg_replace('/[^0-9]/', '', $_POST['dienthoai']);
    if (strlen($dienthoai) < 10 || strlen($dienthoai) > 11) {
        echo json_encode(['error' => 'Số điện thoại không hợp lệ']);
        exit;
    }
    
    $stmt = $conn->prepare("SELECT id FROM nguoidung WHERE dienthoai = ?");
    $stmt->bind_param("s", $dienthoai);
    $stmt->execute();
    $stmt->store_result();
    echo json_encode(['exists' => $stmt->num_rows > 0]);
    exit;
}

// Nếu request không hợp lệ
http_response_code(400);
echo json_encode(['error' => 'Yêu cầu không hợp lệ']);