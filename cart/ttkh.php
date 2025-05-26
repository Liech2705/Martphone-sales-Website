<!-- checkout.php -->
<div class="container my-5">
  <h2 class="mb-4">Thông tin thanh toán</h2>
  <form id="checkoutForm" name="checkoutForm" method="POST">
    <?php
    if (isset($_SESSION['idnd'])) {
        $sql = "SELECT * FROM nguoidung WHERE idnd = '" . $_SESSION['idnd'] . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    }
    ?>
    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label">Tên khách hàng</label>
        <input type="text" name="hoten" class="form-control" value="<?= $row['tennd'] ?? '' ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Số điện thoại</label>
        <input type="text" name="dienthoai" class="form-control" value="0<?= $row['dienthoai'] ?? '' ?>">
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label">Địa chỉ giao hàng</label>
      <input type="text" name="diachi" class="form-control" value="<?= $row['diachi'] ?? '' ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" value="<?= $row['email'] ?? '' ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Phương thức thanh toán</label>
      <select name="phuongthuc" class="form-select" id="phuongthuc" onchange="toggleMomo()">
        <option value="">Chọn phương thức thanh toán</option>
        <option value="2">Qua Momo</option>
        <option value="3">Qua thẻ ATM</option>
        <option value="4">Thanh toán trực tiếp</option>
      </select>
    </div>

    <!-- Collapse: Thông tin Momo -->
    <div class="collapse" id="momoInfo">
      <div class="card card-body border-info mb-3">
        <h5 class="text-info">Thông tin thanh toán qua Momo</h5>
        <div class="mb-3">
          <label class="form-label">Số Momo</label>
          <input type="text" name="momo_sdt" class="form-control" placeholder="Nhập số điện thoại Momo">
        </div>
        <div class="mb-3">
          <label class="form-label">Tên chủ tài khoản</label>
          <input type="text" name="momo_ten" class="form-control" placeholder="Nhập tên chủ tài khoản Momo">
        </div>
      </div>
    </div>

    <input type="hidden" name="redirect" value="1" id="redirectVNPay">
    <div class="mt-4">
      <button type="submit" class="btn btn-success">Đặt hàng</button>
    </div>
  </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("checkoutForm");

  form.addEventListener("submit", async (e) => {
    e.preventDefault(); // Chặn submit mặc định

    const pt = form.phuongthuc.value;

    if (form.hoten.value.trim() === "") { alert("Bạn chưa điền tên"); form.hoten.focus(); return; }
    if (form.dienthoai.value.trim() === "") { alert("Bạn chưa điền SĐT"); form.dienthoai.focus(); return; }
    if (form.diachi.value.trim() === "") { alert("Bạn chưa điền địa chỉ"); form.diachi.focus(); return; }
    if (pt === "") { alert("Bạn chưa chọn phương thức thanh toán"); form.phuongthuc.focus(); return; }

    const formData = new FormData(form);

    // Gửi dữ liệu tạm lên session
    await fetch("./cart/save_ttkh.php", {
      method: "POST",
      body: formData
    });

    // Gán action dựa theo phương thức thanh toán
    if (pt === "3") {
      form.action = "./cart/checkout_VNPay.php";
    } else {
      form.action = "index.php?content=cart&action=insert";
    }

    form.submit(); // Submit thật sau khi đã set action
  });
});

function toggleMomo() {
    const select = document.getElementById("phuongthuc");
    const momo = document.getElementById("momoInfo");
    if (select.value === "2") {
        new bootstrap.Collapse(momo, { show: true });
    } else {
        new bootstrap.Collapse(momo, { toggle: false }).hide();
    }
}
</script>
