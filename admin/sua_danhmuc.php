<?php
ob_start(); // Bật bộ đệm đầu ra
include '../include/connect.php';

$error = "";
$success = "";
$row = null;

// Xử lý form
if (isset($_POST['btnthem']) && isset($_GET['madm'])) {
    $madm = filter_var($_GET['madm'], FILTER_SANITIZE_NUMBER_INT);
    $tendm = trim($_POST['tendm'] ?? '');

    if (empty($tendm)) {
        $error = "Xin vui lòng nhập tên danh mục";
    } else {
        // Kiểm tra xem tên danh mục đã tồn tại chưa (trừ bản ghi hiện tại)
        $sql_check = "SELECT * FROM danhmuc WHERE tendm = ? AND madm != ?";
        if ($stmt_check = $conn->prepare($sql_check)) {
            $stmt_check->bind_param("si", $tendm, $madm);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
            if ($result_check->num_rows > 0) {
                $error = "Tên danh mục đã tồn tại. Vui lòng chọn tên khác.";
            }
            $stmt_check->close();
        } else {
            $error = "Lỗi kiểm tra danh mục: " . $conn->error;
        }

        // Nếu không có lỗi, tiến hành cập nhật
        if (empty($error)) {
            $dequi = (int)($_POST['dequi'] ?? 0);
            $sql = "UPDATE danhmuc SET tendm = ?, dequi = ? WHERE madm = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("sii", $tendm, $dequi, $madm);
                if ($stmt->execute()) {
                    $success = "Đã cập nhật danh mục thành công";
                } else {
                    $error = "Lỗi khi cập nhật danh mục: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error = "Lỗi trong việc chuẩn bị câu lệnh: " . $conn->error;
            }
        }
    }
}

// Lấy dữ liệu danh mục
if (isset($_GET['madm'])) {
    $madm = filter_var($_GET['madm'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM danhmuc WHERE madm = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $madm);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
    } else {
        $error = "Lỗi truy vấn danh mục: " . $conn->error;
    }
} else {
    $error = "Không tìm thấy mã danh mục";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa danh mục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<link rel="stylesheet" href="/admin/css/form.css">
<div class="fix_form">        
    <form action="?admin=suadm&madm=<?php echo htmlspecialchars($row['madm']); ?>" class="form" method="post" name="frm" onsubmit="return kiemtra()">
        <h2>SỬA DANH MỤC</h2>
        <?php if ($error) { echo "<p class='error text-danger'>$error</p>"; } ?>
        <?php if ($success) { echo "<p class='text-success'>$success</p>"; } ?>
        <?php if ($row) { ?>
            <div class="mb-3">
                <label for="madm" class="form-label">Mã danh mục</label>
                <input type="text" class="form-control" id="madm" name="madm" disabled value="<?php echo htmlspecialchars($row['madm']); ?>" />
            </div>
            <div class="mb-3">
                <label for="tendm" class="form-label">Tên danh mục</label>
                <input type="text" class="form-control" id="tendm" name="tendm" value="<?php echo htmlspecialchars($row['tendm']); ?>" />
            </div>
            <div class="mb-3">
                <label for="dequi" class="form-label">Thuộc</label>
                <select class="form-select" id="dequi" name="dequi">
                    <option value="0" <?php if ($row['dequi'] == 0) echo 'selected'; ?>>Danh mục chính</option>
                    <?php
                    $sql1 = "SELECT * FROM danhmuc WHERE dequi = 0 AND madm != ?";
                    if ($stmt1 = $conn->prepare($sql1)) {
                        $stmt1->bind_param("i", $madm);
                        $stmt1->execute();
                        $result1 = $stmt1->get_result();
                        while ($row1 = $result1->fetch_assoc()) {
                    ?>
                        <option value="<?php echo $row1['madm']; ?>" <?php if ($row1['madm'] == $row['dequi']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($row1['tendm']); ?>
                        </option>
                    <?php
                        }
                        $stmt1->close();
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3 text-center">
                <button type="submit" name="btnthem" class="btn btn-success">Update</button>
            </div>
        </form>
        <?php } else { ?>
        <p class="error text-danger">Không tìm thấy danh mục</p>
        <?php } ?>
    </div>

<script>
function kiemtra() {
    if (frm.tendm.value.trim() == "") {
        alert("Bạn chưa nhập tên danh mục. Vui lòng kiểm tra lại");
        frm.tendm.focus();
        return false;
    }
    return true;
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php ob_end_flush(); // Kết thúc bộ đệm đầu ra ?>
</body>
</html>