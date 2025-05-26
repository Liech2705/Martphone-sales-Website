<?php
require __DIR__ . '/include/connect.php';

$idsp = isset($_GET['idsp']) ? (int)$_GET['idsp'] : 0;
if ($idsp <= 0) {
    echo "<div class='alert alert-warning'>Sản phẩm không hợp lệ</div>";
    exit;
}

$sql = "SELECT * FROM sanpham WHERE idsp = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idsp);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $conlai = max(0, $row['soluong'] - $row['daban']);
?>
<div class="container my-5">
  <div class="row">
    <!-- Ảnh sản phẩm -->
    <div class="col-md-5 text-center">
      <div class="position-relative overflow-hidden rounded shadow-sm">
        <img src="img/uploads/<?= htmlspecialchars($row['hinhanh']) ?>"
             class="img-fluid w-100 product-img"
             alt="<?= htmlspecialchars($row['tensp']) ?>"
             loading="lazy">
      </div>
    </div>
    <!-- Thông tin chi tiết -->
    <div class="col-md-7">
      <h2 class="mb-3"><?= htmlspecialchars($row['tensp']) ?></h2>
      <p>
        <strong>Giá gốc:</strong>
        <span class="text-muted">
          <?php if ($row['khuyenmai1'] > 0): ?>
            <s><?= number_format($row['gia'], 0, ',', '.') ?> ₫</s>
          <?php else: ?>
            <?= number_format($row['gia'], 0, ',', '.') ?> ₫
          <?php endif; ?>
        </span>
      </p>
      <p>
        <strong>Giá:</strong>
        <span class="text-danger fw-bold">
          <?= number_format($row['gia'] * ((100 - $row['khuyenmai1'])/100), 0, ',', '.') ?> ₫
        </span>
      </p>
      <p>
        <strong>Tình trạng:</strong>
        <?php if ($conlai > 0): ?>
          <span class="badge bg-success">Còn <?= $conlai ?> sản phẩm</span>
        <?php else: ?>
          <span class="badge bg-danger">Hết hàng</span>
        <?php endif; ?>
      </p>

      <!-- Form chọn số lượng -->
      <form action="index.php?content=cart&action=add&idsp=<?= (int)$row['idsp'] ?>"
            method="post" class="mt-4">
        <div class="mb-3">
          <label for="soluongmua" class="form-label">Số lượng mua:</label>
          <div class="input-group w-50">
            <button class="btn btn-outline-secondary" type="button"
                    data-action="decrement" aria-hidden="true">−</button>
            <input type="number" name="soluongmua" id="soluongmua"
                   class="form-control text-center"
                   min="1" max="<?= $conlai ?: 1 ?>" value="1" required
                   aria-label="Số lượng mua">
            <button class="btn btn-outline-secondary" type="button"
                    data-action="increment" aria-hidden="true">+</button>
          </div>
        </div>
        <?php if ($conlai > 0): ?>
          <button type="submit" class="btn btn-primary">Cho vào giỏ</button>
        <?php else: ?>
          <button class="btn btn-secondary" disabled>Hết hàng</button>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <!-- Tab mô tả & bình luận -->
  <div class="row mt-5">
    <div class="col-12">
      <ul class="nav nav-tabs" id="productTab" role="tablist">
        <li class="nav-item">
          <button class="nav-link active" data-bs-toggle="tab"
                  data-bs-target="#tab1" type="button">Tính năng</button>
        </li>
      </ul>
      <div class="tab-content border border-top-0 p-4">
        <div class="tab-pane fade show active" id="tab1">
          <?= nl2br(htmlspecialchars($row['chitiet'])) ?>
        </div>
        <div class="tab-pane fade" id="tab2">
          <div id="fb-root"></div>
          <script async defer crossorigin="anonymous"
                  src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v12.0">
          </script>
          <div class="fb-comments"
               data-href="<?= 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"
               data-width="100%" data-numposts="10"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- CSS tùy chỉnh -->
<style>
  input[type=number]::-webkit-inner-spin-button,
  input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none; margin: 0;
  }
  input[type=number] { -moz-appearance: textfield; }
  .product-img { transition: transform .5s ease; }
  .product-img:hover { transform: scale(1.05); }
</style>

<!-- JS vanilla cho nút +/− -->
<script>
  document.querySelectorAll('.input-group').forEach(group => {
    const input = group.querySelector('input[type="number"]');
    const btnDec = group.querySelector('[data-action="decrement"]');
    const btnInc = group.querySelector('[data-action="increment"]');

    const updateButtons = () => {
      const val = parseInt(input.value) || input.min;
      btnDec.disabled = val <= input.min;
      btnInc.disabled = val >= input.max;
    };
    [btnDec, btnInc].forEach(btn => {
      btn.addEventListener('click', () => {
        let val = parseInt(input.value) || input.min;
        val += btn === btnInc ? 1 : -1;
        val = Math.max(input.min, Math.min(input.max, val));
        input.value = val;
        updateButtons();
      });
    });
    input.addEventListener('input', updateButtons);
    updateButtons();
  });
</script>
<?php } else {
  echo "<div class='alert alert-danger'>Sản phẩm không tồn tại</div>";
}
$stmt->close();
?>