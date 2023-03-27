<?php

namespace CT275\Nienluannganh;

class Admin
{
	private $db;
	private $admin_id = -1;

    public $admin_email,$admin_password;
    private $errors = [];
    
    public function getId()
	{
		return $this->admin_id;
	}

	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

    public function fill(array $data) {
		if (isset($data['admin_email'])) {
			$this->admin_email = trim($data['admin_email']);
		}
		if (isset($data['admin_password'])) {
			$this->admin_password = trim($data['admin_password']);
		}
		return $this;
	}

    protected function fillFromDB(array $row)
	{
		[
		'admin_id' => $this->admin_id,
		'admin_email' => $this->admin_email,
        'admin_password' => $this->admin_password
		] = $row;
		return $this;
	}

	public function validate_login() {

		$stmt = $this->db->prepare('SELECT * FROM tbl_admin WHERE admin_email = :admin_email AND admin_password = :admin_password');
		$stmt->execute(['admin_email' => $this->admin_email,'admin_password' => md5($this->admin_password)]);

		if (!$this->admin_email) {
			$this->errors['admin_email'] = 'Email không được bỏ trống!';
		} 
		if (!$this->admin_password) {
			$this->errors['admin_password'] = 'Mật khẩu không được bỏ trống!';
		} 
		else if ($stmt -> rowCount() <= 0){
			$this->errors['login_check'] = 'Email hoặc mật khẩu không khớp!';
		}
		
		return empty($this->errors);
	}

	public function getValidationErrors() {
		return $this->errors;
	}

	public function change_password() {
		$result = false;
		if($this->admin_id >= 0) {
			$stmt = $this->db->prepare('UPDATE tbl_admin 
			SET admin_password = :admin_password 
			WHERE admin_id = :admin_id');
			
			$result = $stmt->execute([
			'admin_password' 	=> md5($this->admin_password),
			'admin_id'			=> $this->admin_id
			]);
		}
		
		return $result;
	}

	public function all(){
		$admins = [];
		$stmt = $this->db->prepare("SELECT * FROM tbl_admin LIMIT 1");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$admin = new Setting($this->db);
			$admin->fillFromDB($row);
			$admins[] = $admin;
		}
		return $admins;
	}

	public function find($admin_id) {
		$stmt = $this->db->prepare('SELECT * FROM tbl_admin WHERE admin_id = :admin_id');
		$stmt->execute(['admin_id' => $admin_id]);
		if ($row = $stmt->fetch()) {
			$this->fillFromDB($row);
			return $this;
		}
		return null;
	}

	public function find_admin($admin_email,$admin_password) {
		$stmt = $this->db->prepare('SELECT * FROM tbl_admin WHERE admin_email = :admin_email AND admin_password = :admin_password');
		$stmt->execute(['admin_email' => $admin_email, 'admin_password' => $admin_password ]);
		if ($row = $stmt->fetch()) {
			$this->fillFromDB($row);
			return $this;
		}
		return null;
	}

    public function login_admin()
	{
		$stmt = $this->db->prepare('SELECT * FROM tbl_admin WHERE admin_email = :admin_email AND admin_password = :admin_password');
		$stmt->execute(['admin_email' => $this->admin_email,'admin_password' => md5($this->admin_password)]);

		$row = $stmt->fetch();
		if ($stmt -> rowCount() > 0) {
			session_start();
			$_SESSION["admin_id"] = $row["admin_id"];
			$_SESSION["admin_email"] = $row["admin_email"];
			$_SESSION["admin_password"] = $row["admin_password"];
			header('Location:index.php');
		} 
		else {
			$alert = "Tài khoản và mật khẩu không khớp";
			return $alert;
		}  
	}

}
