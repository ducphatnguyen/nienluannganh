<?php

namespace CT275\Nienluannganh;

class Issue_book
{
	private $db;
	private $issue_book_id = -1;

	public $book_id;
	public $user_id;
	public $issue_date_time;
	public $expected_return_date;
	public $return_date_time;
	public $book_fines;
	public $can_extend;
	public $book_issue_status;
	
	private $errors = [];

	public function getId()
	{
		return $this->issue_book_id;
	}

	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

	public function fill(array $data) {
		if (isset($data['book_id'])) {
			$this->book_id = trim($data['book_id']);
		}
		if (isset($data['user_id'])) {
			$this->user_id = trim($data['user_id']);
		}
		if (isset($data['issue_date_time'])) {
			$this->issue_date_time = trim($data['issue_date_time']);
		}
		if (isset($data['expected_return_date'])) {
			$this->expected_return_date = trim($data['expected_return_date']);
		}
		if (isset($data['return_date_time'])) {
			$this->return_date_time = trim($data['return_date_time']);
		}
		if (isset($data['book_fines'])) {
			$this->book_fines = trim($data['book_fines']);
		}
		if (isset($data['can_extend'])) {
			$this->can_extend = trim($data['can_extend']);
		}
		if (isset($data['book_issue_status'])) {
			$this->book_issue_status = trim($data['book_issue_status']);
		}

		return $this;
	}

	protected function fillFromDB(array $row)
	{
		[
		'issue_book_id' => $this->issue_book_id,
		'book_id' => $this->book_id,
		'user_id' => $this->user_id,
		'issue_date_time' => $this->issue_date_time,
		'expected_return_date' => $this->expected_return_date,
		'return_date_time' => $this->return_date_time,
		'book_fines' => $this->book_fines,
		'can_extend' => $this->can_extend,
		'book_issue_status' => $this->book_issue_status
		] = $row;
		return $this;
	}

