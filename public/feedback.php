<?php
	include "../bootstrap.php";
	include 'function.php';

	if(!is_user_login())
	{
		header('location:user_login.php');
	}

    use CT275\Nienluannganh\Feedback;
	$feedback = new Feedback($PDO);
    $action = "";
	$errors = [];
	// Chỉnh sửa
	if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
		$action = $feedback->update($_POST);
		$errors = $feedback->getValidationErrors();
	}

?>

<?php include '../partials/header.php'; ?>

<main>
    <div>
        <div class="row">
            <div class="col-9 mt-3">
                <h3>Liên hệ với chúng tôi!</h3>
                <p>Chúng tôi mong muốn lắng nghe từ bạn. Hãy liên hệ với chúng tôi và một thành viên của chúng tôi sẽ liên lạc với bạn trong thời gian sớm nhất. Chúng tôi yêu thích việc nhận được email của bạn <em>mỗi ngày</em>.</p>
                <form method="POST">
                    <?php 
                        if($action != "") {
                            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Góp ý thành công<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                        }
                    ?>
                    <div class="row mb-4">
                        <div class="col-3">
                            <label class="form-label" for="feedback_title"><b>Chủ đề</b></label>
                        </div>
                        <div class="col-9">
                            <input class="form-control" type="text" name="feedback_title" placeholder="Chủ đề" required />
                        </div>                       
                    </div>


                    <div class="row mb-4">
                        <div class="col-3">
                            <b><label class="form-label">Nội dung</label></b>
                        </div>
                        <div class="col-9">
                            <textarea class="form-control" name="feedback_content" placeholder="Nội dung" rows="3" required></textarea>
                        </div>                       
                    </div>      
                    <div class="d-flex justify-content-end">
                        <input type="hidden" name="user_id" value="<?=$_SESSION['user_id']?>" />
                        <button class="btn btn-primary" type="submit" name="save">Góp ý</button>
                    </div>     
                </form>
            </div>
            <div class="col-3 mt-3">
                <div>
                    <h5>Bản đồ</h5>
                    <p>
                        <a href="#">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15729855.42909206!2d96.7382165931671!3d15.735434000981483!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31157a4d736a1e5f%3A0xb03bb0c9e2fe62be!2zVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1445179448264" width="200" height="200" frameborder="0" style="border:0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                            <br />
                        </a>
                        <br />
                        <a href="#" style="text-decoration:none;">Xem bản đồ</a>
                    </p>
                    <p>
                        Địa chỉ 1.
                        <br /> 
                        Địa chỉ 2.
                    </p>
                </div>
            </div>       
        </div>
    </div>
    
</main>

<?php include '../partials/footer.php'; ?>

<script type="text/javascript">
        $(document).ready(function () {
            $(document).click(function() {
                $(".alert").remove();
            });
            $(".alert").first().hide().fadeIn(500).delay(3000).fadeOut(500, function () {
                $(this).remove(); 
            });
        });
    </script>