<?php

namespace CT275\Nienluannganh;

class Location_rack
{
	private $db;
	private $location_rack_id = -1;

	public $location_rack_name;
	public $location_rack_status;
	public $location_rack_created_on;
	public $location_rack_updated_on;

	private $errors = [];

	public function getId()
	{
		return $this->location_rack_id;
	}

	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

	// Nhập
	public function fill(array $data) {
		if (isset($data['location_rack_name'])) {
			$this->location_rack_name = trim($data['location_rack_name']);
		}
		if (isset($data['location_rack_status'])) {
			$this->location_rack_status = trim($data['location_rack_status']);
		}
		if (isset($data['location_rack_created_on'])) {
			$this->location_rack_created_on = $data['location_rack_created_on'];
		}
		if (isset($data['location_rack_updated_on'])) {
			$this->location_rack_updated_on = $data['location_rack_updated_on'];
		}
		return $this;
	}

	// Xác thực
	public function validate() {

		$stmt = $this->db->prepare('SELECT * FROM tbl_location_rack WHERE location_rack_name = :location_rack_name AND location_rack_id != :location_rack_id');
		$stmt->execute([
						'location_rack_name' => $this->location_rack_name,
						'location_rack_id' => $this->location_rack_id
					]);

		if (!$this->location_rack_name) {
			$this->errors['location_rack_name'] = 'Tên vị trí kệ không được bỏ trống!';
		} 
		else if ($stmt -> rowCount() > 0){
			$this->errors['location_rack_name'] = 'Tên vị trí kệ đã tồn tại!';
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
		'location_rack_id' => $this->location_rack_id,
		'location_rack_name' => $this->location_rack_name,
		'location_rack_status' => $this->location_rack_status,
		'location_rack_created_on' => $this->location_rack_created_on,
		'location_rack_updated_on' => $this->location_rack_updated_on
		] = $row;
		return $this;
	}

	public function save() {
		$result = false;
		if ($this->location_rack_id >= 0) {
			$stmt = $this->db->prepare('UPDATE tbl_location_rack 
										SET location_rack_name = :location_rack_name, location_rack_updated_on = :location_rack_updated_on 
										WHERE location_rack_id = :location_rack_id');
			$result = $stmt->execute([
										'location_rack_name' => $this->location_rack_name,
										'location_rack_updated_on'=>	date('Y-m-d H:i:s'),
										'location_rack_id' => $this->location_rack_id
									]);
		} else {
			$stmt = $this->db->prepare('INSERT INTO tbl_location_rack(location_rack_name,location_rack_status,location_rack_created_on) 
							VALUES(:location_rack_name, :location_rack_status, :location_rack_created_on)');
			$result = $stmt->execute([
										'location_rack_name' => $this->location_rack_name,
										'location_rack_status' => 'Enable',
										'location_rack_created_on' => date('Y-m-d H:i:s')
									]);
			if ($result) {
				$this->location_rack_id = $this->db->lastInsertId();
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
	
	public function find($location_rack_id) {
		$stmt = $this->db->prepare('SELECT * FROM tbl_location_rack WHERE location_rack_id = :location_rack_id');
		$stmt->execute(['location_rack_id' => $location_rack_id]);
		if ($row = $stmt->fetch()) {
			$this->fillFromDB($row);
			return $this;
		}
		return null;
	}

	public function all(){
		$location_racks = [];
		$stmt = $this->db->prepare("SELECT * FROM tbl_location_rack ORDER BY location_rack_name ASC");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$location_rack = new location_rack($this->db);
			$location_rack->fillFromDB($row);
			$location_racks[] = $location_rack;
		}
		return $location_racks;
	}

	public function all_enable(){
		$location_racks = [];
		$stmt = $this->db->prepare("SELECT * FROM tbl_location_rack WHERE location_rack_status = 'Enable' ORDER BY location_rack_name ASC");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$location_rack = new location_rack($this->db);
			$location_rack->fillFromDB($row);
			$location_racks[] = $location_rack;
		}
		return $location_racks;
	}


	public function delete_location_rack() {
		$stmt = $this->db->prepare('DELETE FROM tbl_location_rack where location_rack_id = :location_rack_id');
		return $stmt->execute([':location_rack_id' => $this->location_rack_id]);
	}

	public function update_status_location_rack() {
		if($this->location_rack_status == 'Enable') {
			$stmt = $this->db->prepare("UPDATE tbl_location_rack 
										SET location_rack_status = 'Disable', location_rack_updated_on = :location_rack_updated_on  
										WHERE location_rack_id = :location_rack_id ");
			header("Location: location_rack.php?msg=disable");
			return $stmt->execute([
									'location_rack_updated_on' =>	date('Y-m-d H:i:s'),
									'location_rack_id' => $this->location_rack_id
								]);
			
		}	
		else if ($this->location_rack_status == 'Disable') {
			$stmt = $this->db->prepare("UPDATE tbl_location_rack
										SET location_rack_status = 'Enable', location_rack_updated_on = :location_rack_updated_on   
										WHERE location_rack_id = :location_rack_id ");
			header("Location: location_rack.php?msg=enable");
			return $stmt->execute([
									'location_rack_updated_on' =>	date('Y-m-d H:i:s'),
									'location_rack_id' => $this->location_rack_id
								]);
			
		}
	}

	public function count_total_location_rack_number() { 
		$total = 0;
		$stmt = $this->db->prepare(" SELECT COUNT(location_rack_id) AS Total FROM tbl_location_rack WHERE location_rack_status = 'Enable'");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$total = $row["Total"];
        }
		return $total;
    }


}
