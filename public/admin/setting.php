<?php
	include "../../bootstrap.php";
	include '../function.php';

	if(!is_admin_login())
	{
		header('location:admin_login.php');
	}

	use CT275\Nienluannganh\Setting;

	$errors = [];
	$setting = new Setting($PDO);
	$action = "";
	// Chỉnh sửa
	if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_setting'])) {
		$action = $setting->update($_POST);
		$errors = $setting->getValidationErrors();
	}

?>

<?php include 'partials_admin/header.php'; ?>

<div class="container-fluid px-4">
	<h1 class="mt-4">Cài đặt</h1>

	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
		<li class="breadcrumb-item active">Cài đặt</a></li>
	</ol>

	<?php 
		if($action != "") {
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Chỉnh sửa dữ liệu thành công<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
	?>
	<div class="card mb-4">
		<div class="card-header">
			<i class="fas fa-user-edit"></i>Cài đặt
		</div>
		<div class="card-body">

			<form method="post">
				<?php 
					$settings = $setting->all();
					foreach($settings as $setting):
				?>
				<div class="row">
					<div class="col-md-12">
						<div class="mb-3">
							<label class="form-label">Tên thư viện</label>
							<input type="text" name="library_name" id="library_name" class="form-control" value="<?=htmlspecialchars($setting->library_name)?>" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="mb-3">
							<label class="form-label">Địa chỉ</label>
							<textarea name="library_address" id="library_address" class="form-control"><?=htmlspecialchars($setting->library_address)?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">SĐT</label>
							<input type="text" name="library_contact_number" id="library_contact_number" class="form-control" value="<?=htmlspecialchars($setting->library_contact_number)?>" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">Email</label>
							<input type="text" name="library_email_address" id="library_email_address" class="form-control" value="<?=htmlspecialchars($setting->library_email_address)?>" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">Giới hạn ngày hoàn trả</label>
							<input type="number" name="library_total_book_issue_day" id="library_total_book_issue_day" class="form-control" value="<?=htmlspecialchars($setting->library_total_book_issue_day)?>" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">Chi phí trả trễ một ngày</label>
							<input type="text" name="library_one_day_fine" id="library_one_day_fine" class="form-control" value="<?=htmlspecialchars($setting->library_one_day_fine)?>" />
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">Giới hạn số lượng mượn sách mỗi người dùng</label>
							<input type="number" name="library_issue_total_book_per_user" id="library_issue_total_book_per_user" class="form-control" value="<?=htmlspecialchars($setting->library_issue_total_book_per_user)?>" />
						</div>	
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">Tỉ lệ chi trả hư hỏng sách</label>
							<input type="text" name="library_damaged_return_book_rate" id="library_damaged_return_book_rate" class="form-control" value="<?=htmlspecialchars($setting->library_damaged_return_book_rate)?>" />
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<div>
							<label class="form-label">Tỉ lệ chi trả mất sách</label>
							<input type="text" name="library_lost_book_rate" id="library_lost_book_rate" class="form-control" value="<?=htmlspecialchars($setting->library_lost_book_rate)?>" />
						</div>
					</div>
				</div>

				
				<div class="mt-4 mb-0">
					<input type="submit" name="edit_setting" class="btn btn-primary" value="Save" />
				</div>
				
				<?php endforeach ?>	

			</form>

		</div>
	</div>
</div>

<?php include 'partials_admin/footer.php'; ?>