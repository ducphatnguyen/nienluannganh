<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include "../bootstrap.php";
include 'function.php';

$success = "";
$errors = [];
use CT275\Nienluannganh\User;
$user = new User($PDO);
if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	if(isset($_POST['register_button'])) {

		$user->fill_regist($_POST,$_FILES);
		if($user->validate_regist()) {
			if($user->save()) {

				//XÁC THỰC GMAIL
				require '../vendor/autoload.php';

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

				$mail->Subject = 'Verification for library management system';

				$mail->Body = '
					<p>Cảm ơn bạn đã đăng ký tài khoản. UID của bạn là <b>'
						.$user->user_unique_id.
					'</b> được sử dụng để mượn sách.</p>
					<p>Đây là email cần được xác thực, vui lòng click vào link bên dưới để xác thực.</p>
					<p><a href="nienluannganh.localhost/verify.php?user_verification_code='
					.md5($user -> user_verification_code).
					'">Click để xác thực</a></p>
					
					<p>Xin cảm ơn...</p>
				';

				$mail->send();

				$success = 'Vui lòng xác thực email tại địa chỉ ' . $user -> user_email_address;
			}
		}
		$errors = $user->getValidationErrors();
		}	
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="../assets/img/apple-icon.png">
    <link href="../assets/img/favicon.png">
    <title>SIGN UP</title>

    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="../asset/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../asset/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="../asset/css/material-kit.css?v=3.0.4" rel="stylesheet" />

    <!-- Include jQuery Library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <!-- Include jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <style>
    .custom-file-input {
        position: relative;
        z-index: 2;
        width: 100%;
        height: calc(2.25rem + 2px);
        margin: 0;
        opacity: 0;
    }

    .custom-file-label {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        z-index: 1;
        height: calc(2.25rem + 2px);
        padding: 0.375rem 0.75rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        cursor: pointer;
    }
    </style>

</head>

<body class="sign-in-basic">
    <div class="page-header align-items-start min-vh-100"
        style="background-image: url('https://mientaycogi.com/wp-content/uploads/2019/10/%C4%91%E1%BA%A1i-h%E1%BB%8Dc-c%E1%BA%A7n-th%C6%A1-1280x720.jpg');" loading="lazy">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container my-auto">
            <div class="row">
                <div class="col-lg-4 col-md-8 col-12 mx-auto">
                    <div class="card z-index-0 fadeIn3 fadeInBottom">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                                <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">ĐĂNG KÝ</h4>
                            </div>
                        </div>

                        

                        <div class="card-body">
                            <form role="form" method="POST" class="text-start" enctype="multipart/form-data">

                                <!-- Cần chỉnh lại bắt lỗi -->
                                <?php if (isset($errors['user_email_address'])) : ?>
								<span class="text-danger fst-italic fw-semibold">
									<strong><?= htmlspecialchars($errors['user_email_address']) ?></strong>
								</span>
								<?php endif ?>

                                <div class="input-group input-group-outline input-group-dynamic mb-3">
                                    <input type="email" class="form-control" name="user_email_address"
                                        id="user_email_address" aria-describedby="emailHelp"
                                        placeholder="Dùng Email trường" required>
                                </div>

                                <div class="input-group input-group-outline input-group-dynamic mb-3">
                                    <input type="password" class="form-control" name="user_password" id="user_password"
                                        placeholder="Mật khẩu" required>
                                </div>

                                <div class="input-group input-group-outline input-group-dynamic mb-3">
                                    <input type="text" class="form-control" name="user_name" id="user_name"
                                        placeholder="User Name" required>
                                </div>

                                <div class="input-group input-group-outline input-group-dynamic mb-3">
                                    <input type="text" class="form-control" name="user_contact_no" id="user_contact_no"
                                        placeholder="SĐT" required>
                                </div>

                                <div class="input-group input-group-outline input-group-dynamic mb-3">
                                    <input type="text" class="form-control" name="user_address" id="user_address"
                                        placeholder="Địa chỉ" required>

                                </div>

                                <script>
                                $("#user_address").autocomplete({
                                    source: function(request, response) {
                                        $.ajax({
                                            url: "https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/findAddressCandidates",
                                            dataType: "jsonp",
                                            data: {
                                                singleLine: request.term,
                                                f: "json"
                                            },
                                            success: function(data) {
                                                response($.map(data.candidates, function(item) {
                                                    return {
                                                        label: item.address,
                                                        value: item.address
                                                    }
                                                }));
                                            }
                                        });
                                    },
                                    minLength: 3
                                });
                                </script>


                                <div class="form-group">
                                    <label for="user_profile">Ảnh đại diện</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="user_profile"
                                                id="user_profile">
                                            <label class="custom-file-label" for="user_profile">Chọn ảnh</label>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                // Cập nhật nội dung của label khi người dùng chọn file
                                $('.custom-file-input').on('change', function(event) {
                                    var inputFile = event.currentTarget;
                                    $(inputFile).parent().find('.custom-file-label').html(inputFile.files[0]
                                        .name);
                                });
                                </script>
                                
                                <!-- Input ẩn -->
                                <input type="hidden" name="user_unique_id" value="<?php echo rand(100000,999999)?>"class="btn btn-color px-5 mb-5 w-100"></input>
                                <input type="hidden" name="user_verification_code" value="<?php echo uniqid() ?>" class="btn btn-color px-5 mb-5 w-100"></input>


                                <div class="text-center">
                                    <button type="submit" name="register_button"
                                        class="btn bg-gradient-primary w-100 my-4 mb-2">Đăng ký</button>
                                </div>

                                <p class="mt-4 text-sm text-center">
                                    <a href="user_login.php">Đăng nhập</a>
                                </p>

								<p class="text-sm text-center">
                                    <a href="index.php">Trở lại trang chủ</a>
                                </p>

								<?php 
									if($success != "")
									{
										echo '<div class="text-success">'.$success.'</div>';
									}
								?>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/core/popper.min.js" type="text/javascript"></script>
    <script src="../assets/js/core/bootstrap.min.js" type="text/javascript"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/parallax.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTTfWur0PDbZWPr7Pmq8K3jiDp0_xUziI"></script>
    <script src="../assets/js/material-kit.min.js?v=3.0.4" type="text/javascript"></script>
</body>

</html>