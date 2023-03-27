<?php
	include "../../bootstrap.php";
	include '../function.php';

	if(!is_admin_login())
	{
		header('location:admin_login.php');
	}

	use CT275\Nienluannganh\Location_rack;

	$errors = [];
	$location_rack = new Location_rack($PDO);
	// Thêm và chỉnh sửa (combo)
	if($_SERVER['REQUEST_METHOD'] === 'POST') {

		if($_POST['add_location_rack']) {
			$location_rack->update($_POST);
			if($location_rack->update($_POST)) {
				header("Location: location_rack.php?msg=add");
			}
		}
		else if ($_POST['edit_location_rack']) {

			//Kiểm tra id hợp lệ (có thể bỏ)
			$location_rack_id = isset($_REQUEST['location_rack_id']) ?
			filter_var($_REQUEST['location_rack_id'], FILTER_SANITIZE_NUMBER_INT) : -1;

			if ($location_rack_id < 0 || !($location_rack->find($location_rack_id))) {
				header("Location: location_rack.php"); 
			}
			else{
				$location_rack->update($_POST);
				if($location_rack->update($_POST)) {
					header("Location: location_rack.php?msg=edit");
				}
			}
			
		}
		$errors = $location_rack->getValidationErrors();
	}
	
	//Xóa 
	if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['location_rack_id'],$_GET['action']) && $_GET["action"] == 'delete' && ($location_rack->find($_GET['location_rack_id'])) !== null){
		$location_rack->delete_location_rack();
		if($location_rack->delete_location_rack()) {
			header("Location: location_rack.php?msg=delete");
		}
	}
	
	//On/off
	if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['location_rack_id'],$_GET['action']) && $_GET["action"] == 'change' && ($location_rack->find($_GET['location_rack_id'])) !== null){
		$location_rack->update_status_location_rack();
	}

?>

<?php include 'partials_admin/header.php'; ?>

<div class="container-fluid py-4" style="min-height: 700px;">
	<h1>Quản lí vị trí kệ</h1>

	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
		<li class="breadcrumb-item active">Quản lí vị trí kệ</li>
	</ol>

	<?php 
	if(isset($_GET["msg"]))
	{
		//Add
		if($_GET["msg"] == 'add')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Vị trí kệ đã được tạo<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		//Edit
		if($_GET['msg'] == 'edit')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Vị trí kệ đã được cập nhật<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		//Delete
		if($_GET['msg'] == 'delete')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Vị trí kệ đã được xóa<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		//Disable
		if($_GET["msg"] == 'disable')
		{
			echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Trạng thái vị trí kệ đã chuyển sang chế độ vô hiệu hóa<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		//Enable
		if($_GET["msg"] == 'enable')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Trạng thái vị trí kệ đã chuyển sang chế độ kích hoạt<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
	}
	?>
	
	<?php 
		if (isset($errors['location_rack_name'])) {
			echo '<div class="alert alert-danger">'.$errors['location_rack_name'].'</div>';
		}
	?>

	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i>Quản lí vị trí kệ
				</div>
				<div class="col col-md-6" align="right">

					<a type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
						<i class="fas fa-plus me-1"></i>Thêm
					</a>

				</div>

				<!-- Modal add-->
				<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<form action="location_rack.php" method="POST" id="form_location_rack_add" class="was-validated">

								<div class="modal-header">
									<h1 class="modal-title fs-5" id="exampleModalLabel">Thêm vị trí kệ</h1>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<div class="mb-3">
										<label class="form-label">Tên vị trí kệ</label>
										<input class="form-control" id="location_rack_name" name="location_rack_name" placeholder="Tên vị trí kệ" required></input>
										<div class="invalid-feedback">
											Vui lòng nhập vào tên vị trí kệ.
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
									<input type="submit" name="add_location_rack" id="add_location_rack" class="btn btn-success" value="Thêm"/>
								</div>
								
							</form>
						</div>
					</div>
				</div>
				
			</div>
		</div>

		<div class="card-body" >
			<table id="datatablesSimple" class="table-striped">
				<thead>
					<tr>
						<th>Tên vị trí kệ</th>
						<th>Trạng thái</th>
						<th>Thao tác</th>
					</tr>
				</thead>
				<tbody id="load_data">

				<?php 
					$location_racks = $location_rack->all();
					foreach($location_racks as $location_rack):

						$location_rack_status = "";
						if($location_rack->location_rack_status == 'Enable')
						{
							$location_rack_status = '<div class="badge bg-success">Kích hoạt</div>';
						}
						else
						{
							$location_rack_status = '<div class="badge bg-danger">Vô hiệu hóa</div>';
						}
				?>

					<tr>
						<td><?=htmlspecialchars($location_rack->location_rack_name)?></td>
						<td><?=$location_rack_status?></td>
						<td>
							<a href="location_rack.php?location_rack_id=<?=htmlspecialchars($location_rack->getId())?>"  type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#location_rack<?=htmlspecialchars($location_rack->getId())?>">
								<i class="fas fa-edit me-1"></i>
							</a>
							<a href="location_rack.php?action=delete&location_rack_id=<?=htmlspecialchars($location_rack->getId())?>" onclick = "return confirm('Bạn chắc chứ?')"  class="btn btn-sm btn-danger">
								<i class="fas fa-trash mx-1"></i>
							</a>
							<a href="location_rack.php?action=change&location_rack_id=<?=htmlspecialchars($location_rack->getId())?>" onclick = "return confirm('Bạn chắc chứ?')"  class="btn btn-sm btn-warning">
								<i class="fas fa-power-off mx-1"></i>
							</a>
						</td>

						<!-- Modal edit-->
						<div class="modal fade" id="location_rack<?=htmlspecialchars($location_rack->getId())?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<form action="location_rack.php" method="POST" id="form_location_rack_edit" class="was-validated">

										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Chỉnh sửa vị trí kệ</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>

										<div class="modal-body">
											<div class="mb-3">
												<label class="form-label">Tên vị trí kệ</label>
												<input type="text" class="form-control" id="location_rack_name" name="location_rack_name" value="<?=htmlspecialchars($location_rack->location_rack_name)?>" placeholder="Tên vị trí kệ" required></input>
												<div class="invalid-feedback">
													Vui lòng nhập vào tên vị trí kệ.
												</div>
											</div>
										</div>

										<div class="modal-footer">
											<input type="hidden" name="location_rack_id" value="<?=htmlspecialchars($location_rack->getId())?>" />
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
											<input type="submit" name="edit_location_rack" id="edit_location_rack" class="btn btn-success" value="Chỉnh sửa"/>
										</div>
										
									</form>
								</div>
							</div>
						</div>
					</tr>

				<?php endforeach ?>	

				</tbody>
			</table>
		</div>
	</div>

</div>

<?php include 'partials_admin/footer.php'; ?>