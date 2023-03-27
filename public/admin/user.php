<?php

	include "../../bootstrap.php";
	include '../function.php';

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	if(!is_admin_login())
	{
		header('location:admin_login.php');
	}

	use CT275\Nienluannganh\User;
	$user = new User($PDO);

	//On/off
	if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['user_id'],$_GET['action']) && $_GET["action"] == 'change' && ($user->find($_GET['user_id'])) !== null){
		$user->update_status_user();
	}

	if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['user_id'],$_GET['action']) && $_GET["action"] == 'mail' && ($user->find($_GET['user_id'])) !== null){
		//XÁC THỰC GMAIL
		require '../../vendor/autoload.php';

		$mail = new PHPMailer(true);
		//Send message 
		$mail->isSMTP();

		$mail->Host = 'ssl://smtp.gmail.com';  

		$mail->SMTPAuth = true;

		$mail->Username = 'ducb1910213@student.ctu.edu.vn'; 

		$mail->Password = 'TZeUL5u!nG'; 

		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

		// 465 hoặc 587
		$mail->Port = 465;

		//Thông tin người gửi
		$mail->setFrom('library@gmail.com', 'library');

		//Người nhận
		$mail->addAddress($user -> user_email_address, $user -> user_name);

		$mail->isHTML(true);

		$mail->Subject = 'Hoan tra sach';

		$mail->Body = '
			<p>Bạn đã vượt quá số ngày mượn, vui lòng trả lại sách và đóng phạt sớm nhất có thể</p>
		';

		$mail->send();
	}



?>


<?php include 'partials_admin/header.php'; ?>

<div class="container-fluid py-4" style="min-height: 700px;">
	<h1>Quản lí người dùng</h1>
	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Quản lí người dùng</li>
    </ol>
    <?php 
 	
 	if(isset($_GET["msg"]))
 	{
 		if($_GET["msg"] == 'disable')
 		{
 			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Trạng thái người dùng đã chuyển sang chế độ vô hiệu hóa<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
 		}

 		if($_GET["msg"] == 'enable')
 		{
 			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Trạng thái người dùng đã chuyển sang chế độ kích hoạt<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
 		}
 	}

    ?>
    <div class="card mb-4">
    	<div class="card-header">
    		<div class="row">
    			<div class="col col-md-6">
    				<i class="fas fa-table me-1"></i>Quản lí người dùng
    			</div>
    			<div class="col col-md-6" align="right">
    			</div>
    		</div>
    	</div>
    	<div class="card-body">
    		<table id="datatablesSimple" class="table-striped">
    			<thead>
    				<tr>
    					<th>Hình ảnh</th>
                        <th>UID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Địa chỉ</th>
                        <th>Xác thực email</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
    				</tr>
    			</thead>

    			<tbody>
    			<?php 
					$users = $user->all();
					foreach($users as $user):

						$user_status = "";
						if($user->user_status == 'Enable')
						{
							$user_status = '<div class="badge bg-success">Kích hoạt</div>';
						}
						else
						{
							$user_status = '<div class="badge bg-danger">Vô hiệu hóa</div>';
						}
				?>
    					<tr>
    						<td><img src="uploads/<?=htmlspecialchars($user->user_profile)?>" class="img-thumbnail" width="75" /></td>
    						<td><?=htmlspecialchars($user->user_unique_id)?></td>
    						<td><?=htmlspecialchars($user->user_name)?></td>
    						<td><?=htmlspecialchars($user->user_email_address)?></td>
    						<td><?=htmlspecialchars($user->user_contact_no)?></td>
    						<td><?=htmlspecialchars($user->user_address)?></td>
    						<td><?=htmlspecialchars($user->user_verification_status)?></td>
    						<td><?=$user_status?></td>
    						<td>
								<a href="user.php?action=change&user_id=<?=htmlspecialchars($user->getId())?>" onclick = "return confirm('Bạn chắc chứ?')"  class="btn btn-sm btn-warning">
									<i class="fas fa-power-off mx-1"></i>
								</a>

								<a href="user.php?action=mail&user_id=<?=htmlspecialchars($user->getId())?>" onclick = "return confirm('Bạn chắc chứ?')"  class="btn btn-sm btn-primary">
									<i class="fas fa-envelope mx-1"></i>
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