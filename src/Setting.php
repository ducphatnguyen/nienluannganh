<?php

namespace CT275\Nienluannganh;

class Setting
{
	private $db;
	private $setting_id;

	public $library_name;
	public $library_address;
	public $library_contact_number;
	public $library_email_address;
	public $library_total_book_issue_day;
	public $library_one_day_fine;
	public $library_damaged_return_book_rate;
	public $library_lost_book_rate;
	public $library_issue_total_book_per_user;

	private $errors = [];

	public function getId()
	{
		return $this->setting_id;
	}

	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

	// Nhập
	public function fill(array $data) {
		if (isset($data['library_name'])) {
			$this->library_name = trim($data['library_name']);
		}
		if (isset($data['library_address'])) {
			$this->library_address = trim($data['library_address']);
		}
		if (isset($data['library_contact_number'])) {
			$this->library_contact_number = $data['library_contact_number'];
		}
		if (isset($data['library_email_address'])) {
			$this->library_email_address = $data['library_email_address'];
		}
		if (isset($data['library_total_book_issue_day'])) {
			$this->library_total_book_issue_day = $data['library_total_book_issue_day'];
		}
		if (isset($data['library_one_day_fine'])) {
			$this->library_one_day_fine = $data['library_one_day_fine'];
		}
		if (isset($data['library_damaged_return_book_rate'])) {
			$this->library_damaged_return_book_rate = $data['library_damaged_return_book_rate'];
		}
		if (isset($data['library_lost_book_rate'])) {
			$this->library_lost_book_rate = $data['library_lost_book_rate'];
		}
		if (isset($data['library_issue_total_book_per_user'])) {
			$this->library_issue_total_book_per_user = $data['library_issue_total_book_per_user'];
		}
		return $this;
	}

	// Xác thực
	public function validate() {
		if (!$this->library_name) {
			$this->errors['library_name'] = 'Tên thư viện không được bỏ trống!';
		} 
		if (!$this->library_address) {
			$this->errors['library_address'] = 'Địa chỉ thư viện không được bỏ trống!';
		}
		if (!$this->library_contact_number) {
			$this->errors['library_contact_number'] = 'Số điện thoại thư viện không được bỏ trống!';
		}
		if (!$this->library_email_address) {
			$this->errors['library_email_address'] = 'Email thư viện không được bỏ trống!';
		}
		if (!$this->library_total_book_issue_day) {
			$this->errors['library_total_book_issue_day'] = 'Tổng sách mượn trong ngày không được bỏ trống!';
		}
		if (!$this->library_one_day_fine) {
			$this->errors['library_one_day_fine'] = 'Chi phí trả trễ trên ngày không được bỏ trống!';
		}
		if (!$this->library_damaged_return_book_rate) {
			$this->errors['library_damaged_return_book_rate'] = 'Chi phí trả trễ trên ngày không được bỏ trống!';
		}
		if (!$this->library_lost_book_rate) {
			$this->errors['library_lost_book_rate'] = 'Chi phí trả trễ trên ngày không được bỏ trống!';
		}
		if (!$this->library_issue_total_book_per_user) {
			$this->errors['library_issue_total_book_per_user'] = 'Tổng sách mượn tối đa không được bỏ trống!';
		}
		return empty($this->errors);
	}
	
	public function getValidationErrors() {
		return $this->errors;
	}

	// Đổ dữ liệu
	protected function fillFromDB(array $row)
	{
		[
		'setting_id' => $this->setting_id,
		'library_name' => $this->library_name,
		'library_address' => $this->library_address,
		'library_contact_number' => $this->library_contact_number,
		'library_email_address' => $this->library_email_address,
		'library_total_book_issue_day' => $this->library_total_book_issue_day,
		'library_one_day_fine' => $this->library_one_day_fine,
		'library_damaged_return_book_rate' => $this->library_damaged_return_book_rate,
		'library_lost_book_rate' => $this->library_lost_book_rate,
		'library_issue_total_book_per_user' => $this->library_issue_total_book_per_user

		] = $row;
		return $this;
	}

	public function save() {
		$result = false;
		$stmt = $this->db->prepare('UPDATE tbl_setting 
									SET library_name = :library_name,
										library_address = :library_address, 
										library_contact_number = :library_contact_number, 
										library_email_address = :library_email_address, 
										library_total_book_issue_day = :library_total_book_issue_day, 
										library_one_day_fine = :library_one_day_fine, 
										library_damaged_return_book_rate = :library_damaged_return_book_rate,
										library_lost_book_rate = :library_lost_book_rate,
										library_issue_total_book_per_user = :library_issue_total_book_per_user');
									
		$result = $stmt->execute([
									'library_name' 						=> $this->library_name,
									'library_address' 					=> $this->library_address,
									'library_contact_number' 			=> $this->library_contact_number,
									'library_email_address' 			=> $this->library_email_address,
									'library_total_book_issue_day' 		=> $this->library_total_book_issue_day,
									'library_one_day_fine' 				=> $this->library_one_day_fine,
									'library_damaged_return_book_rate' 	=> $this->library_damaged_return_book_rate,
									'library_lost_book_rate' 			=> $this->library_lost_book_rate,
									'library_issue_total_book_per_user' => $this->library_issue_total_book_per_user

								]);
		return $result;
	}
	
	public function update(array $data)
	{
		$this->fill($data);
		if ($this->validate()) {
			return $this->save();
		}
		return false;
	}
	
	public function all(){
		$settings = [];
		$stmt = $this->db->prepare("SELECT * FROM tbl_setting LIMIT 1");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$setting = new Setting($this->db);
			$setting->fillFromDB($row);
			$settings[] = $setting;
		}
		return $settings;
	}

	public function get_one_day_fines() { 
		$output = 0;
		$stmt = $this->db->prepare("SELECT library_one_day_fine FROM tbl_setting LIMIT 1");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$output = $row["library_one_day_fine"];
        }
		return $output;
    }

	public function get_damaged_return_book_fines() { 
		$output = 0;
		$stmt = $this->db->prepare("SELECT library_damaged_return_book_rate FROM tbl_setting LIMIT 1");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$output = $row["library_damaged_return_book_rate"];
        }
		return $output;
    }

	public function get_lost_book_fines() { 
		$output = 0;
		$stmt = $this->db->prepare("SELECT library_lost_book_rate FROM tbl_setting LIMIT 1");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$output = $row["library_lost_book_rate"];
        }
		return $output;
    }

	public function get_total_book_issue_day() {
		$output = 0;
		$stmt = $this->db->prepare("SELECT library_total_book_issue_day FROM tbl_setting LIMIT 1");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$output = $row["library_total_book_issue_day"];
        }
		return $output;
	}

	public function get_book_issue_limit_per_user()
	{
		$output = 0;
		$stmt = $this->db->prepare("SELECT library_issue_total_book_per_user FROM tbl_setting LIMIT 1");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$output = $row["library_issue_total_book_per_user"];
        }
		return $output;
	}



}
