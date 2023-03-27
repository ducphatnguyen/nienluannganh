<?php
	include "../../bootstrap.php";
	include '../function.php';

	if(!is_admin_login())
	{
		header('location:admin_login.php');
	}

	use CT275\Nienluannganh\Book;
	use CT275\Nienluannganh\Author;
	use CT275\Nienluannganh\Publisher;
	use CT275\Nienluannganh\Category;
	use CT275\Nienluannganh\Location_rack;
	

	$errors = [];
	$book = new Book($PDO);
	$author = new Author($PDO);
	$publisher = new Publisher($PDO);
	$category = new Category($PDO);
	$location_rack = new Location_rack($PDO);

	// Thêm và chỉnh sửa (combo)
	if($_SERVER['REQUEST_METHOD'] === 'POST') {

		if($_POST['add_book']) {
			$book->update($_POST, $_FILES);
			if($book->update($_POST, $_FILES)) {
				header("Location: book.php?msg=add");
			}
		}

		else if ($_POST['edit_book']) {

			//Kiểm tra id hợp lệ (có thể bỏ)
			$book_id = isset($_REQUEST['book_id']) ?
			filter_var($_REQUEST['book_id'], FILTER_SANITIZE_NUMBER_INT) : -1;

			if ($book_id < 0 || !($book->find($book_id))) {
				header("Location: book.php"); 
			}
			else{
				$book->update($_POST, $_FILES);
				if($book->update($_POST, $_FILES)) {
					header("Location: book.php?msg=edit");
				}
			}
			
		}
		$errors = $book->getValidationErrors();
	}

	//Xóa 
	if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['book_id'],$_GET['action']) && $_GET["action"] == 'delete' && ($book->find($_GET['book_id'])) !== null){
		$book->delete_book();
		if($book->delete_book()) {
			header("Location: book.php?msg=delete");
		}
	}
	
	//On/off
	if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['book_id'],$_GET['action']) && $_GET["action"] == 'change' && ($book->find($_GET['book_id'])) !== null){
		$book->update_status_book();
	}


?>

<?php include 'partials_admin/header.php'; ?>

