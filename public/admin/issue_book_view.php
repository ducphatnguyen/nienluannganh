<?php
	include "../../bootstrap.php";
    include '../function.php';

    if(!is_admin_login())
	{
		header('location:admin_login.php');
	}

    use CT275\Nienluannganh\Issue_book;
	use CT275\Nienluannganh\Book;
    use CT275\Nienluannganh\Author;
	use CT275\Nienluannganh\User;

	$issue_book = new Issue_book($PDO);
	$book = new Book($PDO);
    $author = new Author($PDO);
	$user = new User($PDO);

    $error = "";
    if(isset($_POST["book_return_button"]))
    {
        if(isset($_POST["book_return_confirmation"]))
        {
            
            $issue_book_id = $_POST['issue_book_id'];
            $issue_book->find($issue_book_id);
            $issue_book->update_status_return_issue_book();

            $book_id = $_POST['book_id'];
            $book->find($book_id); 
            $book->update_plus_quantity_book();

		    header("location:issue_book.php?msg=return");
        }

        else if(isset($_POST["book_damaged_return_confirmation"]))
        {
            
            $issue_book_id = $_POST['issue_book_id'];
            $issue_book->find($issue_book_id);
            $issue_book->update_status_damaged_return_issue_book();

            $book_id = $_POST['book_id'];
            $book->find($book_id); 
            $book->update_plus_quantity_book();

		    header("location:issue_book.php?msg=damaged_return");
        }

        else if(isset($_POST["book_lost_confirmation"]))
        {
            
            $issue_book_id = $_POST['issue_book_id'];
            $issue_book->find($issue_book_id);
            $issue_book->update_status_lost_return_issue_book();

		    header("location:issue_book.php?msg=lost");
        }
        
        else
        {
            $error = 'Vui lòng xác nhận việc xử lý sách';
        }
    } 
    
?>

<?php include 'partials_admin/header.php'; ?>

