<?php
	include "../../bootstrap.php";
	include '../function.php';

	if(!is_admin_login())
	{
		header('location:admin_login.php');
	}

	use CT275\Nienluannganh\Publisher;

	$errors = [];
	$publisher = new Publisher($PDO);
	// Thêm và chỉnh sửa (combo)
	if($_SERVER['REQUEST_METHOD'] === 'POST') {

		if($_POST['add_publisher']) {
			$publisher->update($_POST);
			if($publisher->update($_POST)) {
				header("Location: publisher.php?msg=add");
			}
		}
		else if ($_POST['edit_publisher']) {

			//Kiểm tra id hợp lệ (có thể bỏ)
			$publisher_id = isset($_REQUEST['publisher_id']) ?
			filter_var($_REQUEST['publisher_id'], FILTER_SANITIZE_NUMBER_INT) : -1;

			if ($publisher_id < 0 || !($publisher->find($publisher_id))) {
				header("Location: publisher.php"); 
			}
			else{
				$publisher->update($_POST);
				if($publisher->update($_POST)) {
					header("Location: publisher.php?msg=edit");
				}
			}
			
		}
		$errors = $publisher->getValidationErrors();
	}
	
	//Xóa 
	if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['publisher_id'],$_GET['action']) && $_GET["action"] == 'delete' && ($publisher->find($_GET['publisher_id'])) !== null){
		$publisher->delete_publisher();
		if($publisher->delete_publisher()){
			header("Location: publisher.php?msg=delete");
		}
	}
	
	//On/off
	if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['publisher_id'],$_GET['action']) && $_GET["action"] == 'change' && ($publisher->find($_GET['publisher_id'])) !== null){
		$publisher->update_status_publisher();
	}

?>

<?php include 'partials_admin/header.php'; ?>

<div class="container-fluid py-4" style="min-height: 700px;">
	<h1>Quản lí nhà xuất bản</h1>

	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
		<li class="breadcrumb-item active">Quản lí nhà xuất bản</li>
	</ol>

	<?php 
	if(isset($_GET["msg"]))
	{
		//Add
		if($_GET["msg"] == 'add')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">nhà xuất bản đã được tạo<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		//Edit
		if($_GET['msg'] == 'edit')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Nhà xuất bản đã được cập nhật<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		//Delete
		if($_GET['msg'] == 'delete')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Nhà xuất bản đã được xóa<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		//Disable
		if($_GET["msg"] == 'disable')
		{
			echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Trạng thái nhà xuất bản đã chuyển sang chế độ vô hiệu hóa<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		//Enable
		if($_GET["msg"] == 'enable')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Trạng thái nhà xuất bản đã chuyển sang chế độ kích hoạt<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
	}
	?>
	
	<?php 
		if (isset($errors['publisher_name'])) {
			echo '<div class="alert alert-danger">'.$errors['publisher_name'].'</div>';
		}
	?>

	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i>Quản lí nhà xuất bản
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
							<form action="publisher.php" method="POST" id="form_publisher_add" class="was-validated">

								<div class="modal-header">
									<h1 class="modal-title fs-5" id="exampleModalLabel">Thêm nhà xuất bản</h1>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<div class="mb-3">
										<label class="form-label">Tên nhà xuất bản</label>
										<input class="form-control" id="publisher_name" name="publisher_name" placeholder="Tên nhà xuất bản" required></input>
										<div class="invalid-feedback">
											Vui lòng nhập vào tên nhà xuất bản.
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
									<input type="submit" name="add_publisher" id="add_publisher" class="btn btn-success" value="Thêm"/>
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
						<th>Tên nhà xuất bản</th>
						<th>Trạng thái</th>
						<th>Thao tác</th>
					</tr>
				</thead>
				<tbody id="load_data">

				<?php 
					$publishers = $publisher->all();
					foreach($publishers as $publisher):

						$publisher_status = "";
						if($publisher->publisher_status == 'Enable')
						{
							$publisher_status = '<div class="badge bg-success">Kích hoạt</div>';
						}
						else
						{
							$publisher_status = '<div class="badge bg-danger">Vô hiệu hóa</div>';
						}
				?>

					<tr>
						<td><?=htmlspecialchars($publisher->publisher_name)?></td>
						<td><?=$publisher_status?></td>
						<td>
							<a href="publisher.php?publisher_id=<?=htmlspecialchars($publisher->getId())?>"  type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#publisher<?=htmlspecialchars($publisher->getId())?>">
								<i class="fas fa-edit me-1"></i>
							</a>
							<a href="publisher.php?action=delete&publisher_id=<?=htmlspecialchars($publisher->getId())?>" onclick = "return confirm('Bạn chắc chứ?')"  class="btn btn-sm btn-danger">
								<i class="fas fa-trash mx-1"></i>
							</a>
							<a href="publisher.php?action=change&publisher_id=<?=htmlspecialchars($publisher->getId())?>" onclick = "return confirm('Bạn chắc chứ?')"  class="btn btn-sm btn-warning">
								<i class="fas fa-power-off mx-1"></i>
							</a>
						</td>

						<!-- Modal edit-->
						<div class="modal fade" id="publisher<?=htmlspecialchars($publisher->getId())?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<form action="publisher.php" method="POST" id="form_publisher_edit" class="was-validated">

										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Chỉnh sửa nhà xuất bản</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>

										<div class="modal-body">
											<div class="mb-3">
												<label class="form-label">Tên nhà xuất bản</label>
												<input type="text" class="form-control" id="publisher_name" name="publisher_name" value="<?=htmlspecialchars($publisher->publisher_name)?>" placeholder="Tên nhà xuất bản" required></input>
												<div class="invalid-feedback">
													Vui lòng nhập vào tên nhà xuất bản.
												</div>
											</div>
										</div>

										<div class="modal-footer">
											<input type="hidden" name="publisher_id" value="<?=htmlspecialchars($publisher->getId())?>" />
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
											<input type="submit" name="edit_publisher" id="edit_publisher" class="btn btn-success" value="Chỉnh sửa"/>
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