<div class="container-fluid py-4" style="min-height: 700px;">
	<h1>Quản lí sách</h1>
	
	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
		<li class="breadcrumb-item active">Quản lí sách</li>
	</ol>

	<?php 
	if(isset($_GET["msg"]))
	{
		if($_GET["msg"] == 'add')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Sách mới đã được tạo<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		if($_GET['msg'] == 'edit')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Dữ liệu sách đã được cập nhật<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		if($_GET['msg'] == 'delete')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Dữ liệu sách đã được xóa<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		if($_GET["msg"] == 'disable')
		{
			echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Trạng thái sách đã chuyển sang chế độ vô hiệu hóa<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		if($_GET['msg'] == 'enable')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Trạng thái sách đã chuyển sang chế độ kích hoạt<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
	}
	?>

	<?php 
		if (isset($errors['book_name'])) {
			echo '<div class="alert alert-danger">'.$errors['book_name'].'</div>';
		}
	?>
	<?php 
		if (isset($errors['book_no_of_copy'])) {
			echo '<div class="alert alert-danger">'.$errors['book_no_of_copy'].'</div>';
		}
	?>

	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i>Quản lí sách
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
							<form action="book.php" method="POST" enctype="multipart/form-data" id="form_book_add" class="was-validated">

								<div class="modal-header">
									<h1 class="modal-title fs-5" id="exampleModalLabel">Thêm sách</h1>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>

								<div class="modal-body">
									<!-- 1 -->
									<div class="mb-3">
										<label class="form-label">Tên sách</label>
										<input type="text" class="form-control" id="book_name" name="book_name" placeholder="Tên sách" required></input>
										<div class="invalid-feedback">
											Vui lòng nhập vào tên sách.
										</div>
									</div>
									<!-- 2 -->
									<div class="mb-3">
										<label class="form-label">Chọn tác giả</label>
										<select name="author_id" id="author_id" required>
											<option hidden value="" selected>Chọn tác giả</option>
											<?php 
												$authors = $author->all_enable();
												foreach($authors as $author): 
											?>
											<option value="<?=htmlspecialchars($author->getId())?>">
												<?=htmlspecialchars($author->author_name)?>
											</option>
											<?php endforeach ?>	
										</select>
										<div class="invalid-feedback">
											Vui lòng chọn tác giả.
										</div>
									</div>

									<!-- 2 -->
									<div class="mb-3">
										<label class="form-label">Chọn nhà xuất bản</label>
										<select name="publisher_id" id="publisher_id"  required>
											<option hidden value="" selected>Chọn nhà xuất bản</option>
											<?php 
												$publishers = $publisher->all_enable();
												foreach($publishers as $publisher): 
											?>
											<option value="<?=htmlspecialchars($publisher->getId())?>">
												<?=htmlspecialchars($publisher->publisher_name)?>
											</option>
											<?php endforeach ?>	
										</select>
										<div class="invalid-feedback">
											Vui lòng chọn nhà xuất bản.
										</div>
									</div>

									<!-- 3 -->
									<div class="mb-3">
										<label class="form-label">Chọn thể loại</label>
										<select name="category_id" id="category_id" required>
											<option hidden value="" selected>Chọn thể loại</option>
											<?php 
												$categories = $category->all_enable();
												foreach($categories as $category): 
											?>
											<option value="<?=htmlspecialchars($category->getId())?>">
												<?=htmlspecialchars($category->category_name)?>
											</option>
											<?php endforeach ?>	
										</select>
										<div class="invalid-feedback">
											Vui lòng chọn thể loại.
										</div>
									</div>
									<!-- 4 -->
									<div class="mb-3">
										<label class="form-label">Chọn vị trí kệ</label>
										<select name="location_rack_id" id="location_id" required>
											<option hidden value="" selected>Chọn vị trí kệ</option>
											<?php 
												$location_racks = $location_rack->all_enable();
												foreach($location_racks as $location_rack): 
											?>
											<option value="<?=htmlspecialchars($location_rack->getId())?>">
												<?=htmlspecialchars($location_rack->location_rack_name)?>
											</option>
											<?php endforeach ?>	
										</select>
										<div class="invalid-feedback">
											Vui lòng chọn vị trí kệ.
										</div>
									</div>
									<!-- 5 -->
									<div class="mb-3">
										<label class="form-label">Mã sách</label>
										<input type="text" class="form-control" id="book_isbn_number" name="book_isbn_number" placeholder="Mã sách" required></input>
										<div class="invalid-feedback">
											Vui lòng nhập vào mã sách.
										</div>
									</div>
									<!-- 6 -->
									<div class="mb-3">
										<label class="form-label">Số bản in</label>
										<input type="number" step="1" class="form-control" id="book_no_of_copy" name="book_no_of_copy" placeholder="Số bản in" required></input>
										<div class="invalid-feedback">
											Vui lòng nhập vào số bản in sách.
										</div>
									</div>

									<!-- 7 -->
									<div class="mb-3">
										<label class="form-label">Giá</label>
										<input type="number" step="1" class="form-control" id="book_price" name="book_price" placeholder="Giá sách" required></input>
										<div class="invalid-feedback">
											Vui lòng nhập vào giá sách.
										</div>
									</div>

									<!-- 8 -->
									<div class="mb-3">
										<label for="validationTextarea" class="form-label">Mô tả sách</label>
										<textarea class="form-control" id="validationTextarea" name="book_description" placeholder="Mô tả sách" required></textarea>
										<div class="invalid-feedback">
											Vui lòng nhập vào mô tả sách.
										</div>
									</div>

									<!-- 9 -->
									<div class="mb-3">
										<input type="file" class="form-control" aria-label="file example" name="book_image" required>
										<div class="invalid-feedback">Vui lòng chọn ảnh sách</div>
									</div>

								</div>

								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
									<input type="submit" name="add_book" id="add_book" class="btn btn-success" value="Thêm"/>
								</div>
								
							</form>
						</div>
					</div>
				</div>
            </div>
        </div>
		
        <div class="card-body">
        	<table id="datatablesSimple" class="table-striped">
        		<thead> 
        			<tr> 
						<th>Ảnh</th>
        				<th>Tên sách</th>
        				<th>Mã sách</th>
        				<th>Thể loại</th>
        				<th>Tác giả</th>
						<th>NXB</th>
        				<th>Vị trí</th>
        				<th>Số bản in</th>
						<th>Giá</th>
						<th>Mô tả</th>
        				<th>Trạng thái</th>
        				<th>Thao tác</th>
        			</tr>
        		</thead>

        		<tbody>
						<?php 
							$books = $book->all();
							foreach($books as $book):

								$book_status = "";
								if($book->book_status == 'Enable')
								{
									$book_status = '<div class="badge bg-success">Kích hoạt</div>';
								}
								else
								{
									$book_status = '<div class="badge bg-danger">Vô hiệu hóa</div>';
								}
						?>
        				
        				<tr>
							<td><img src="uploads/<?=htmlspecialchars($book->book_image)?>" width="60"/></td>
        					<td><?=htmlspecialchars($book->book_name)?></td>
        					<td><?=htmlspecialchars($book->book_isbn_number)?></td>
        					<td><?=$category->find(htmlspecialchars($book->category_id))->category_name?></td>
        					<td><?=$author->find(htmlspecialchars($book->author_id))->author_name?></td>
							<td><?=$publisher->find(htmlspecialchars($book->publisher_id))->publisher_name?></td>
        					<td><?=$location_rack->find(htmlspecialchars($book->location_rack_id))->location_rack_name?></td>
        					<td><?=htmlspecialchars($book->book_no_of_copy)?></td>
							<td><?=htmlspecialchars($book->book_price). " VNĐ"?> </td>
							<td><?=htmlspecialchars($book->book_description)?></td>
        					<td><?=$book_status?></td>
        					<td>
								<a href="book.php?book_id=<?=htmlspecialchars($book->getId())?>"  type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#book<?=htmlspecialchars($book->getId())?>">
									<i class="fas fa-edit me-1"></i>
								</a>
								<a href="book.php?action=delete&book_id=<?=htmlspecialchars($book->getId())?>" onclick = "return confirm('Bạn chắc chứ?')"  class="btn btn-sm btn-danger">
									<i class="fas fa-trash mx-1"></i>
								</a>
        						<a href="book.php?action=change&book_id=<?=htmlspecialchars($book->getId())?>" onclick = "return confirm('Bạn chắc chứ?')"  class="btn btn-sm btn-warning">
									<i class="fas fa-power-off mx-1"></i>
								</a>
        					</td>
							

							<div class="modal fade" id="book<?=htmlspecialchars($book->getId())?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<form action="book.php" method="POST" enctype="multipart/form-data" id="form_book_edit" class="was-validated">

											<div class="modal-header">
												<h1 class="modal-title fs-5" id="exampleModalLabel">Chỉnh sửa tác giả</h1>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>

											<div class="modal-body">
												<!-- 1 -->
												<div class="mb-3">
													<label class="form-label">Tên sách</label>
													<input type="text" class="form-control" id="book_name" name="book_name" placeholder="Tên sách" value="<?=htmlspecialchars($book->book_name)?>" required></input>
													<div class="invalid-feedback">
														Vui lòng nhập vào tên sách.
													</div>
												</div>
												<!-- 2 -->
												<div class="mb-3">
													<label class="form-label">Chọn tác giả</label>
													<select name="author_id" id="author_id"  required>
													<option hidden value="" selected>Chọn tác giả</option>
														<?php 
															$authors = $author->all_enable();
															foreach($authors as $author): 
														?>
														<option <?php if($book->author_id == $author->getId()) { echo 'selected';} ?> value="<?=htmlspecialchars($author->getId())?>">
															<?=htmlspecialchars($author->author_name)?>
														</option>
														<?php endforeach ?>
													</select>
													<div class="invalid-feedback">
														Vui lòng chọn tác giả.
													</div>
												</div>

												<!-- 2 -->
												<div class="mb-3">
													<label class="form-label">Chọn nhà xuất bản</label>
													<select name="publisher_id" id="publisher_id"  required>
													<option hidden value="" selected>Chọn nhà xuất bản</option>
														<?php 
															$publishers = $publisher->all_enable();
															foreach($publishers as $publisher): 
														?>
														<option <?php if($book->publisher_id == $publisher->getId()) { echo 'selected';} ?> value="<?=htmlspecialchars($publisher->getId())?>">
															<?=htmlspecialchars($publisher->publisher_name)?>
														</option>
														<?php endforeach ?>
													</select>
													<div class="invalid-feedback">
														Vui lòng chọn tác giả.
													</div>
												</div>

												<!-- 3 -->
												<div class="mb-3">
													<label class="form-label">Chọn thể loại</label>
													<select name="category_id" id="category_id"  required>
														<option hidden value="" selected>Chọn thể loại</option>
														<?php 
															$categories = $category->all_enable();
															foreach($categories as $category): 
														?>
														<option <?php if($book->category_id == $category->getId()) { echo 'selected';} ?> value="<?=htmlspecialchars($category->getId())?>">
															<?=htmlspecialchars($category->category_name)?>
														</option>
														<?php endforeach ?>	

													</select>
													<div class="invalid-feedback">
														Vui lòng chọn thể loại.
													</div>
												</div>
												<!-- 4 -->
												<div class="mb-3">
													<label class="form-label">Chọn vị trí kệ</label>
													<select name="location_rack_id" id="location_rack_id"  required>
														<option hidden value="" selected>Chọn vị trí kệ</option>
														<?php 
															$location_racks = $location_rack->all_enable();
															foreach($location_racks as $location_rack): 
														?>
														<option <?php if($book->location_rack_id == $location_rack->getId()) { echo 'selected';} ?> value="<?=htmlspecialchars($location_rack->getId())?>">
															<?=htmlspecialchars($location_rack->location_rack_name)?>
														</option>
														<?php endforeach ?>
													</select>
													<div class="invalid-feedback">
														Vui lòng chọn vị trí kệ.
													</div>
												</div>
												
												<!-- 5 -->
												<div class="mb-3">
													<label class="form-label">Mã sách</label>
													<input type="text" class="form-control" id="book_isbn_number" name="book_isbn_number" placeholder="Mã sách" value="<?=htmlspecialchars($book->book_isbn_number)?>" required></input>
													<div class="invalid-feedback">
														Vui lòng nhập vào mã sách.
													</div>
												</div>

												<!-- 6 -->
												<div class="mb-3">
													<label class="form-label">Số bản in</label>
													<input type="number" step="1" min="1" max="100" class="form-control" id="book_no_of_copy" name="book_no_of_copy" value="<?=htmlspecialchars($book->book_no_of_copy)?>" placeholder="Số bản in" required></input>
													<div class="invalid-feedback">
														Vui lòng nhập vào số bản in sách.
													</div>
												</div>

												<!-- 7 -->
												<div class="mb-3">
													<label class="form-label">Giá</label>
													<input type="number" step="1" class="form-control" id="book_price" name="book_price" value="<?=htmlspecialchars($book->book_price)?>" placeholder="Giá sách" required></input>
													<div class="invalid-feedback">
														Vui lòng nhập vào giá sách.
													</div>
												</div>

												<!-- 8 -->
												<div class="mb-3">
													<label for="validationTextarea" class="form-label">Mô tả sách</label>
													<textarea class="form-control" id="validationTextarea" name="book_description" placeholder="Mô tả sách" required><?=htmlspecialchars($book->book_description)?></textarea>
													<div class="invalid-feedback">
														Vui lòng nhập vào mô tả sách.
													</div>
												</div>

												<!-- 9 -->
												<div class="mb-3">
													<input type="file" class="form-control" aria-label="file example" name="book_image" required>
													<div class="invalid-feedback">Vui lòng chọn ảnh sách</div>
													<img src="uploads/<?=htmlspecialchars($book->book_image)?>" width="60"/>
												</div>
												
											</div>

											<div class="modal-footer">
												<!-- Xem -->
												<input type="hidden" name="book_id" value="<?=htmlspecialchars($book->getId())?>" />
												<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
												<input type="submit" name="edit_book" id="edit_book" class="btn btn-success" value="Chỉnh sửa"/>
											</div>

										</form>
									</div>
								</div>
							</div>
        				</tr>       			
        		</tbody>
				<?php endforeach ?>	
        	</table>
        </div>
    </div>
</div>

<?php include 'partials_admin/footer.php'; ?>