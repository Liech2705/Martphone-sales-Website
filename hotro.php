<form action="insert_hotro.php" method="post" name="frm" onsubmit="return kiemtra()">
  <div class="container my-5">
    <div class="card shadow">
      <div class="card-header bg-primary text-white">
        <h4 class="mb-0">HỖ TRỢ</h4>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <label for="chude" class="form-label">Chủ đề <span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="chude" id="chude">
        </div>

        <div class="mb-3">
          <label for="hoten" class="form-label">Họ tên <span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="hoten" id="hoten">
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
          <input type="email" class="form-control" name="email" id="email">
        </div>

        <div class="mb-3">
          <label for="dienthoai" class="form-label">Điện thoại <span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="dienthoai" id="dienthoai">
        </div>

        <div class="mb-3">
          <label for="noidung" class="form-label">Nội dung <span class="text-danger">*</span></label>
          <textarea class="form-control" name="noidung" id="noidung" rows="5"></textarea>
        </div>

        <div class="d-flex justify-content-end gap-2">
          <button type="submit" name="submit" class="btn btn-success">GỬI</button>
          <button type="reset" class="btn btn-secondary">HỦY</button>
        </div>
      </div>
    </div>
  </div>
</form>

<script language="javascript">
 	function  kiemtra()
	{
	    if(frm.chude.value=="")
		{
			alert("Bạn chưa nhập chủ đề. Vui lòng kiểm tra lại");
			frm.chude.focus();
			return false;	
		}
		if(frm.chude.value.length<6)
		{
			alert("Chủ đề quá ngắn. Vui lòng điền đầy đủ.");
			frm.chude.focus();
			return false;	
		}
		if(frm.hoten.value=="")
	 	{
			alert("Bạn chưa nhập tên . Vui lòng kiểm tra lại");
			frm.hoten.focus();
			return false;	
		}
		if(frm.hoten.value.length<6)
	 	{
			alert("Tên không đúng.");
			frm.hoten.focus();
			return false;	
		}
		if(frm.noidung.value=="")
		{
			alert("Bạn chưa nhập nội dung");	
			frm.noidung.focus();
			return false;
		}
		if(frm.noidung.value.length<20)
		{
			alert("Nội dung phải nhiều hơn 20 ký tự");	
			frm.noidung.focus();
			return false;
		}
	   dt=/^[0-9]+$/;
	   dienthoai=frm.dienthoai.value;
	   if(!dt.test(dienthoai))
	   {
		    alert("Bạn chưa nhập điện thoại. Vui lòng kiểm tra lại.");
		    frm.dienthoai.focus();
		    return false;
	   }
	   	dd=frm.dienthoai.value;
		if(10>dd.length || dd.length>11)
		{
			alert("Số điện thoại không đủ độ dài. Vui lòng nhập lại");
			frm.dienthoai.focus();
			return false;	
		}
		if(frm.email.value=="")
		{
			alert("Bạn chưa nhập email");	
			frm.email.focus();
			return false;
		}
		mail=frm.email.value;
		m=/^([A-z0-9])+[@][a-z]+[.][a-z]+[.]*([a-z]+)*$/;
		if(!m.test(mail))
		{
			alert("Bạn nhập sai email");	
			frm.email.focus();
			return false;
		}
		
	}
 </script>