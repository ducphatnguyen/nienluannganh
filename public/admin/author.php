<?php
	include "../../bootstrap.php";
	include '../function.php';

	if(!is_admin_login())
	{
		header('location:admin_login.php');
	}

	use CT275\Nienluannganh\Author;

	$errors = [];
	$author = new Author($PDO);
	// Thêm và chỉnh sửa (combo)
	if($_SERVER['REQUEST_METHOD'] === 'POST') {

		if($_POST['add_author']) {
			$author->update($_POST);
			if($author->update($_POST)) {
				header("Location: author.php?msg=add");
			}
		}
		else if ($_POST['edit_author']) {
			//Kiểm tra id hợp lệ (có thể bỏ)
			$author_id = isset($_REQUEST['author_id']) ?
			filter_var($_REQUEST['author_id'], FILTER_SANITIZE_NUMBER_INT) : -1;

			if ($author_id < 0 || !($author->find($author_id))) {
				header("Location: author.php"); 
			}
			else{
				$author->update($_POST);
				if($author->update($_POST)) {
					header("Location: author.php?msg=edit");
				}
			}
			
		}
		$errors = $author->getValidationErrors();
	}
	
	//Xóa 
	if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['author_id'],$_GET['action']) && $_GET["action"] == 'delete' && ($author->find($_GET['author_id'])) !== null){
		$author->delete_author();
		if($author->delete_author()) {
			header("Location: author.php?msg=delete");
		}
	}
	
	//On/off
	if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['author_id'],$_GET['action']) && $_GET["action"] == 'change' && ($author->find($_GET['author_id'])) !== null){
		$author->update_status_author();
	}

?>

<?php include 'partials_admin/header.php'; ?>

<div class="container-fluid py-4" style="min-height: 700px;">
	<h1>Quản lí tác giả</h1>

	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
		<li class="breadcrumb-item active">Quản lí tác giả</li>
	</ol>

	<?php 
	if(isset($_GET["msg"]))
	{
		//Add
		if($_GET["msg"] == 'add')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Tác giả đã được tạo<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		//Edit
		if($_GET['msg'] == 'edit')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Tác giả đã được cập nhật<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		//Delete
		if($_GET['msg'] == 'delete')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Tác giả đã được xóa<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		//Disable
		if($_GET["msg"] == 'disable')
		{
			echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Trạng thái tác giả đã chuyển sang chế độ vô hiệu hóa<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		//Enable
		if($_GET["msg"] == 'enable')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Trạng thái tác giả đã chuyển sang chế độ kích hoạt<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
	}
	?>
	
	<?php 
		if (isset($errors['author_name'])) {
			echo '<div class="alert alert-danger">'.$errors['author_name'].'</div>';
		}
	?>

	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i>Quản lí tác giả
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
							<form action="author.php" method="POST" id="form_author_add" class="was-validated">

								<div class="modal-header">
									<h1 class="modal-title fs-5" id="exampleModalLabel">Thêm tác giả</h1>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<div class="mb-3">
										<label class="form-label">Tên tác giả</label>
										<input class="form-control" id="author_name" name="author_name" placeholder="Tên tác giả" required></input>
										<div class="invalid-feedback">
											Vui lòng nhập vào tên tác giả.
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
									<input type="submit" name="add_author" id="add_author" class="btn btn-success" value="Thêm"/>
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
						<th>Tên tác giả</th>
						<th>Trạng thái</th>
						<th>Thao tác</th>
					</tr>
				</thead>
				<tbody id="load_data">

				<?php 
					$authors = $author->all();
					foreach($authors as $author):

						$author_status = "";
						if($author->author_status == 'Enable')
						{
							$author_status = '<div class="badge bg-success">Kích hoạt</div>';
						}
						else
						{
							$author_status = '<div class="badge bg-danger">Vô hiệu hóa</div>';
						}
				?>

					<tr>
						<td><?=htmlspecialchars($author->author_name)?></td>
						<td><?=$author_status?></td>
						<td>
							<a href="author.php?author_id=<?=htmlspecialchars($author->getId())?>"  type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#author<?=htmlspecialchars($author->getId())?>">
								<i class="fas fa-edit me-1"></i>
							</a>
							<a href="author.php?action=delete&author_id=<?=htmlspecialchars($author->getId())?>" onclick = "return confirm('Bạn chắc chứ?')"  class="btn btn-sm btn-danger">
								<i class="fas fa-trash mx-1"></i>
							</a>
							<a href="author.php?action=change&author_id=<?=htmlspecialchars($author->getId())?>" onclick = "return confirm('Bạn chắc chứ?')"  class="btn btn-sm btn-warning">
								<i class="fas fa-power-off mx-1"></i>
							</a>
						</td>

						<!-- Modal edit-->
						<div class="modal fade" id="author<?=htmlspecialchars($author->getId())?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<form action="author.php" method="POST" id="form_author_edit" class="was-validated">

										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Chỉnh sửa tác giả</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>

										<div class="modal-body">
											<div class="mb-3">
												<label class="form-label">Tên tác giả</label>
												<input type="text" class="form-control" id="author_name" name="author_name" value="<?=htmlspecialchars($author->author_name)?>" placeholder="Tên tác giả" required></input>
												<div class="invalid-feedback">
													Vui lòng nhập vào tên tác giả.
												</div>
											</div>
										</div>

										<div class="modal-footer">
											<input type="hidden" name="author_id" value="<?=htmlspecialchars($author->getId())?>" />
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
											<input type="submit" name="edit_author" id="edit_author" class="btn btn-success" value="Chỉnh sửa"/>
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