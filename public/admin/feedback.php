<?php
	include "../../bootstrap.php";
	include '../function.php';

	if(!is_admin_login())
	{
		header('location:admin_login.php');
	}

	use CT275\Nienluannganh\User;
	use CT275\Nienluannganh\Feedback;

	$user = new User($PDO);
	$feedback = new Feedback($PDO);

	//Delete
	if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['feedback_id'],$_GET['action']) && $_GET["action"] == 'delete' && ($feedback->find($_GET['feedback_id'])) !== null){
		$feedback->delete_feedback();
		if($feedback->delete_feedback()) {
			header("Location: feedback.php?msg=delete");
		}
	}
?>


<?php include 'partials_admin/header.php'; ?>

<div class="container-fluid py-4" style="min-height: 700px;">
	<h1>Quản lí góp ý</h1>
	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Quản lí góp ý</li>
    </ol>
	<?php 
	if(isset($_GET["msg"]))
	{
		//Delete
		if($_GET['msg'] == 'delete')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Góp ý đã được xóa<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
	}
	?>

    <div class="card mb-4">
    	<div class="card-header">
    		<div class="row">
    			<div class="col col-md-6">
    				<i class="fas fa-table me-1"></i>Quản lí góp ý
    			</div>
    			<div class="col col-md-6" align="right">
    			</div>
    		</div>
    	</div>
    	<div class="card-body">
    		<table id="datatablesSimple" class="table-striped">
    			<thead>
    				<tr>
                        <th>UID</th>
                        <th>Chủ đề</th>
                        <th>Nội dung</th>
                        <th>Thao tác</th>
    				</tr>
    			</thead>

    			<tbody>
    			<?php 
					$feedbacks = $feedback->all();
					foreach($feedbacks as $feedback):
				?>
    					<tr>
    						<td><?=htmlspecialchars($user->find($feedback->user_id)->user_unique_id)?></td>
    						<td><?=htmlspecialchars($feedback->feedback_title)?></td>
    						<td><?=htmlspecialchars($feedback->feedback_content)?></td>
    						<td>
								<a href="feedback.php?action=delete&feedback_id=<?=htmlspecialchars($feedback->getId())?>" onclick = "return confirm('Bạn chắc chứ?')"  class="btn btn-sm btn-danger">
									<i class="fas fa-trash mx-1"></i>
								</a>
							</td>
    					</tr>
						
				<?php endforeach ?>	

    			</tbody>
    		</table>
    	</div>
    </div>
</div>


<?php include 'partials_admin/footer.php'; ?>