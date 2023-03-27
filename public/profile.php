<?php
	include "../bootstrap.php";
	include 'function.php';

	if(!is_user_login())
	{
		header('location:user_login.php');
	}

	use CT275\Nienluannganh\User;
	$user = new User($PDO);

	// Chỉnh sửa
	if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
		
		$user_id = isset($_REQUEST['user_id']) ?
		filter_var($_REQUEST['user_id'], FILTER_SANITIZE_NUMBER_INT) : -1;

		if ($user_id < 0 || !($user->find($user_id))) {
			header("Location: search_book.php"); 
		}
		else{
      //Cập nhật thông tin
			$user->fill_regist($_POST,$_FILES);
			$user->save();
		}
	}

  $action = "";
	$check_pass = "";
	// Đổi mật khẩu 
	if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
		if($user->find($_SESSION['user_id'])){
			if(md5($_POST['user_old_password']) == $user->user_password) {
				//Đổ dữ liệu email và password vô lại để tiến hành đổi mật khẩu
				$user->fill_login($_POST);
				$action = $user->change_password($_POST);
				
			}
			else {
				$check_pass = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Mật khẩu không khớp<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
			}	
		}
		
	}

?>

<?php include '../partials/header.php'; ?>

<main>
<div class="mt-5 mb-5">
<?php 
  $user->find($_SESSION["user_id"]);
?>
    <div class="row text-center">
      <div class="col-md-8 mb-3"><h3>CẬP NHẬT THÔNG TIN</h1></div>
      <div class="col-md-4 mb-3"><h3>ĐỔI MẬT KHẨU</h3></div>
    </div>
    <div class="row">
      <div class="col-md-4">
      <form method="POST" enctype="multipart/form-data">
        <img src="admin/uploads/<?=htmlspecialchars($user->user_profile)?>" width="100%"/>	
          <div class="mb-3">
						<input type="file" class="form-control" aria-label="file example" name="user_profile" required>
						<div class="invalid-feedback">Vui lòng chọn ảnh đại diện</div>
						<br/>
						<span class="text-muted">Chỉ những ảnh có đuôi .jpg & .png mới được cho phép. Kích thước ảnh bắt buộc phải 225*225</span>
						<br/>
				</div>
      </div>

      <div class="col-md-4">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" readonly class="form-control" id="user_email_address" name="user_email_address" placeholder="Email" value="<?=htmlspecialchars($user->user_email_address)?>"required></input>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Tên</label>
          <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Tên" value="<?=htmlspecialchars($user->user_name)?>"required></input>
        </div>

        <div class="mb-3">
          <label class="form-label">SĐT</label>
          <input type="text" class="form-control" id="user_contact_no" name="user_contact_no" placeholder="SĐT" value="<?=htmlspecialchars($user->user_contact_no)?>" required></input>
        </div>

        <div class="mb-3">
          <label class="form-label">Địa chỉ</label>
          <input type="text" class="form-control" id="user_address" name="user_address" placeholder="Địa chỉ" value="<?=htmlspecialchars($user->user_address)?>"required></input>
        </div>
        
        <div class="text-center mt-4 mb-2">
          <input type="hidden" name="user_id" value="<?=$_SESSION['user_id']?>" />
          <input type="submit" name="save" class="btn btn-primary" value="Lưu" />
        </div>
      </div>
      </form>

      <div class="col-md-4">
        <form method="POST" action="profile.php">
          <?php 
            if($action != "") {
              echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Đổi mật khẩu thành công<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }
            if($check_pass != "") {
              echo $check_pass;
            }

          ?>
            <div class="mb-3">
              <label class="form-label">Mật khẩu cũ</label>
              <input type="password" class="form-control" id="user_old_password" name="user_old_password" placeholder="Mật khẩu cũ" required></input>
            </div>

            <div class="mb-3">
              <label class="form-label">Mật khẩu mới</label>
              <input type="password" class="form-control" id="user_password" name="user_password" placeholder="Mật khẩu mới" required></input>
            </div>
            
            <div class="text-center mt-4 mb-2">
              <input type="submit" name="edit_user" class="btn btn-primary" value="Đổi" />
            </div>
          </div>
        </form>
    </div>
    


</main>

<?php include '../partials/footer.php'; ?>

<script type="text/javascript">
        $(document).ready(function () {
            $(document).click(function() {
                $(".alert").remove();
            });
            $(".alert").first().hide().fadeIn(500).delay(3000).fadeOut(500, function () {
                $(this).remove(); 
            });
        });
    </script>