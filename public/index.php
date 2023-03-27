
<?php
    include "../bootstrap.php";
    include 'function.php';
	
?>

<!doctype html>
<html lang="en" >
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Hệ thống quản lý thư viện</title>
        
        <link href="asset/css/simple-datatables-style.css" rel="stylesheet" />
        <link href="asset/css/styles.css" rel="stylesheet" />
        <script src="asset/js/font-awesome-5-all.min.js" crossorigin="anonymous"></script>

        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }
            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }

			.divider:after,
			.divider:before {
				content: "";
				flex: 1;
				height: 1px;
				background: #eee;
			}
			.h-custom {
				height: calc(100% - 73px);
			}

			@media (max-width: 450px) {
				.h-custom {
					height: 100%;
				}
			}
		</style>
    </head>

    <body>
    	<main>
    		<div class="container py-4"  >
    			<header class="pb-3 mb-4 border-bottom">
                    <div class="row">
        				<div class="col-md-6">
                            <a href="index.php" class="d-flex align-items-center text-dark text-decoration-none">                               
                            </a>                           
                        </div>

                        <marquee behavior="" direction="right">
                            <span class="fs-4 fw-bold" style="color: brown">Hệ thống quản lí thư viện xin kính chào !!!</span>
                        </marquee>

                        <div class="col-md-6"></div>
                    </div>
    			</header>

                
                <!-- <div class="p-5 bg-light rounded-3 mt-4" style="background-image: url('https://cdn.123job.vn/123job/uploads/2021/05/27/2021_05_27______d326ed021ef9c5f7dbe5ad589bf9d036.jpeg'); background-size: 1300px 400px;background-repeat: no-repeat; ">
					<div class="container-fluid py-5 text-center" style="">
						<h1 class="display-5 fw-bold">Hệ thống quản lí thư viện</h1>
						<p class="fs-4" >Đây là trang web thư viện tạo ra nhằm phục vụ cho mọi người. </p>
					</div>
				</div> -->

                <div class="row mb-4">
                    <img src="https://thuvienquocgia.vn/wp-content/uploads/2018/10/5-dieu-ban-doc-can-biet-ve-thu-vien-quoc-gia-viet-nam-1-3.jpg" alt="" style="height:400px">
                </div>

				<div class="row align-items-md-stretch">

					<div class="col-md-6">
						<div class="h-100 p-5 text-white bg-dark rounded-3">
							<h2>Admin login</h2>
							<p></p>
							<a href="admin/admin_login.php" class="btn btn-outline-light">Đăng nhập</a>
						</div>
					</div>

					<div class="col-md-6">
						<div class="h-100 p-5 bg-light border rounded-3">
							<h2>User login</h2>
							<p></p>
							<a href="user_login.php" class="btn btn-outline-secondary">Đăng nhập</a>
							<a href="user_registration.php" class="btn btn-outline-primary">Đăng ký</a>
						</div>
					</div>
                    
				</div>
                
                <footer class="pt-3 mt-4 text-muted text-center border-top">
                    &copy; Quản lí thư viện <?php echo date('Y'); ?> 
                </footer>
            </div>
        </main>

    	<script src="asset/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="asset/js/scripts.js"></script>
        <script src="asset/js/simple-datatables@latest.js" crossorigin="anonymous"></script>
        <script src="asset/js/datatables-simple-demo.js"></script>

    </body>
</html>