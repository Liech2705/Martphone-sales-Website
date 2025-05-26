<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
  /* Overlay tối mờ */
  .modal-backdrop.show {
    background-color: rgba(0, 0, 0, 0.5);
  }

  /* Modal custom */
  #loginModal .modal-content {
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
  }
  #loginModal .modal-header {
    background: linear-gradient(45deg, #4b6cb7, #182848);
    border-bottom: none;
  }
  #loginModal .modal-title {
    color: #fff;
    font-weight: 600;
  }
  /* Tabs in header */
  #loginModal .nav-tabs {
    background: transparent;
    border-bottom: none;
  }
  #loginModal .nav-tabs .nav-link {
    color: #fff;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 2rem;
    margin-left: 0.5rem;
    transition: all 0.3s;
    opacity: 0.8;
  }
  #loginModal .nav-tabs .nav-link.active {
    background: rgba(255,255,255,0.2);
    color: #fff;
    opacity: 1;
  }
  .form-label {
    font-weight: 500;
    color: #333;
  }
  .btn-primary {
    background: #4b6cb7;
    border: none;
    border-radius: 2rem;
    padding: 0.5rem 1.75rem;
    transition: background 0.3s;
  }
  .btn-primary:hover {
    background: #3a539b;
  }
  .btn-success {
    border-radius: 2rem;
  }
  .form-control, .form-select, textarea {
    border-radius: 0.5rem;
    padding: 0.75rem;
  }
  /* Responsive */
  @media (max-width: 576px) {
    #loginModal .modal-dialog {
      margin: 1rem;
    }
    #loginModal .nav-tabs .nav-link {
      padding: 0.4rem 0.8rem;
      font-size: 0.85rem;
      margin-left: 0.25rem;
    }
  }
</style>

