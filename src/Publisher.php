<?php

namespace CT275\Nienluannganh;

class Publisher
{
	private $db;
	private $publisher_id = -1;

	public $publisher_name;
	public $publisher_status;
	public $publisher_created_on;
	public $publisher_updated_on;

	private $errors = [];

	public function getId()
	{
		return $this->publisher_id;
	}

	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

	// Nhập
	public function fill(array $data) {
		if (isset($data['publisher_name'])) {
			$this->publisher_name = trim($data['publisher_name']);
		}
		if (isset($data['publisher_status'])) {
			$this->publisher_status = trim($data['publisher_status']);
		}
		if (isset($data['publisher_created_on'])) {
			$this->publisher_created_on = $data['publisher_created_on'];
		}
		if (isset($data['publisher_updated_on'])) {
			$this->publisher_updated_on = $data['publisher_updated_on'];
		}
		return $this;
	}

	// Xác thực
	public function validate() {

		$stmt = $this->db->prepare('SELECT * FROM tbl_publisher WHERE publisher_name = :publisher_name AND publisher_id != :publisher_id');
		$stmt->execute([
						'publisher_name' => $this->publisher_name,
						'publisher_id' => $this->publisher_id
					]);

		if (!$this->publisher_name) {
			$this->errors['publisher_name'] = 'Tên nhà xuất bản không được bỏ trống!';
		} 
		else if ($stmt -> rowCount() > 0){
			$this->errors['publisher_name'] = 'Tên nhà xuất bản đã tồn tại!';
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
		'publisher_id' => $this->publisher_id,
		'publisher_name' => $this->publisher_name,
		'publisher_status' => $this->publisher_status,
		'publisher_created_on' => $this->publisher_created_on,
		'publisher_updated_on' => $this->publisher_updated_on
		] = $row;
		return $this;
	}

	public function save() {
		$result = false;
		if ($this->publisher_id >= 0) {
			$stmt = $this->db->prepare('UPDATE tbl_publisher 
										SET publisher_name = :publisher_name, publisher_updated_on = :publisher_updated_on 
										WHERE publisher_id = :publisher_id');
			$result = $stmt->execute([
										'publisher_name' => $this->publisher_name,
										'publisher_updated_on'=>	date('Y-m-d H:i:s'),
										'publisher_id' => $this->publisher_id
									]);
		} else {
			$stmt = $this->db->prepare('INSERT INTO tbl_publisher(publisher_name,publisher_status,publisher_created_on) 
							VALUES(:publisher_name, :publisher_status, :publisher_created_on)');
			$result = $stmt->execute([
										'publisher_name' => $this->publisher_name,
										'publisher_status' => 'Enable',
										'publisher_created_on' => date('Y-m-d H:i:s')
									]);
			if ($result) {
				$this->publisher_id = $this->db->lastInsertId();
			}
			
		}
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
	
	public function find($publisher_id) {
		$stmt = $this->db->prepare('SELECT * FROM tbl_publisher WHERE publisher_id = :publisher_id');
		$stmt->execute(['publisher_id' => $publisher_id]);
		if ($row = $stmt->fetch()) {
			$this->fillFromDB($row);
			return $this;
		}
		return null;
	}

	public function all(){
		$publishers = [];
		$stmt = $this->db->prepare("SELECT * FROM tbl_publisher ORDER BY publisher_name ASC");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$publisher = new publisher($this->db);
			$publisher->fillFromDB($row);
			$publishers[] = $publisher;
		}
		return $publishers;
	}

	public function all_enable(){
		$publishers = [];
		$stmt = $this->db->prepare("SELECT * FROM tbl_publisher WHERE publisher_status = 'Enable' ORDER BY publisher_name ASC");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$publisher = new publisher($this->db);
			$publisher->fillFromDB($row);
			$publishers[] = $publisher;
		}
		return $publishers;
	}

	public function delete_publisher() {
		$stmt = $this->db->prepare('DELETE FROM tbl_publisher where publisher_id = :publisher_id');

		return $stmt->execute([':publisher_id' => $this->publisher_id]);
	}

	public function update_status_publisher() {
		if($this->publisher_status == 'Enable') {
			$stmt = $this->db->prepare("UPDATE tbl_publisher 
										SET publisher_status = 'Disable', publisher_updated_on = :publisher_updated_on  
										WHERE publisher_id = :publisher_id ");
			header("Location: publisher.php?msg=disable");
			return $stmt->execute([
									'publisher_updated_on' =>	date('Y-m-d H:i:s'),
									'publisher_id' => $this->publisher_id
								]);
			
		}	
		else if ($this->publisher_status == 'Disable') {
			$stmt = $this->db->prepare("UPDATE tbl_publisher
										SET publisher_status = 'Enable', publisher_updated_on = :publisher_updated_on   
										WHERE publisher_id = :publisher_id ");
			header("Location: publisher.php?msg=enable");
			return $stmt->execute([
									'publisher_updated_on' =>	date('Y-m-d H:i:s'),
									'publisher_id' => $this->publisher_id
								]);
			
		}
	}

	public function count_total_publisher_number() { 
		$total = 0;
		$stmt = $this->db->prepare("SELECT COUNT(publisher_id) AS Total FROM tbl_publisher WHERE publisher_status = 'Enable'");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$total = $row["Total"];
        }
		return $total;
    }
	


}
