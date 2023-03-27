<?php
	include "../../bootstrap.php";
	include '../function.php';

    use CT275\Nienluannganh\Admin;

    $admin = new Admin($PDO);
    $errors = [];
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_button'])) {
        $admin->fill($_POST);
        if($admin->validate_login()) {
            $admin->login_admin();
        }
        $errors = $admin->getValidationErrors();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="../assets/img/apple-icon.png">
    <link href="../assets/img/favicon.png">
    <title>LOGIN</title>

    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="../asset/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../asset/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="../asset/css/material-kit.css?v=3.0.4" rel="stylesheet" />

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
                                <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">ĐĂNG NHẬP</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form role="form" method="POST" class="text-start" enctype="multipart/form-data">


                                <div class="input-group input-group-outline my-3">
                                    <input type="email" class="form-control" name="admin_email"
                                        id="admin_email" placeholder="Email" required>
                                </div>

                                <div class="input-group input-group-outline mb-3">
                                    <input type="password" class="form-control" name="admin_password" id="admin_password"
                                        placeholder="Mật khẩu" required>
                                </div>

                                <!-- Cảnh báo không trùng mật khẩu -->
                                <?php if (isset($errors['login_check'])) : ?>
                                <span class="text-danger fst-italic fw-semibold mb-3">
                                    <strong><?= htmlspecialchars($errors['login_check']) ?></strong>
                                </span>
                                <?php endif ?>


                                <div class="form-check form-switch d-flex align-items-center mb-3">
                                    <input class="form-check-input" type="checkbox" id="rememberMe" checked>
                                    <label class="form-check-label mb-0 ms-3" for="rememberMe">Nhớ tôi</label>
                                </div>

                                <div class="text-center">
                                    <button type="submit" name="login_button"
                                        class="btn bg-gradient-primary w-100 my-4 mb-2">Đăng nhập</button>
                                </div>

                                <p class="text-sm text-center">
                                    <a href="../index.php">Trở lại trang chủ</a>
                                </p>

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