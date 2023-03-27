<?php

namespace CT275\Nienluannganh;

class Category
{
	private $db;
	private $category_id = -1;

	public $category_name;
	public $category_status;
	public $category_created_on;
	public $category_updated_on;

	private $errors = [];

	public function getId()
	{
		return $this->category_id;
	}

	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

	// Nhập
	public function fill(array $data) {
		if (isset($data['category_name'])) {
			$this->category_name = trim($data['category_name']);
		}
		if (isset($data['category_status'])) {
			$this->category_status = trim($data['category_status']);
		}
		if (isset($data['category_created_on'])) {
			$this->category_created_on = $data['category_created_on'];
		}
		if (isset($data['category_updated_on'])) {
			$this->category_updated_on = $data['category_updated_on'];
		}
		return $this;
	}

	// Xác thực
	public function validate() {

		$stmt = $this->db->prepare('SELECT * FROM tbl_category WHERE category_name = :category_name AND category_id != :category_id');
		$stmt->execute([
						'category_name' => $this->category_name,
						'category_id' => $this->category_id
						
					]);

		if (!$this->category_name) {
			$this->errors['category_name'] = 'Tên thể loại không được bỏ trống!';
		} 
		else if ($stmt -> rowCount() > 0){
			$this->errors['category_name'] = 'Tên thể loại đã tồn tại!';
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
		'category_id' => $this->category_id,
		'category_name' => $this->category_name,
		'category_status' => $this->category_status,
		'category_created_on' => $this->category_created_on,
		'category_updated_on' => $this->category_updated_on
		] = $row;
		return $this;
	}

	public function save() {
		$result = false;
		if ($this->category_id >= 0) {
			$stmt = $this->db->prepare('UPDATE tbl_category 
										SET category_name = :category_name, category_updated_on = :category_updated_on 
										WHERE category_id = :category_id');
			$result = $stmt->execute([
										'category_name' => $this->category_name,
										'category_updated_on'=>	date('Y-m-d H:i:s'),
										'category_id' => $this->category_id
									]);

		} else {
			$stmt = $this->db->prepare('INSERT INTO tbl_category(category_name,category_status,category_created_on) 
							VALUES(:category_name, :category_status, :category_created_on)');
			$result = $stmt->execute([
										'category_name' => $this->category_name,
										'category_status' => 'Enable',
										'category_created_on' => date('Y-m-d H:i:s')
									]);
			if ($result) {
				$this->category_id = $this->db->lastInsertId();
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
	
	public function find($category_id) {
		$stmt = $this->db->prepare('SELECT * FROM tbl_category WHERE category_id = :category_id');
		$stmt->execute(['category_id' => $category_id]);
		if ($row = $stmt->fetch()) {
			$this->fillFromDB($row);
			return $this;
		}
		return null;
	}

	public function all(){
		$categorys = [];
		$stmt = $this->db->prepare("SELECT * FROM tbl_category ORDER BY category_name ASC");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$category = new category($this->db);
			$category->fillFromDB($row);
			$categorys[] = $category;
		}
		return $categorys;
	}

	public function all_enable(){
		$categorys = [];
		$stmt = $this->db->prepare("SELECT * FROM tbl_category WHERE category_status = 'Enable' ORDER BY category_name ASC");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$category = new category($this->db);
			$category->fillFromDB($row);
			$categorys[] = $category;
		}
		return $categorys;
	}

	public function delete_category() {
		$stmt = $this->db->prepare('DELETE FROM tbl_category where category_id = :category_id');
		return $stmt->execute([':category_id' => $this->category_id]);
	}

	public function update_status_category() {
		if($this->category_status == 'Enable') {
			$stmt = $this->db->prepare("UPDATE tbl_category 
										SET category_status = 'Disable', category_updated_on = :category_updated_on  
										WHERE category_id = :category_id ");
			header("Location: category.php?msg=disable");
			return $stmt->execute([
									'category_updated_on' =>	date('Y-m-d H:i:s'),
									'category_id' => $this->category_id
								]);
			
		}	
		else if ($this->category_status == 'Disable') {
			$stmt = $this->db->prepare("UPDATE tbl_category
										SET category_status = 'Enable', category_updated_on = :category_updated_on   
										WHERE category_id = :category_id ");
			header("Location: category.php?msg=enable");
			return $stmt->execute([
									'category_updated_on' =>	date('Y-m-d H:i:s'),
									'category_id' => $this->category_id
								]);
			
		}
	}

	public function count_total_category_number() { 
		$total = 0;
		$stmt = $this->db->prepare("SELECT COUNT(category_id) AS Total FROM tbl_category WHERE category_status = 'Enable'");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$total = $row["Total"];
        }
		return $total;
    }


}
