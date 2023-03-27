<?php
	include "../../bootstrap.php";
	include '../function.php';

	if(!is_admin_login())
	{
		header('location:admin_login.php');
	}
	
	use CT275\Nienluannganh\Issue_book;
	use CT275\Nienluannganh\Book;
	use CT275\Nienluannganh\User;
	use CT275\Nienluannganh\Setting;

	$issue_book = new Issue_book($PDO);
	$book = new Book($PDO);
	$user = new User($PDO);
	$setting = new Setting($PDO);

	$error = "";
	// Thêm và chỉnh sửa (combo)
	if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_issue_book'])) {

		$issue_book->fill($_POST);

		$book_issue_limit = $setting->get_book_issue_limit_per_user();
		$total_book_issue = $issue_book->get_total_book_issue_per_user();

		if($issue_book->validate()) {
			
			if($total_book_issue < $book_issue_limit) {
				if($book->find($issue_book->book_id)->book_no_of_copy <= 0) {
					$error .= '<li>Số bản in sách đã hết, không thể cho mượn!</li>';
				}
				else {
					$issue_book->save();

					$book_id = $_POST['book_id'];
					$book->find($book_id); 
					$book->update_subtract_quantity_book();
				}
			}
			else {
				$error .= '<li>Người dùng đã vượt quá số lần mượn sách, vui lòng trả sách để có thể mượn tiếp!</li>';
			}

			$errors = $issue_book->getValidationErrors();
			
		}
	}

?>

<?php include 'partials_admin/header.php'; ?>

<div class="container-fluid py-4" style="min-height: 700px;">
	<h1>Quản lí mượn trả</h1>
    
    
	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Quản lí mượn trả</li>
    </ol>

    <?php 
    if(isset($_GET['msg']))
    {
        if($_GET['msg'] == 'add')
        {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Sách đã mượn thành công<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }

        if($_GET["msg"] == 'return')
        {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Trả sách về thư viện thành công<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }

		if($_GET["msg"] == 'damaged_return')
        {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Trả sách hỏng về thư viện thành công<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }

		if($_GET["msg"] == 'lost')
        {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Xử lý mất sách thành công<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }
    }
    ?>
	
	<?php
		if($error != "")
		{
			echo '<div class="alert alert-danger">'.$error.'</div>';
		}
	?>


    <div class="card mb-4">
    	<div class="card-header">
    		<div class="row">

    			<div class="col col-md-6">
    				<i class="fas fa-table me-1"></i>Quản lí mượn trả
                </div>

                <div class="col col-md-6" align="right">
					<a href="excel_export.php" type="button" class="btn btn-success btn-sm" target="_blank">
						<i class="fas fa-file-excel"></i> Xuất Excel
					</a>
					<a type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
						<i class="fas fa-plus me-1"></i>Thêm
					</a>
                </div>

				<!-- Modal add-->
				<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<form id="form" action="issue_book.php" method="POST" id="form_issue_book_add" class="was-validated">

								<div class="modal-header">
									<h1 class="modal-title fs-5" id="exampleModalLabel">Thêm mượn trả</h1>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								
								<div class="modal-body">
									<div class="mb-3">
										<label class="form-label">Chọn mã sách</label>
										<select name="book_id" required>
											<option hidden value="" selected>Chọn mã sách</option>
											<?php 
												$books = $book->all_enable();
												foreach($books as $book): 
											?>
											<option value="<?=htmlspecialchars($book->getId())?>">
												<?=htmlspecialchars($book->book_isbn_number)?>
											</option>
											<?php endforeach ?>	
										</select>
										<div class="invalid-feedback">
											Vui lòng chọn mã sách.
										</div>
									</div>

									<div class="mb-3">
										<label class="form-label">Chọn mã người dùng</label>
										<select name="user_id" id="select" required>
											<option hidden value="" selected>Chọn mã người dùng</option>
											<?php 
												$users = $user->all_enable();
												foreach($users as $user): 
											?>
											<option value="<?=htmlspecialchars($user->getId())?>">
												<?=htmlspecialchars($user->user_unique_id)?>
											</option>
											<?php endforeach ?>	
										</select>
										<div class="invalid-feedback">
											Vui lòng chọn mã người dùng.
										</div>
									</div>
								</div>

								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
									<input id="btnTestForm" type="submit" name="add_issue_book" id="add_issue_book" class="btn btn-success" value="Thêm"/>
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
        				<th>Mã sách</th>
                        <th>UID</th>
                        <th>Ngày mượn</th>
						<th>Hạn trả</th>
                        <th>Ngày xử lý hoàn trả</th>
                        <th>Tiền trả trễ/bồi thường</th>
                        <th>Trạng thái</th>
                        <th>View</th>
        			</tr>
        		</thead>
        		<tbody>
				
				<!-- Kiểm tra -->
        		<?php
					$one_day_fine = $setting->get_one_day_fines();

					$issue_books = $issue_book->all();

					foreach($issue_books as $issue_book):

						$status = $issue_book->book_issue_status;
						
						$book_fines = $issue_book->book_fines;

						//Đang mượn
						if($issue_book->book_issue_status == "Issue" || $issue_book->book_issue_status == "Not Return")
						{
							$current_date_time = new DateTime(date('Y-m-d H:i:s'));
							
							$expected_return_date = new DateTime($issue_book->expected_return_date);
							
							// Trả quá hạn
							if($current_date_time > $expected_return_date)
							{
								//Lay ra so ngay tra tre (ngay hien tai) - (han tra)
								$interval = $current_date_time->diff($expected_return_date);
								
								$total_day = ($interval->d) + 1;
								
								$book_fines = $total_day * $one_day_fine;
								
								$issue_book_id = $issue_book->getId();

								$issue_book->update_status_not_return_issue_book($book_fines,$issue_book_id);
								
							}
							
						}	


						if($status == 'Issue')
						{
							$status = '<span class="badge bg-warning">Mượn</span>';
						}

						if($status == 'Not Return')
						{
							$status = '<span class="badge bg-danger">Trễ hạn</span>';
						}

						if($status == 'Return')
						{
							$status = '<span class="badge bg-primary">Hoàn trả</span>';
						}

						if($status == 'Damaged Return')
						{
							$status = '<span class="badge bg-secondary">Hoàn trả (hỏng)</span>';
						}

						if($status == 'Lost')
						{
							$status = '<span class="badge bg-dark">Mất sách</span>';
						}

						
				?>
				
					<tr>
						<td><?=$book->find(htmlspecialchars($issue_book->book_id))->book_isbn_number?></td>
						<td><?=$user->find(htmlspecialchars($issue_book->user_id))->user_unique_id?></td>
						<td><?=htmlspecialchars($issue_book->issue_date_time)?></td>
						<td><?=htmlspecialchars($issue_book->expected_return_date)?></td>
						<td><?=htmlspecialchars($issue_book->return_date_time)?></td>
						<td><?=htmlspecialchars($book_fines)." VNĐ"?></td>
						<td><?=$status?></td>
						<td>
							<a href="issue_book_view.php?issue_book_id=<?=htmlspecialchars($issue_book->getId())?>" class="btn btn-info btn-sm"><i class="fas fa-eye me-1"></i></a>
						</td>
					</tr>

				<?php endforeach ?>	

        		</tbody>
        	</table>
        </div>
    </div>
</div>



<?php include 'partials_admin/footer.php'; ?>