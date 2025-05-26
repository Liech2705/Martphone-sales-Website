document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".form");
    if (form) {
        form.addEventListener("submit", function (e) {
            if (!handleFormSubmit()) {
                e.preventDefault(); // Ngăn form gửi nếu kiểm tra không đạt
            }
        });
    }
});

function handleFormSubmit() {
    const fullname = document.querySelector("input[name=fullname]").value.trim();
    const email = document.querySelector("input[name=email]").value.trim();
    const phone = document.querySelector("input[name=phone]").value.trim();
    const birthday = document.querySelector("input[name=birthday]").value.trim();

    // Fullname
    if (!fullname) {
        alert("Tên đầy đủ không được để trống");
        return false;
    }

    // Email
    if (!email) {
        alert("Email không được để trống");
        return false;
    } else if (!/^[a-zA-Z0-9._%+-]+@gmail\.com$/.test(email)) {
        alert("Email phải đúng định dạng Gmail (vd: example@gmail.com)");
        return false;
    }

    // Phone
    if (!phone) {
        alert("Số điện thoại không được để trống");
        return false;
    } else if (!/^\d+$/.test(phone)) {
        alert("Số điện thoại phải là số");
        return false;
    } else if (phone.length !== 10) {
        alert("Số điện thoại phải có đúng 10 chữ số");
        return false;
    }

    // Birthday
    if (!birthday) {
        alert("Ngày sinh không được để trống");
        return false;
    } else if (isNaN(Date.parse(birthday))) {
        alert("Ngày sinh không hợp lệ");
        return false;
    }

    // New password (nếu có)
    const newPassword = document.querySelector("input[name=new_password]");
    const confirmPassword = document.querySelector("input[name=confirm_password]");

    if (newPassword && confirmPassword) {
        const newPass = newPassword.value.trim();
        const confirmPass = confirmPassword.value.trim();

        if (newPass || confirmPass) {
            if (newPass.length < 6) {
                alert("Mật khẩu mới phải có ít nhất 6 ký tự.");
                return false;
            }
            if (newPass !== confirmPass) {
                alert("Mật khẩu xác nhận không khớp.");
                return false;
            }
        }
    }

    return true;
}
