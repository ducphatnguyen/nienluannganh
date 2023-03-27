<?php

namespace CT275\Nienluannganh;

class User
{
	private $db;
	private $user_id = -1;

	public $user_name;
	public $user_address;
	public $user_contact_no;
	public $user_profile;
	public $user_email_address;
	public $user_password;
	public $user_verification_code;
	public $user_verification_status;
	public $user_unique_id;
	public $user_status;
	public $user_created_on;
	public $user_updated_on;
	
	private $errors = [];

	public function getId()
	{
		return $this->user_id;
	}

	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

	public function fill_regist(array $data, $file) {
		if (isset($data['user_name'])) {
			$this->user_name = trim($data['user_name']);
		}
		if (isset($data['user_address'])) {
			$this->user_address = trim($data['user_address']);
		}
		if (isset($data['user_contact_no'])) {
			$this->user_contact_no = trim($data['user_contact_no']);
		}
		if (isset($file)) {
			$this->user_profile = $file["user_profile"]["name"];
		}
		if (isset($data['user_email_address'])) {
			$this->user_email_address = trim($data['user_email_address']);
		}
		if (isset($data['user_password'])) {
			$this->user_password = trim($data['user_password']);
		}
		if (isset($data['user_verification_code'])) {
			$this->user_verification_code = trim($data['user_verification_code']);
		}
		if (isset($data['user_verification_status'])) {
			$this->user_verification_status = trim($data['user_verification_status']);
		}
		//Cắt chuỗi từ email để lấy id cho user_unique_id
		if (isset($data['user_unique_id'])) {

			// $a = trim($data['user_email_address']);
			// $b = explode('@', $a);
			// $c = $b[0];
			// $d = substr($c,-8);
			// $this->user_unique_id = strtoupper($d);
			$this->user_unique_id = trim($data['user_unique_id']);
			
		}

		if (isset($data['user_status'])) {
			$this->user_status = trim($data['user_status']);
		}
		if (isset($data['user_created_on'])) {
			$this->user_created_on = trim($data['user_created_on']);
		}
		if (isset($data['user_updated_on'])) {
			$this->user_updated_on = trim($data['user_updated_on']);
		}
		return $this;
	}

	public function fill_login(array $data) {

		if (isset($data['user_email_address'])) {
			$this->user_email_address = trim($data['user_email_address']);
		}
		if (isset($data['user_password'])) {
			$this->user_password = trim($data['user_password']);
		}

		return $this;
	}
	
	public function fill_verification(array $data) {

		if (isset($data['user_verification_code'])) {
			$this->user_verification_code = trim($data['user_verification_code']);
		}

		return $this;
	}

	// Làm tiếp
	protected function fillFromDB(array $row)
	{
		[
		'user_id' => $this->user_id,
		'user_name' => $this->user_name,
		'user_address' => $this->user_address,
		'user_contact_no' => $this->user_contact_no,
		'user_profile' => $this->user_profile,
		'user_email_address' => $this->user_email_address,
		'user_password' => $this->user_password,
		'user_verification_code' => $this->user_verification_code,
		'user_verification_status' => $this->user_verification_status,
		'user_unique_id' => $this->user_unique_id,
		'user_status' => $this->user_status,
		'user_created_on' => $this->user_created_on,
		'user_updated_on' => $this->user_updated_on

		] = $row;
		return $this;
	}

	public function validate_regist() {
		if (!$this->user_name) {
			$this->errors['user_name'] = 'Tên người dùng không được bỏ trống!';
		} 
		if (!$this->user_address) {
			$this->errors['user_address'] = 'Địa chỉ không được bỏ trống!';
		} 


		if ($this->user_contact_no == '') {
			$this->errors['user_contact_no'] = 'SĐT không được bỏ trống!';
		} 
		if (!$this->user_profile) {
			$this->errors['user_profile'] = 'Ảnh đại diện không được bỏ trống!';
		} 

		$stmt = $this->db->prepare('SELECT * FROM tbl_user WHERE user_email_address = :user_email_address AND user_id != :user_id');
		$stmt->execute([
						'user_email_address' => $this->user_email_address,
						'user_id' => $this->user_id
					]);

		if (!$this->user_email_address) {
			$this->errors['user_email_address'] = 'Email không được bỏ trống!';
		} 
		else if ($stmt -> rowCount() > 0){
			$this->errors['user_email_address'] = 'Email đã tồn tại!';
		}
		//Bắt buộc là email trường
		else if (strpos($this->user_email_address, '@student.ctu.edu.vn') === false && strpos($this->user_email_address, '@ctu.edu.vn') === false) {
			$this->errors['user_email_address'] = 'Email phải có địa chỉ tên miền @student.ctu.edu.vn hoặc @ctu.edu.vn';
		}
		
		if (!$this->user_password) {
			$this->errors['user_password'] = 'Mật khẩu không được bỏ trống!';
		}
	
		return empty($this->errors);
	}

