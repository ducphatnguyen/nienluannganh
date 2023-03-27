<?php
	include "../bootstrap.php";
	include 'function.php';

	if(!is_user_login())
	{
		header('location:user_login.php');
	}
	
	use CT275\Nienluannganh\Issue_book;
	use CT275\Nienluannganh\Book;

	$issue_book = new Issue_book($PDO);
	$book = new Book($PDO);

	//On/off
	if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['issue_book_id'],$_GET['action']) && $_GET["action"] == 'giahan' && ($issue_book->find($_GET['issue_book_id'])) !== null){
		$giahan = $issue_book->update_expected_return_date();
		
		if($giahan) {
			// echo '<script>$("#giahan").addClass("disabled");</script>';
			header("Location: issue_book_details.php");
		}
	}

   
?>

<?php include '../partials/header.php'; ?>

<main>
<div class="py-4" >

	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i>Mượn trả
				</div>
				<div class="col col-md-6" align="right">
				</div>
			</div>
		</div>
		<div class="card-body">
			<table id="datatablesSimple" class="table-striped">
				<thead>
					<tr>
						<th>Mã sách</th>
						<th>Tên sách</th>
						<th>Ngày mượn</th>
						<th>Hạn trả</th>
						<th>Ngày trả</th>
						<th>Tiền phạt</th>
						<th>Trạng thái</th>
						<th>Gia hạn</th>
					</tr>
				</thead>
				<tbody>
				
					<?php
						$user_id = $_SESSION['user_id'];
						$issue_books = $issue_book->find_personal_issue_book($user_id);
                            foreach($issue_books as $issue_book):

							$status = $issue_book->book_issue_status;
							if($status == 'Issue')
							{
								$status = '<span class="badge bg-warning">Mượn</span>';
							}

							if($status == 'Not Return')
							{
								$status = '<span class="badge bg-danger">Chưa trả</span>';
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
						<td>
							<?=htmlspecialchars($book->find($issue_book->book_id)->book_isbn_number)?>
						</td>
						<td>
							<?=htmlspecialchars($book->find($issue_book->book_id)->book_name)?>
						</td>
						<td><?=htmlspecialchars($issue_book->issue_date_time)?></td>
						<td><?=htmlspecialchars($issue_book->expected_return_date)?></td>
						<td>
							<?=htmlspecialchars($issue_book->return_date_time)?>
						</td>
						<td>
							<?=htmlspecialchars($issue_book->book_fines)." VNĐ"?> 
						</td>
						<td><?= $status ?></td>
						<td>

							<script>
								$(document).ready(function () {
									//Chỉ gia hạn khi đó là ngày cuối cùng của hạn trả và điều kiện là tình trạng đang mượn
									<?php 
										$current_date = date('Y-m-d');
										$expected_return_date = date('Y-m-d', strtotime($issue_book->expected_return_date));
										if ($issue_book->book_issue_status == 'Issue' && $current_date >= $expected_return_date && $issue_book->can_extend == 1) 
									{ ?>
										$("#giahan").removeClass("disabled");
									<?php } ?>
								});

							</script>

							<a href="issue_book_details.php?action=giahan&issue_book_id=<?=htmlspecialchars($issue_book->getId())?>" onclick = "return confirm('Sau khi click, bạn sẽ có thể gia hạn thêm 3 ngày, bạn chắc chứ?')" id="giahan" class="btn btn-primary disabled" role="button" aria-disabled="true">
								<i class="fas fa-calendar-plus me-1"></i>
							</a>
						</td>
						
					</tr>
					<?php endforeach ?>	
				</tbody>
			</table>
		</div>
	</div>

  <div class="alert alert-warning" role="alert">
    
    <div class="fst-italic ">
      Chú ý: Để mượn được sách thư viện bạn cần phải đem theo thẻ sinh viên hoặc căn cước công dân đến thư viện để làm thủ tục mượn trả. Sau khi mượn bạn cần tuân thủ các nguyên tắc của thư viện về việc mượn trả !!!
    </div>
  </div>

</div>

</main>

<?php include '../partials/footer.php'; ?>
