
<?php

	include "../bootstrap.php";
	include 'function.php';

	include '../partials/header.php'; 


	use CT275\Nienluannganh\User;
	$user = new User($PDO);

	//Xác thực 
	if(isset($_GET['user_verification_code']))
	{
		$find = $user->find_user_verification_code($_GET['user_verification_code']);
		//Nếu tìm thấy
		if($find) {
			$update_status_verify = $user->verify_registration();
			if($update_status_verify) {
				echo '<div class="alert alert-success">Email của bạn đã được xác thực, bây giờ bạn có thể <a href="user_login.php">đăng nhập</a> vào hệ thống.</div>';
			}
			else {
				echo '<div class="alert alert-info">Email của bạn đã xác thực rồi</div>';
			}
		}
		else {
			echo '<div class="alert alert-danger">Lỗi URL</div>';
		}
	}

?>

	<?php 	include '../partials/footer.php';  ?>