	public function get_total_book_issue_per_user()
	{
		$output = 0;
		$stmt = $this->db->prepare("SELECT COUNT(issue_book_id) AS Total FROM tbl_issue_book 
									WHERE user_id = :user_id 
									AND book_issue_status = 'Issue'");
		$stmt->execute(['user_id' => $this->user_id]);
		while ($row = $stmt->fetch()) {
			$output = $row["Total"];
        }
		return $output;
	}

	public function validate() {

		if (!$this->book_id) {
			$this->errors['book_id'] = 'Mã sách không được bỏ trống!';
		} 
		
		
		if (!$this->user_id) {
			$this->errors['user_id'] = 'Mã người dùng không được rỗng!';
		} 


		return empty($this->errors);
	}

	public function getValidationErrors() {
		return $this->errors;
	}

	public function save() {
        $result = false;

		// get_total_book_issue_day();
		$total_book_issue_day = 0;
		$stmt = $this->db->prepare("SELECT library_total_book_issue_day FROM tbl_setting LIMIT 1");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$total_book_issue_day = $row["library_total_book_issue_day"];
        }

		$today_date = date('Y-m-d H:i:s');

		$expected_return_date = date('Y-m-d H:i:s', strtotime($today_date. ' + '.$total_book_issue_day.' days'));
	
        $stmt = $this->db->prepare('INSERT INTO tbl_issue_book 
									(book_id, user_id, issue_date_time, expected_return_date, book_fines, can_extend, book_issue_status) 
									VALUES (:book_id, :user_id, :issue_date_time, :expected_return_date, :book_fines, :can_extend, :book_issue_status)');
        $result = $stmt->execute([
								'book_id'      =>  $this->book_id,
								'user_id'      =>  $this->user_id,
								'issue_date_time'  =>   $today_date,
								'expected_return_date' => $expected_return_date,
								'book_fines'       =>  0,
								'can_extend'       =>  1,
								'book_issue_status'    =>  'Issue'
                                    ]);

		header("Location: issue_book.php?msg=add");

		if ($result) {
			$this->book_id = $this->db->lastInsertId();
		}
        
        return $result;
    }

	public function update_status_return_issue_book() {
		 
		$stmt = $this->db->prepare("  	UPDATE tbl_issue_book 
										SET return_date_time = :return_date_time, 
											book_issue_status = :book_issue_status,
											can_extend = 0 
										WHERE issue_book_id = :issue_book_id ");

		return $stmt->execute([
						'return_date_time' => date('Y-m-d H:i:s'),
						'book_issue_status' => 'Return',
						'issue_book_id' => $this->issue_book_id
						]);
	}

	public function find($issue_book_id) {
        $stmt = $this->db->prepare('SELECT * FROM tbl_issue_book WHERE issue_book_id = :issue_book_id');
        $stmt->execute(['issue_book_id' => $issue_book_id]);
        if ($row = $stmt->fetch()) {
            $this->fillFromDB($row);
            return $this;
        }
        return null;
    }

	//Check
	public function update_status_damaged_return_issue_book() {
		
		// get_damaged_return_book_fines();
		$library_damaged_return_book_rate = 0;
		$stmt = $this->db->prepare("SELECT library_damaged_return_book_rate FROM tbl_setting LIMIT 1");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$library_damaged_return_book_rate = $row["library_damaged_return_book_rate"];
        }

		//
		$this->find($issue_book_id);

		$stmt = $this->db->prepare("SELECT book_price FROM tbl_book WHERE book_id = :book_id");
		$stmt->execute(['book_id' => $this->book_id]);
		while ($row = $stmt->fetch()) {
			$book_price = $row["book_price"];
        }

		$stmt1 = $this->db->prepare("  	UPDATE tbl_issue_book 
										SET return_date_time = :return_date_time, 
											book_fines = :book_fines,
											book_issue_status = :book_issue_status,
											can_extend = 0 
										WHERE issue_book_id = :issue_book_id ");

		return $stmt1->execute([
						'return_date_time' => date('Y-m-d H:i:s'),
						'book_fines' => $this->book_fines + $book_price*$library_damaged_return_book_rate,
						'book_issue_status' => 'Damaged Return',
						'issue_book_id' => $this->issue_book_id
						]);
	}

	//Check
	public function update_status_lost_return_issue_book() {

		// get_damaged_return_book_fines();
		$library_lost_book_rate = 0;
		$stmt = $this->db->prepare("SELECT library_lost_book_rate FROM tbl_setting LIMIT 1");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$library_lost_book_rate = $row["library_lost_book_rate"];
        }

		$this->find($issue_book_id);

		$stmt = $this->db->prepare("SELECT book_price FROM tbl_book WHERE book_id = :book_id");
		$stmt->execute(['book_id' => $this->book_id]);
		while ($row = $stmt->fetch()) {
			$book_price = $row["book_price"];
        }

		$stmt1 = $this->db->prepare("  	UPDATE tbl_issue_book 
										SET return_date_time = :return_date_time, 
											book_fines = :book_fines,
											book_issue_status = :book_issue_status,
											can_extend = 0 
										WHERE issue_book_id = :issue_book_id ");

		return $stmt1->execute([
						'return_date_time' => date('Y-m-d H:i:s'),
						'book_fines' => $this->book_fines + $book_price*$library_lost_book_rate,
						'book_issue_status' => 'Lost',
						'issue_book_id' => $this->issue_book_id
						]);
	}

	//Check
	public function update_status_not_return_issue_book($book_fines,$issue_book_id) {
	
		$stmt = $this->db->prepare("UPDATE tbl_issue_book 
									SET book_fines = :book_fines, 
										book_issue_status = 'Not Return',  
										can_extend = 0
									WHERE issue_book_id = :issue_book_id ");
		return $stmt->execute([
						'book_fines' =>	$book_fines,
						'issue_book_id' => $issue_book_id
		]);
		
	}

	public function update_expected_return_date() {
	
		$stmt = $this->db->prepare("UPDATE tbl_issue_book 
									SET  expected_return_date = DATE_ADD(expected_return_date, INTERVAL 3 DAY),
										 can_extend = 0  
									WHERE issue_book_id = :issue_book_id ");
		return $stmt->execute([
						'issue_book_id' => $this->issue_book_id
		]);
		
	}

	public function update(array $data)
    {
        $this->fill($data);
        if ($this->validate()) {
            return $this->save();
        }
        return false;
    }


	public function find_personal_issue_book($user_id) {

		$personal_issue_books = [];
        $stmt = $this->db->prepare('SELECT * FROM tbl_issue_book WHERE user_id = :user_id ORDER BY issue_book_id DESC');
        $stmt->execute(['user_id' => $user_id]);
        while ($row = $stmt->fetch()) {
            $personal_issue_book = new Issue_book($this->db);
            $personal_issue_book->fillFromDB($row);
            $personal_issue_books[] = $personal_issue_book;
        }
        return $personal_issue_books;

    }

    public function all(){
        $issue_books = [];
        $stmt = $this->db->prepare("SELECT * FROM tbl_issue_book ORDER BY issue_book_id ASC");
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $issue_book = new Issue_book($this->db);
            $issue_book->fillFromDB($row);
            $issue_books[] = $issue_book;
        }
        return $issue_books;
    }

	public function top3_favorite_book_issue(){
        $issue_books = [];
        $stmt = $this->db->prepare("SELECT *, COUNT(*) as count
									FROM tbl_issue_book
									GROUP BY book_id
									ORDER BY count DESC
									LIMIT 3");
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $issue_book = new Issue_book($this->db);
            $issue_book->fillFromDB($row);
            $issue_books[] = $issue_book;
        }
        return $issue_books;
    }

	public function count_total_issue_book_number() { 
		$total = 0;
		$stmt = $this->db->prepare("SELECT COUNT(issue_book_id) AS Total FROM tbl_issue_book");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$total = $row["Total"];
        }
		return $total;
    }

	public function count_total_returned_book_number() { 
		$total = 0;
		$stmt = $this->db->prepare("SELECT COUNT(issue_book_id) AS Total FROM tbl_issue_book 
									WHERE book_issue_status = 'Return'");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$total = $row["Total"];
        }
		return $total;
    }

	public function count_total_damaged_returned_book_number() { 
		$total = 0;
		$stmt = $this->db->prepare("SELECT COUNT(issue_book_id) AS Total FROM tbl_issue_book 
									WHERE book_issue_status = 'Damaged Return'");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$total = $row["Total"];
        }
		return $total;
    }

	public function count_total_lost_book_number() { 
		$total = 0;
		$stmt = $this->db->prepare("SELECT COUNT(issue_book_id) AS Total FROM tbl_issue_book 
									WHERE book_issue_status = 'Lost'");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$total = $row["Total"];
        }
		return $total;
    }

	public function count_total_not_returned_book_number() { 
		$total = 0;
		$stmt = $this->db->prepare("SELECT COUNT(issue_book_id) AS Total FROM tbl_issue_book 
									WHERE book_issue_status = 'Not Return'");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$total = $row["Total"];
        }
		return $total;
    }

	//Sau khi đã xác nhận xử lý hoàn trả + tiền trả trễ nếu có nằm trong book_fines
	public function count_total_fines_received() { 
		$total = 0;
		$stmt = $this->db->prepare("SELECT SUM(book_fines) AS Total FROM tbl_issue_book WHERE book_issue_status = 'Return' OR book_issue_status = 'Damaged Return' OR book_issue_status = 'Lost'");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$total = $row["Total"];
        }
		return $total;
    }



}
