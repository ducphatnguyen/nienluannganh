<?php

namespace CT275\Nienluannganh;

class feedback
{
	private $db;
	private $feedback_id = -1;

    public $user_id;
	public $feedback_title;
	public $feedback_content;

	private $errors = [];

	public function getId()
	{
		return $this->feedback_id;
	}

	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

	// Nhập
	public function fill(array $data) {
		if (isset($data['user_id'])) {
			$this->user_id = trim($data['user_id']);
		}
		if (isset($data['feedback_title'])) {
			$this->feedback_title = trim($data['feedback_title']);
		}
		if (isset($data['feedback_content'])) {
			$this->feedback_content = $data['feedback_content'];
		}
		return $this;
	}

	// Xác thực
	public function validate() {
		if (!$this->feedback_title) {
			$this->errors['feedback_title'] = 'Chủ đề không được bỏ trống!';
		} 
        if (!$this->feedback_content) {
			$this->errors['feedback_content'] = 'Nội dung không được bỏ trống!';
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
		'feedback_id' => $this->feedback_id,
		'user_id' => $this->user_id,
		'feedback_title' => $this->feedback_title,
		'feedback_content' => $this->feedback_content,
		] = $row;
		return $this;
	}

	public function save() {
		$result = false;
		
        $stmt = $this->db->prepare('INSERT INTO tbl_feedback(user_id,feedback_title,feedback_content) 
                        VALUES(:user_id, :feedback_title, :feedback_content)');
        $result = $stmt->execute([
                                    'user_id' => $this->user_id,
                                    'feedback_title' => $this->feedback_title,
                                    'feedback_content' => $this->feedback_content
                                ]);
        if ($result) {
            $this->feedback_id = $this->db->lastInsertId();
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
	
	public function find($feedback_id) {
		$stmt = $this->db->prepare('SELECT * FROM tbl_feedback WHERE feedback_id = :feedback_id');
		$stmt->execute(['feedback_id' => $feedback_id]);
		if ($row = $stmt->fetch()) {
			$this->fillFromDB($row);
			return $this;
		}
		return null;
	}

	public function all(){
		$feedbacks = [];
		$stmt = $this->db->prepare("SELECT * FROM tbl_feedback ORDER BY feedback_id DESC");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$feedback = new feedback($this->db);
			$feedback->fillFromDB($row);
			$feedbacks[] = $feedback;
		}
		return $feedbacks;
	}

	public function delete_feedback() {
		$stmt = $this->db->prepare('DELETE FROM tbl_feedback where feedback_id = :feedback_id');
		
		return $stmt->execute([':feedback_id' => $this->feedback_id]);
	}


}
