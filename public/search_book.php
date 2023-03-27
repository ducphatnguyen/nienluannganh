<?php
	include "../bootstrap.php";
	include 'function.php';

	if(!is_user_login())
	{
		header('location:user_login.php');
	}

	use CT275\Nienluannganh\Book;
	use CT275\Nienluannganh\Category;
	use CT275\Nienluannganh\Author;
	use CT275\Nienluannganh\Publisher;
	use CT275\Nienluannganh\Location_rack;
    use CT275\Nienluannganh\Issue_book;

	$book = new Book($PDO);
	$category = new Category($PDO);
	$author = new Author($PDO);
	$publisher = new Publisher($PDO);
	$location_rack = new Location_rack($PDO);
    $issue_book = new Issue_book($PDO);

?>

<?php include '../partials/header.php'; ?>
<style>
.fa-bell {
  animation: ring-bell 1s infinite;
}

@keyframes ring-bell {
  0% {
    transform: rotateZ(0deg);
  }
  25% {
    transform: rotateZ(10deg);
  }
  75% {
    transform: rotateZ(-10deg);
  }
  100% {
    transform: rotateZ(0deg);
  }
}

</style>
<main>
    <div class="py-4w mt-3">

        <div class="row">
            <div class="col-8">
                <marquee behavior="" direction="left">
                    <span class="fs-4 fw-bold" style="color: brown">Hệ thống quản lí thư viện xin kính chào !!!</span>
                </marquee>
                <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner carousel-fade" style="height:500px" data-bs-interval="5000">
                        <div class="carousel-item active">
                            <img src="https://noithatmienbac.vn/images/image/kienthuc/thu-vien-hien-dai-hoc-ktqd.jpg"
                                class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="https://image.phunuonline.com.vn/fckeditor/upload/2020/20200122/images/diem-danh-nhung-truong-dai-_1579715036.jpg"
                                class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="https://lrc.ctu.edu.vn/images/lrcweb/images/2022/hoa_than_2.jpg"
                                class="d-block w-100" alt="...">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

            </div>

            <div class="col-4">

                <ul class="list-group">
                    <li class="list-group-item active" aria-current="true">TIN TỨC MỚI</li>
                    <li class="list-group-item"><i class="fa fa-bell"></i> Thông báo nợ tài liệu của thư viện quá hạn
                        (Tính đến tháng 2/2023)</li>
                    <li class="list-group-item"><i class="fa fa-bell"></i> Danh sách bạn đọc nợ tài liệu quá hạn tính
                        đến ngày 03/10/2022</li>
                    <li class="list-group-item"><i class="fa fa-bell"></i> Sách hay tìm đọc tháng 10-2022</li>
                    <li class="list-group-item"><i class="fa fa-bell"></i> Sách hay tìm đọc tháng 9-2022</li>
                </ul>


                <ul class="list-group mt-5">
                    <li class="list-group-item active" aria-current="true">GIỜ MỞ CỬA</li>
                    <li class="list-group-item">Từ Thứ Hai đến Thứ Bảy</li>
                    <li class="list-group-item">Phục vụ máy tính: 7:00 – 21:00</li>
                    <li class="list-group-item">Phục vụ tài liệu mượn: 7:30 – 20:45</li>

                </ul>

            </div>

        </div>


        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col col-md-6">
                        <i class="fas fa-table me-1"></i>Sách
                    </div>
                    <div class="col col-md-6" align="right"></div>
                </div>
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table-striped">
                    <thead>
                        <tr>
                            <th>Hình ảnh</th>
                            <th>Tên sách</th>
                            <th>Mã sách</th>
                            <th>Thể loại</th>
                            <th>Tác giả</th>
                            <th>NXB</th>
                            <th>Vị trí</th>
                            <th>Số bản in</th>
                            <th>Trạng thái</th>
                            <th>Ngày thêm</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php 
                            $books = $book->all_enable();
                            foreach($books as $book):

                                $book_status = "";
                                if($book->book_no_of_copy > 0)
                                {
                                    $book_status = '<div class="badge bg-success">Có sẵn</div>';
                                }
                                else
                                {
                                    $book_status = '<div class="badge bg-danger">Không có sẵn</div>';
                                }
                        ?>
                        <tr>
                            <td><img src="admin/uploads/<?=htmlspecialchars($book->book_image)?>" width="60" /></td>
                            <td><?=htmlspecialchars($book->book_name)?></td>
                            <td><?=htmlspecialchars($book->book_isbn_number)?></td>
                            <td><?=$category->find($book->category_id)->category_name?></td>
                            <td><?=$author->find($book->author_id)->author_name?></td>
                            <td><?=$publisher->find($book->publisher_id)->publisher_name?></td>
                            <td><?=$location_rack->find($book->location_rack_id)->location_rack_name?></td>
                            <td><?=htmlspecialchars($book->book_no_of_copy)?></td>
                            <td><?=$book_status?></td>
                            <td><?=htmlspecialchars($book->book_created_on)?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Three columns of text below the carousel -->
    <h1 class="fst-italic fw-normal text-center ">3 cuốn sách được mượn nhiều nhất</h1>
    <br>

    <div class="row text-center">
        <?php 
               $issue_books = $issue_book->top3_favorite_book_issue();
               foreach($issue_books as $issue_book):
                    
                
            ?>
        <div class="col-lg-4">

            <img src="admin/uploads/<?=$book->find(htmlspecialchars($issue_book->book_id))->book_image?>" width=100 />
            <h2 class="fw-normal">
                <td><?=$book->find(htmlspecialchars($issue_book->book_id))->book_name?></td>
            </h2>
            <p><a class="btn btn-secondary" href="#">View details &raquo;</a></p>
        </div>
        <?php endforeach  ?>
    </div>

    <!-- Three columns of text below the carousel -->
    <h1 class="fst-italic fw-normal text-center ">VIDEO GIỚI THIỆU</h1>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
        <div class="col">
            <div class="card shadow-sm">
                <video width="100%" height="100%" controls>
                    <source src="movie.mp4" type="video/mp4">
                    <source src="movie.ogg" type="video/ogg">
                    Your browser does not support the video tag.
                </video>

                <div class="card-body">
                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to
                        additional content. This content is a little bit longer.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                        </div>
                        <small class="text-muted">9 mins</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm">
                <video width="100%" height="100%" controls>
                    <source src="movie.mp4" type="video/mp4">
                    <source src="movie.ogg" type="video/ogg">
                    Your browser does not support the video tag.
                </video>

                <div class="card-body">
                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to
                        additional content. This content is a little bit longer.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                        </div>
                        <small class="text-muted">9 mins</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm">
                <video width="100%" height="100%" controls>
                    <source src="movie.mp4" type="video/mp4">
                    <source src="movie.ogg" type="video/ogg">
                    Your browser does not support the video tag.
                </video>

                <div class="card-body">
                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to
                        additional content. This content is a little bit longer.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                        </div>
                        <small class="text-muted">9 mins</small>
                    </div>
                </div>
            </div>
        </div>

    </div>




    <br>

</main>


<?php include '../partials/footer.php'; ?>

<script type="text/javascript">
$(document).ready(function() {
    $(document).click(function() {
        $(".alert").remove();
    });
    $(".alert").first().hide().fadeIn(500).delay(3000).fadeOut(500, function() {
        $(this).remove();
    });
});
</script>