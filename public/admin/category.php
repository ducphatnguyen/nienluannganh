<?php
	include "../../bootstrap.php";
	include '../function.php';

	if(!is_admin_login())
	{
		header('location:admin_login.php');
	}

	use CT275\Nienluannganh\Category;

	$errors = [];
	$category = new Category($PDO);
	// Thêm và chỉnh sửa (combo)
	if($_SERVER['REQUEST_METHOD'] === 'POST') {

		if($_POST['add_category']) {
			$category->update($_POST);
			if($category->update($_POST)) {
				header("Location: category.php?msg=add");
			}
		}
		else if ($_POST['edit_category']) {

			//Kiểm tra id hợp lệ (có thể bỏ)
			$category_id = isset($_REQUEST['category_id']) ?
			filter_var($_REQUEST['category_id'], FILTER_SANITIZE_NUMBER_INT) : -1;

			if ($category_id < 0 || !($category->find($category_id))) {
				header("Location: category.php"); 
			}
			else{
				$category->update($_POST);
				if($category->update($_POST)) {
					header("Location: category.php?msg=edit");
				}
			}
			
		}
		$errors = $category->getValidationErrors();
	}
	
	//Xóa 
	if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['category_id'],$_GET['action']) && $_GET["action"] == 'delete' && ($category->find($_GET['category_id'])) !== null){
		$category->delete_category();
		if($category->delete_category()) {
			header("Location: category.php?msg=delete");
		}
	}
	
	//On/off
	if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['category_id'],$_GET['action']) && $_GET["action"] == 'change' && ($category->find($_GET['category_id'])) !== null){
		$category->update_status_category();
	}

?>

<?php include 'partials_admin/header.php'; ?>

<div class="container-fluid py-4" style="min-height: 700px;">
	<h1>Quản lí thể loại</h1>

	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
		<li class="breadcrumb-item active">Quản lí thể loại</li>
	</ol>

	<?php 
	if(isset($_GET["msg"]))
	{
		//Add
		if($_GET["msg"] == 'add')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Thể loại đã được tạo<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		//Edit
		if($_GET['msg'] == 'edit')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Thể loại đã được cập nhật<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		//Delete
		if($_GET['msg'] == 'delete')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Thể loại đã được xóa<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		//Disable
		if($_GET["msg"] == 'disable')
		{
			echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Trạng thái thể loại đã chuyển sang chế độ vô hiệu hóa<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		//Enable
		if($_GET["msg"] == 'enable')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Trạng thái thể loại đã chuyển sang chế độ kích hoạt<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
	}
	?>
	
	<?php 
		if (isset($errors['category_name'])) {
			echo '<div class="alert alert-danger">'.$errors['category_name'].'</div>';
		}
	?>

	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i>Quản lí thể loại
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
							<form action="category.php" method="POST" id="form_category_add" class="was-validated">

								<div class="modal-header">
									<h1 class="modal-title fs-5" id="exampleModalLabel">Thêm thể loại</h1>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<div class="mb-3">
										<label class="form-label">Tên thể loại</label>
										<input class="form-control" id="category_name" name="category_name" placeholder="Tên thể loại" required></input>
										<div class="invalid-feedback">
											Vui lòng nhập vào tên thể loại.
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
									<input type="submit" name="add_category" id="add_category" class="btn btn-success" value="Thêm"/>
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
						<th>Tên thể loại</th>
						<th>Trạng thái</th>
						<th>Thao tác</th>
					</tr>
				</thead>
				<tbody id="load_data">

				<?php 
					$categories = $category->all();
					foreach($categories as $category):

						$category_status = "";
						if($category->category_status == 'Enable')
						{
							$category_status = '<div class="badge bg-success">Kích hoạt</div>';
						}
						else
						{
							$category_status = '<div class="badge bg-danger">Vô hiệu hóa</div>';
						}
				?>

					<tr>
						<td><?=htmlspecialchars($category->category_name)?></td>
						<td><?=$category_status?></td>
						<td>
							<a href="category.php?category_id=<?=htmlspecialchars($category->getId())?>"  type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#category<?=htmlspecialchars($category->getId())?>">
								<i class="fas fa-edit me-1"></i>
							</a>
							<a href="category.php?action=delete&category_id=<?=htmlspecialchars($category->getId())?>" onclick = "return confirm('Bạn chắc chứ?')"  class="btn btn-sm btn-danger">
								<i class="fas fa-trash mx-1"></i>
							</a>
							<a href="category.php?action=change&category_id=<?=htmlspecialchars($category->getId())?>" onclick = "return confirm('Bạn chắc chứ?')"  class="btn btn-sm btn-warning">
								<i class="fas fa-power-off mx-1"></i>
							</a>
						</td>

						<!-- Modal edit-->
						<div class="modal fade" id="category<?=htmlspecialchars($category->getId())?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<form action="category.php" method="POST" id="form_category_edit" class="was-validated">

										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Chỉnh sửa thể loại</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>

										<div class="modal-body">
											<div class="mb-3">
												<label class="form-label">Tên thể loại</label>
												<input type="text" class="form-control" id="category_name" name="category_name" value="<?=htmlspecialchars($category->category_name)?>" placeholder="Tên thể loại" required></input>
												<div class="invalid-feedback">
													Vui lòng nhập vào tên thể loại.
												</div>
											</div>
										</div>

										<div class="modal-footer">
											<input type="hidden" name="category_id" value="<?=htmlspecialchars($category->getId())?>" />
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
											<input type="submit" name="edit_category" id="edit_category" class="btn btn-success" value="Chỉnh sửa"/>
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