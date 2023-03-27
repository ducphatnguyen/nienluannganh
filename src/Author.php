<?php

namespace CT275\Nienluannganh;

class Author
{
	private $db;
	private $author_id = -1;

	public $author_name;
	public $author_status;
	public $author_created_on;
	public $author_updated_on;

	private $errors = [];

	public function getId()
	{
		return $this->author_id;
	}

	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

	// Nhập
	public function fill(array $data) {
		if (isset($data['author_name'])) {
			$this->author_name = trim($data['author_name']);
		}
		if (isset($data['author_status'])) {
			$this->author_status = trim($data['author_status']);
		}
		if (isset($data['author_created_on'])) {
			$this->author_created_on = $data['author_created_on'];
		}
		if (isset($data['author_updated_on'])) {
			$this->author_updated_on = $data['author_updated_on'];
		}
		return $this;
	}

	// Xác thực
	public function validate() {

		$stmt = $this->db->prepare('SELECT * FROM tbl_author WHERE author_name = :author_name AND author_id != :author_id');
		$stmt->execute([
						'author_name' => $this->author_name,
						'author_id' => $this->author_id
					]);

		if (!$this->author_name) {
			$this->errors['author_name'] = 'Tên tác giả không được bỏ trống!';
		} 
		else if ($stmt -> rowCount() > 0){
			$this->errors['author_name'] = 'Tên tác giả đã tồn tại!';
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
		'author_id' => $this->author_id,
		'author_name' => $this->author_name,
		'author_status' => $this->author_status,
		'author_created_on' => $this->author_created_on,
		'author_updated_on' => $this->author_updated_on
		] = $row;
		return $this;
	}

	public function save() {
		$result = false;
		if ($this->author_id >= 0) {
			$stmt = $this->db->prepare('UPDATE tbl_author 
										SET author_name = :author_name, author_updated_on = :author_updated_on 
										WHERE author_id = :author_id');
			$result = $stmt->execute([
										'author_name' => $this->author_name,
										'author_updated_on'=>	date('Y-m-d H:i:s'),
										'author_id' => $this->author_id
									]);
			
		} else {
			$stmt = $this->db->prepare('INSERT INTO tbl_author(author_name,author_status,author_created_on) 
							VALUES(:author_name, :author_status, :author_created_on)');
			$result = $stmt->execute([
										'author_name' => $this->author_name,
										'author_status' => 'Enable',
										'author_created_on' => date('Y-m-d H:i:s')
									]);
			if ($result) {
				$this->author_id = $this->db->lastInsertId();
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
	
	public function find($author_id) {
		$stmt = $this->db->prepare('SELECT * FROM tbl_author WHERE author_id = :author_id');
		$stmt->execute(['author_id' => $author_id]);
		if ($row = $stmt->fetch()) {
			$this->fillFromDB($row);
			return $this;
		}
		return null;
	}

	public function all(){
		$authors = [];
		$stmt = $this->db->prepare("SELECT * FROM tbl_author ORDER BY author_name ASC");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$author = new Author($this->db);
			$author->fillFromDB($row);
			$authors[] = $author;
		}
		return $authors;
	}

	public function all_enable(){
		$authors = [];
		$stmt = $this->db->prepare("SELECT * FROM tbl_author WHERE author_status = 'Enable' ORDER BY author_name ASC");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$author = new Author($this->db);
			$author->fillFromDB($row);
			$authors[] = $author;
		}
		return $authors;
	}

	public function delete_author() {
		$stmt = $this->db->prepare('DELETE FROM tbl_author where author_id = :author_id');
		
		return $stmt->execute([':author_id' => $this->author_id]);
	}

	public function update_status_author() {
		if($this->author_status == 'Enable') {
			$stmt = $this->db->prepare("UPDATE tbl_author 
										SET author_status = 'Disable', author_updated_on = :author_updated_on  
										WHERE author_id = :author_id ");
			header("Location: author.php?msg=disable");
			return $stmt->execute([
									'author_updated_on' =>	date('Y-m-d H:i:s'),
									'author_id' => $this->author_id
								]);
			
		}	
		else if ($this->author_status == 'Disable') {
			$stmt = $this->db->prepare("UPDATE tbl_author
										SET author_status = 'Enable', author_updated_on = :author_updated_on   
										WHERE author_id = :author_id ");
			header("Location: author.php?msg=enable");
			return $stmt->execute([
									'author_updated_on' =>	date('Y-m-d H:i:s'),
									'author_id' => $this->author_id
								]);
			
		}
	}

	public function count_total_author_number() { 
		$total = 0;
		$stmt = $this->db->prepare("SELECT COUNT(author_id) AS Total FROM tbl_author WHERE author_status = 'Enable'");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$total = $row["Total"];
        }
		return $total;
    }


}
