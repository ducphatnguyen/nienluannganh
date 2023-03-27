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
	use CT275\Nienluannganh\Category;
	use CT275\Nienluannganh\Location_rack;
	use CT275\Nienluannganh\Publisher;
	use CT275\Nienluannganh\User;

	$issue_book = new Issue_book($PDO);
	$book = new Book($PDO);
	$author = new Author($PDO);
	$category = new Category($PDO);
	$location_rack = new Location_rack($PDO);
	$publisher = new Publisher($PDO);
	$user = new User($PDO);
?>

<?php include 'partials_admin/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<canvas id="myChart"></canvas>
<script>
	var data = {
        labels: ["Tổng số sách mượn", "Tổng số sách hoàn trả", "Tổng số sách trả hỏng", "Tổng số sách bị mất", "Tổng số sách quá hạn"],
        datasets: [{
          label: "Số lượng",
          data: [
			<?php echo $issue_book->count_total_issue_book_number(); ?>,
			<?php echo $a = $issue_book->count_total_returned_book_number(); ?>,
			<?php echo $b = $issue_book->count_total_damaged_returned_book_number(); ?>,
			<?php echo $c = $issue_book->count_total_lost_book_number(); ?>,
			<?php echo $d = $issue_book->count_total_not_returned_book_number(); ?> ],
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
			      'rgba(15, 150, 150, 0.2)',
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
			'rgba(15, 150, 150, 0.2)',
          ],
          borderWidth: 1
        }]
      };

      var options = {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }]
        }
      };

      var ctx = document.getElementById("myChart").getContext('2d');
      var myChart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: options
      });

</script>

<div class="d-flex justify-content-center">
    <h1 class="fs-3 fst-italic text-success">Tổng số tiền bồi thường nhận được: <?php echo $issue_book->count_total_fines_received(); ?></h1>
</div>




<?php include 'partials_admin/footer.php'; ?>