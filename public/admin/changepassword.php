<?php
	include "../../bootstrap.php";
	include '../function.php';

	if(!is_admin_login())
	{
		header('location:admin_login.php');
	}
	
	use CT275\Nienluannganh\Admin;
	$admin = new Admin($PDO);
	$action = "";
	$check_pass = "";
	// Đổi mật khẩu (xem lại)
	if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_admin'])) {
		if($admin->find($_SESSION['admin_id'])){
			if(md5($_POST['admin_old_password']) == $admin->admin_password) {
				//Đổ dữ liệu email và password vô lại để tiến hành đổi mật khẩu
				$admin->fill($_POST);
				$action = $admin->change_password($_POST);
				
			}
			else {
				$check_pass = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Mật khẩu không khớp<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
			}	
		}
		
	}
	
?>

<?php include 'partials_admin/header.php'; ?>

<div class="container-fluid px-4">
	<h1 class="mt-4">Đổi mật khẩu</h1>
	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
		<li class="breadcrumb-item active">Đổi mật khẩu</a></li>
	</ol>
	<div class="row">
		<div class="col-md-6">
			<form method="POST" action="changepassword.php">
			<?php 
				if($action != "") {
					echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Đổi mật khẩu thành công<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
				}
				if($check_pass != "") {
					echo $check_pass;
				}

			?>
			<div class="card mb-4">
				<div class="card-header">
					<i class="fas fa-user-edit"></i> Đổi mật khẩu
				</div>
				<div class="card-body">

				<?php 
					$admin->find($_SESSION["admin_id"]);
				?>
					<div class="mb-3">
						<label class="form-label">Email</label>
						<input type="text" readonly name="admin_email" id="admin_email" class="form-control" value="<?=htmlspecialchars($admin->admin_email)?>" required/>
					</div>

					<div class="mb-3">
						<label class="form-label">Mật khẩu cũ</label>
						<input type="password" name="admin_old_password" id="admin_old_password" class="form-control" value="" required/>
					</div>

					<div class="mb-3">
						<label class="form-label">Mật khẩu mới</label>
						<input type="password" name="admin_password" id="admin_password" class="form-control" value="" required/>
					</div>

					<div class="mt-4 mb-0">
						<input type="submit" name="edit_admin" class="btn btn-primary" value="Đổi" />
					</div>
					
				</div>
			</div>
			</form>
		</div>
	</div>
</div>

<?php include 'partials_admin/footer.php'; ?>