	public function validate_login() {

		$stmt = $this->db->prepare('SELECT * FROM tbl_user WHERE user_email_address = :user_email_address AND user_password = :user_password');
		$stmt->execute(['user_email_address' => $this->user_email_address,'user_password' => md5($this->user_password)]);

		if (!$this->user_email_address) {
			$this->errors['user_email_address'] = 'Email không được bỏ trống!';
		} 
		if (!$this->user_password) {
			$this->errors['user_password'] = 'Mật khẩu không được bỏ trống!';
		} 
		else if ($stmt -> rowCount() <= 0){
			$this->errors['login_check'] = 'Email hoặc mật khẩu không khớp!';
		}
		
		return empty($this->errors);
	}

	public function getValidationErrors() {
		return $this->errors;
	}

	public function save() {
		$result = false;
		if ($this->user_id >= 0) {
			$stmt = $this->db->prepare("UPDATE tbl_user 
										SET 
											user_name = :user_name, 
											user_address = :user_address, 
											user_contact_no = :user_contact_no,
											user_profile = :user_profile,
											user_email_address = :user_email_address,
											user_password = :user_password,
											user_updated_on = :user_updated_on

										WHERE user_id = :user_id");

			$result = $stmt->execute([	'user_name' => $this->user_name,
										'user_address' => $this->user_address,
										'user_contact_no' => $this->user_contact_no,
										'user_profile' => $this->user_profile,
										'user_email_address' => $this->user_email_address,
										'user_password' => md5($this->user_password),
										':user_updated_on'	=>	date('Y-m-d H:i:s'),

										'user_id' => $this->user_id]);
			$user_profile = $this->user_profile;
			move_uploaded_file($_FILES['user_profile']['tmp_name'], 'admin/uploads/'.$user_profile);
		} else {
			$stmt = $this->db->prepare("INSERT INTO tbl_user
										(user_name, user_address, user_contact_no, user_profile, user_email_address, user_password, user_verification_code, user_verification_status, user_unique_id, user_status, user_created_on) 
										VALUES (:user_name, :user_address, :user_contact_no, :user_profile, :user_email_address, :user_password, :user_verification_code, :user_verification_status, :user_unique_id, :user_status, :user_created_on)" );
			$result = $stmt->execute([
									'user_name' => $this->user_name,
									'user_address' => $this->user_address,
									'user_contact_no' => $this->user_contact_no,
									'user_profile' => $this->user_profile,
									'user_email_address' => $this->user_email_address,
									'user_password' => md5($this->user_password),
									'user_verification_code' => md5($this->user_verification_code),
									'user_verification_status' => 'No',
									'user_unique_id' => "U".$this->user_unique_id,
									'user_status'	=>	'Enable',
									'user_created_on'	=>	date('Y-m-d H:i:s')
								]);
			if ($result) {
				$this->user_id = $this->db->lastInsertId();
			}
			$user_profile = $this->user_profile;
			move_uploaded_file($_FILES['user_profile']['tmp_name'], 'admin/uploads/'.$user_profile);
		}
		return $result;
	}

	public function update(array $data, $file)
	{
		$this->fill_regist($data,$file);
		if ($this->validate_regist()) {
			return $this->save();
		}
		return false;
	}

	public function change_password() {
		$result = false;
		if($this->user_id >= 0) {
			$stmt = $this->db->prepare('UPDATE tbl_user 
			SET user_password = :user_password 
			WHERE user_id = :user_id');
			
			$result = $stmt->execute([
			'user_password' 	=> md5($this->user_password),
			'user_id'			=> $this->user_id
			]);
		}
		
		return $result;
	}

    public function find($user_id) {
		$stmt = $this->db->prepare('SELECT * FROM tbl_user where user_id = :user_id');
		$stmt->execute(['user_id' => $user_id]);
		if ($row = $stmt->fetch()) {
			$this->fillFromDB($row);
			return $this;
		}
		return null;
	}

	// public function find_user_unique_id($user_unique_id) {
	// 	$stmt = $this->db->prepare('SELECT * FROM tbl_user where user_unique_id = :user_unique_id');
	// 	$stmt->execute(['user_unique_id' => $user_unique_id]);
	// 	if ($row = $stmt->fetch()) {
	// 		$this->fillFromDB($row);
	// 		return $this;
	// 	}
	// 	return null;
	// }

	public function find_user_verification_code($user_verification_code) {
		$stmt = $this->db->prepare('SELECT * FROM tbl_user where user_verification_code = :user_verification_code');
		$stmt->execute(['user_verification_code' => $user_verification_code]);
		if ($row = $stmt->fetch()) {
			$this->fillFromDB($row);
			return $this;
		}
		return null;
	}

    public function all(){
		$users = [];
		$stmt = $this->db->prepare("SELECT * FROM tbl_user ORDER BY user_id ASC");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$user = new User($this->db);
			$user->fillFromDB($row);
			$users[] = $user;
		}
		return $users;
	}

	public function all_enable(){
		$users = [];
		$stmt = $this->db->prepare("SELECT * FROM tbl_user WHERE user_status = 'Enable' ORDER BY user_id ASC");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$user = new User($this->db);
			$user->fillFromDB($row);
			$users[] = $user;
		}
		return $users;
	}

	public function update_status_user() {
		if($this->user_status == 'Enable') {
			$stmt = $this->db->prepare("UPDATE tbl_user 
										SET user_status = 'Disable', user_updated_on = :user_updated_on  
										WHERE user_id = :user_id ");
			header("Location: user.php?msg=disable");
			return $stmt->execute([
									'user_updated_on' =>	date('Y-m-d H:i:s'),
									'user_id' => $this->user_id
								]);
			
		}	
		else if ($this->user_status == 'Disable') {
			$stmt = $this->db->prepare("UPDATE tbl_user
										SET user_status = 'Enable', user_updated_on = :user_updated_on   
										WHERE user_id = :user_id ");
			header("Location: user.php?msg=enable");
			return $stmt->execute([
									'user_updated_on' =>	date('Y-m-d H:i:s'),
									'user_id' => $this->user_id
								]);
		}
	}

	public function login_user()
	{
		$stmt = $this->db->prepare('SELECT * FROM tbl_user WHERE user_email_address = :user_email_address AND user_password = :user_password');
		$stmt->execute(['user_email_address' => $this->user_email_address,'user_password' => md5($this->user_password)]);

		$row = $stmt->fetch();
		if ($stmt -> rowCount() > 0) {
			session_start();
			$_SESSION["user_id"] = $row["user_id"];
			$_SESSION["user_email_address"] = $row["user_email_address"];
			$_SESSION["user_password"] = $row["user_password"];
			header('Location:issue_book_details.php');
		} 
		else {
			$alert = "Tài khoản và mật khẩu không khớp";
			return $alert;
		}  
	}

	public function verify_registration()
	{

		if($this->user_verification_status == 'No') {
			$stmt = $this->db->prepare("UPDATE tbl_user 
										SET user_verification_status = :user_verification_status 
										WHERE user_verification_code = :user_verification_code ");
			return $stmt->execute([
									'user_verification_status' =>	'Yes',
									'user_verification_code' => $this->user_verification_code
								]);
		}	
	}

	public function count_total_user_number() { 
		$total = 0;
		$stmt = $this->db->prepare("SELECT COUNT(user_id) AS Total FROM tbl_user WHERE user_status = 'Enable'");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$total = $row["Total"];
        }
		return $total;
    }

}
