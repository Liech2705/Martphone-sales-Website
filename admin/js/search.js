function validateSearch() {
    const searchInput = document.getElementById('searchInput').value.trim();
    if (searchInput === '') {
      alert("Vui lòng nhập từ khóa để tìm kiếm");
      return false;  // Ngừng gửi form nếu không có từ khóa
    }
    return true;  // Tiếp tục gửi form nếu có từ khóa
  }