<div class="container-fluid py-4" style="min-height: 700px;">
	<h1>Chi Tiết Sách Mượn</h1>
    
    <?php 
        $issue_book_id = isset($_REQUEST['issue_book_id']) ?
        filter_var($_REQUEST['issue_book_id'], FILTER_SANITIZE_NUMBER_INT) : -1;

        if ($issue_book_id < 0 || !($issue_book->find($issue_book_id))) {
            header("Location: issue_book.php"); 
        }
        else{
            $issue_book->find($issue_book_id);
        }
    ?>        
                    
    <ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="issue_book.php">Quản lí mượn trả</a></li>
        <li class="breadcrumb-item active">Xem chi tiết sách mượn</li>
    </ol>

    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-success" id="print-btn"><i class="fas fa-print"></i> In phiếu</button>
    </div>
    <script>
        var printBtn = document.getElementById("print-btn");
        printBtn.addEventListener("click", function() {
        window.print();
        });
    </script>
    <style>
        @page { size: auto;  margin: 0mm; }

        @media print {
            h1 {
                text-align: center;
            }
            .breadcrumb, .btn, footer, .navbar-brand {
                display: none;
            }
            body { margin: 0mm; }

        }
    </style>

    <?php
        if($error != "")
        {
            echo '<div class="alert alert-danger">'.$error.'</div>';
        }
    ?>

    <?php
        $book->find($issue_book->book_id);
    ?>
    <h2>Chi tiết sách</h2>
    <table class="table table-bordered">
        <tr>
            <th width="30%">Mã sách</th>
            <td width="70%"><?=htmlspecialchars($book->book_isbn_number)?></td>
        </tr>
        <tr>
            <th width="30%">Tiêu đề sách</th>
            <td width="70%"><?=htmlspecialchars($book->book_name)?></td>
        </tr>
        <tr>
            <th width="30%">Tác giả</th>
            <td width="70%"><?=$author->find(htmlspecialchars($book->author_id))->author_name?></td>
        </tr>
    </table>
    <br />

    <?php                    
        $user->find($issue_book->user_id);
    ?>
    <h2>Chi tiết người dùng</h2>
    <table class="table table-bordered">
        <tr>
            <th width="30%">Mã người dùng</th>
            <td width="70%"><?=htmlspecialchars($user->user_unique_id)?></td>
        </tr>
        <tr>
            <th width="30%">Họ tên</th>
            <td width="70%"><?=htmlspecialchars($user->user_name)?></td>
        </tr>
        <tr>
            <th width="30%">Địa chỉ</th>
            <td width="70%"><?=htmlspecialchars($user->user_address)?></td>
        </tr>
        <tr>
            <th width="30%">Số điện thoại</th>
            <td width="70%"><?=htmlspecialchars($user->user_contact_no)?></td>
        </tr>
        <tr>
            <th width="30%">Email</th>
            <td width="70%"><?=htmlspecialchars($user->user_email_address)?></td>
        </tr>
        <tr>
            <th width="30%">Hình ảnh</th>
            <td width="70%"><img src="uploads/<?=htmlspecialchars($user->user_profile)?>" class="img-thumbnail" width="100" /></td>
        </tr>
    </table>
    <br />
    <?php

    $status = $issue_book->book_issue_status;

    $form_item = "";

    //Mượn trả
    if($status == "Issue")
    {
        $status = '<span class="badge bg-warning">Mượn</span>';

        $form_item = '
        <label><input type="checkbox" name="book_return_confirmation" value="Yes" /> Xác nhận hoàn trả sách</label>
        <br />
        <label><input type="checkbox" name="book_damaged_return_confirmation" value="Yes" /> Xác nhận hoàn trả sách hư hỏng</label>
        <br />
        <label><input type="checkbox" name="book_lost_confirmation" value="Yes" /> Xác nhận mất sách</label>
        <br />

        <div class="mt-4 mb-4">
            <input type="submit" name="book_return_button" value="Trả sách" class="btn btn-primary" />
        </div>

        ';

    }

    //Trả trễ 
    if($status == 'Not Return')
    {
        $status = '<span class="badge bg-danger">Trễ hạn</span>';

        $form_item = '
        <label><input type="checkbox" name="book_return_confirmation" value="Yes" />Xác nhận hoàn trả sách>
        <br/>
        <label><input type="checkbox" name="book_damaged_return_confirmation" value="Yes" /> Xác nhận hư hỏng sách</label>
        <br />
        <label><input type="checkbox" name="book_lost_confirmation" value="Yes" /> Xác nhận mất sách</label>
        <br />

        <div class="mt-4 mb-4">
            <input type="submit" name="book_return_button" value="Trả sách" class="btn btn-primary" />
        </div>
        ';
    }

    // Hoàn trả
    if($status == 'Return')
    {
        $status = '<span class="badge bg-primary">Hoàn trả</span>';
    }

    if($status == 'Damaged Return')
    {
        $status = '<span class="badge bg-secondary">Trả hỏng</span>';
    }

    if($status == 'Lost')
    {
        $status = '<span class="badge bg-dark">Mất sách</span>';
    }



    ?>

    <h2>Chi tiết mượn trả</h2>
    <table class="table table-bordered">
        <tr>
            <th width="30%">Ngày mượn sách</th>
            <td width="70%"><?=htmlspecialchars($issue_book->issue_date_time)?></td>
        </tr>
        <tr>
            <th width="30%">Ngày hoàn trả sách</th>
            <td width="70%"><?=htmlspecialchars($issue_book->return_date_time)?></td>
        </tr>
        <tr>
            <th width="30%">Trạng thái mượn sách</th>
            <td width="70%"><?= $status ?></td>
        </tr>
        <tr>
            <th width="30%">Tiền phạt</th>
            <td width="70%"><?=htmlspecialchars($issue_book->book_fines). " VNĐ"?></td>
        </tr>
    </table>

    <form method="POST">

        <input type="hidden" name="issue_book_id" value="<?=htmlspecialchars($issue_book->getId())?>" />
        <input type="hidden" name="book_id" value="<?=htmlspecialchars($book->getId())?>" />
        
        <?php echo $form_item ?>
    </form>

    <br />

</div>

<?php include 'partials_admin/footer.php'; ?>