<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header d-flex flex-column align-items-start py-3">
        <div class="d-flex w-100 justify-content-between align-items-center">
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>
        <ul class="nav nav-tabs mt-3" id="authTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#loginTabPane" type="button" role="tab">Đăng nhập</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#registerTabPane" type="button" role="tab">Đăng ký</button>
          </li>
        </ul>
      </div>
      <div class="modal-body px-4">
        <div class="tab-content">
          <!-- Đăng nhập -->
          <div class="tab-pane fade show active" id="loginTabPane" role="tabpanel">
            <form method="POST" action="./kiemtra_dangnhap.php">
              <?php if (isset($_SESSION['thongbaolo'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <?= htmlspecialchars($_SESSION['thongbaolo']); unset($_SESSION['thongbaolo']); ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
              <?php endif; ?>
              <div class="mb-3">
                <label for="user" class="form-label">Tên đăng nhập</label>
                <input type="text" class="form-control" id="user" name="user" placeholder="Nhập tên đăng nhập" required>
              </div>
              <div class="mb-3">
                <label for="pass" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="pass" name="pass" placeholder="Nhập mật khẩu" required>
              </div>
              <div class="d-flex justify-content-between align-items-center">
              <a href="forgot_password.php" class="forgot-password">Quên mật khẩu?</a>
                <button type="submit" name="login" class="btn btn-primary">Đăng nhập</button>
              </div>
            </form>
          </div>

          <!-- Đăng ký -->
          <div class="tab-pane fade" id="registerTabPane" role="tabpanel">
            <form action="update_dangky.php" method="post" name="frm" onsubmit="dangky(); return false;">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Tên đăng nhập *</label>
                  <input type="text" name="user" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Tên người dùng *</label>
                  <input type="text" name="tennd" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Mật khẩu *</label>
                  <input type="password" name="pass" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Xác nhận mật khẩu *</label>
                  <input type="password" name="pass1" class="form-control" placeholder="Nhập lại mật khẩu" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Email *</label>
                  <input type="email" name="email" class="form-control" placeholder="example@mail.com" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Điện thoại *</label>
                  <input type="text" name="dienthoai" class="form-control" placeholder="10-11 chữ số" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Ngày sinh</label>
                  <input type="date" name="ngaysinh" class="form-control">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Giới tính</label>
                  <select name="gioitinh" class="form-select">
                    <option value="">-Chọn-</option>
                    <option value="nam">Nam</option>
                    <option value="nu">Nữ</option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label">Địa chỉ</label>
                  <textarea name="diachi" class="form-control" rows="2" placeholder="Nhập địa chỉ"></textarea>
                </div>
              </div>
              <div class="mt-4 d-flex justify-content-end gap-2">
                <button type="reset" class="btn btn-secondary">Hủy</button>
                <button type="submit" name="submit" class="btn btn-success">Đăng ký</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
async function kiemTraTonTai(tenTruong, giaTri) {
    try {
        const res = await fetch("kiemtra_email_dienthoai.php", {
            method: "POST",
            headers: { 
                "Content-Type": "application/x-www-form-urlencoded",
                "X-Requested-With": "XMLHttpRequest" // Đánh dấu là AJAX request
            },
            body: `${tenTruong}=${encodeURIComponent(giaTri)}`
        });
        
        if (!res.ok) throw new Error('Lỗi kết nối');
        
        const data = await res.json();
        
        if (data.error) {
            alert(data.error);
            return false;
        }
        
        return data.exists;
    } catch (error) {
        console.error('Lỗi:', error);
        alert('Có lỗi xảy ra khi kiểm tra dữ liệu');
        return false;
    }
}

async function dangky() {
    const frm = document.forms['frm'];
    
    // Validate các trường bắt buộc
    const requiredFields = [
        {field: 'tennd', min: 6, message: 'Tên người dùng phải có ít nhất 6 ký tự'},
        {field: 'user', min: 6, message: 'Tên đăng nhập phải có ít nhất 6 ký tự'},
        {field: 'pass', min: 6, message: 'Mật khẩu phải có ít nhất 6 ký tự'}
    ];
    
    for (const {field, min, message} of requiredFields) {
        if (frm[field].value.length < min) {
            alert(message);
            frm[field].focus();
            return false;
        }
    }
    
    // Kiểm tra mật khẩu khớp
    if (frm.pass.value !== frm.pass1.value) {
        alert("Mật khẩu không khớp");
        frm.pass1.focus();
        return false;
    }
    
    // Kiểm tra username tồn tại
    try {
        const userRes = await fetch("kiemtra_user.php", {
            method: "POST",
            headers: { 
                "Content-Type": "application/x-www-form-urlencoded",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: `user=${encodeURIComponent(frm.user.value)}`
        });
        
        const userData = await userRes.json();
        
        if (userData.exists) {
            alert("Tên đăng nhập đã tồn tại");
            frm.user.focus();
            return false;
        }
    } catch (error) {
        console.error('Lỗi kiểm tra username:', error);
        alert('Có lỗi xảy ra khi kiểm tra tên đăng nhập');
        return false;
    }
    
    // Validate email
    const emailPattern = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
    if (!emailPattern.test(frm.email.value)) {
        alert("Email không hợp lệ");
        frm.email.focus();
        return false;
    }
    
    // Validate số điện thoại
    if (!/^[0-9]{10,11}$/.test(frm.dienthoai.value)) {
        alert("Số điện thoại không hợp lệ");
        frm.dienthoai.focus();
        return false;
    }
    
    // Kiểm tra email hoặc số điện thoại đã tồn tại
    try {
        const [emailExists, phoneExists] = await Promise.all([
            kiemTraTonTai("email", frm.email.value),
            kiemTraTonTai("dienthoai", frm.dienthoai.value)
        ]);
        
        if (emailExists) {
            alert("Email đã được sử dụng");
            frm.email.focus();
            return false;
        }
        
        if (phoneExists) {
            alert("Số điện thoại đã được sử dụng");
            frm.dienthoai.focus();
            return false;
        }
        
        // Nếu tất cả validation đều pass, submit form
        frm.submit();
        
    } catch (error) {
        console.error('Lỗi trong quá trình validation:', error);
        alert('Có lỗi xảy ra khi kiểm tra thông tin');
        return false;
    }
}

</script>
