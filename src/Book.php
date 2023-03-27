<?php

namespace CT275\Nienluannganh;

class Book
{
	private $db;
	private $book_id = -1;

	public $category_id;
    public $author_id;
    public $location_rack_id;
    public $publisher_id;
    public $book_image;
	public $book_name;
	public $book_isbn_number;
    public $book_no_of_copy;
    public $book_price;
    public $book_description;
	public $book_status;
	public $book_created_on;
	public $book_updated_on;

	private $errors = [];

	public function getId()
	{
		return $this->book_id;
	}

	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

	// Nhập
	public function fill(array $data, $file) {
		if (isset($data['category_id'])) {
            $this->category_id = trim($data['category_id']);
        }
        if (isset($data['author_id'])) {
            $this->author_id = trim($data['author_id']);
        }
        if (isset($data['publisher_id'])) {
            $this->publisher_id = trim($data['publisher_id']);
        }
        if (isset($data['location_rack_id'])) {
            $this->location_rack_id = trim($data['location_rack_id']);
        }
        if (isset($file)) {
			$this->book_image = $file["book_image"]["name"];
		}
		if (isset($data['book_name'])) {
			$this->book_name = trim($data['book_name']);
		}
		if (isset($data['book_isbn_number'])) {
            $this->book_isbn_number = trim($data['book_isbn_number']);
        }
		if (isset($data['book_no_of_copy'])) {
            $this->book_no_of_copy = trim($data['book_no_of_copy']);
        }
        if (isset($data['book_price'])) {
            $this->book_price = trim($data['book_price']);
        }
        if (isset($data['book_description'])) {
            $this->book_description = trim($data['book_description']);
        }
		if (isset($data['book_status'])) {
			$this->book_status = trim($data['book_status']);
		}
		if (isset($data['book_created_on'])) {
			$this->book_created_on = $data['book_created_on'];
		}
		if (isset($data['book_updated_on'])) {
			$this->book_updated_on = $data['book_updated_on'];
		}
		return $this;
	}

	// Xác thực
	public function validate() {

		if (!$this->category_id) {
            $this->errors['category_id'] = 'Thể loại sách không được bỏ trống!';
        }
        if (!$this->author_id) {
            $this->errors['author_id'] = 'Tên tác giả sách không được bỏ trống!';
        }
        if (!$this->publisher_id) {
            $this->errors['publisher_id'] = 'Nhà xuất bản sách không được bỏ trống!';
        }
        if (!$this->location_rack_id) {
            $this->errors['location_rack_id'] = 'Vị trí sách trong kệ sách không được bỏ trống!';
        }

		$stmt = $this->db->prepare('SELECT * FROM tbl_book WHERE book_name = :book_name AND book_id != :book_id');
		$stmt->execute([
                        'book_name' => $this->book_name,
                        'book_id' => $this->book_id
                    ]);

		if (!$this->book_name) {
			$this->errors['book_name'] = 'Tên sách không được bỏ trống!';
		} 
		else if ($stmt -> rowCount() > 0){
			$this->errors['book_name'] = 'Tên sách đã tồn tại!';
		}

		if (!$this->book_isbn_number) {
            $this->errors['book_isbn_number'] = 'Mã định danh sách không được bỏ trống!';
        }
        
        if (!$this->book_no_of_copy) {
            $this->errors['book_no_of_copy'] = 'Số lượng sách không được rỗng!';
        }

        if (!$this->book_price) {
            $this->errors['book_price'] = 'Giá sách không được rỗng!';
        }

        if (!$this->book_image) {
            $this->errors['book_image'] = 'Ảnh sách không được rỗng!';
        }

		return empty($this->errors);
	}
	
    	// Xác thực
	// public function validate_issuebook() {

	// 	$stmt1 = $this->db->prepare('SELECT * FROM tbl_book WHERE book_isbn_number = :book_isbn_number');
	// 	$stmt1->execute(['book_isbn_number' => $this->book_isbn_number]);
    //     if ($stmt1 -> rowCount() <= 0){
    //         $this->errors['book_isbn_number'] = 'Sách không tồn tại!';
    //     }

    //     $stmt2 = $this->db->prepare('SELECT * FROM tbl_book WHERE book_status = :book_status' );
	// 	$stmt2->execute([
    //                     'book_status' => 'Disable'
    //                 ]);
    //     if ($stmt2 -> rowCount() > 0){
    //         $this->errors['book_status'] = 'Sách đã ngưng hoạt động!';
    //     }

	// 	return empty($this->errors);
	// }

	public function getValidationErrors() {
		return $this->errors;
	}

	// Đổ dữ liệu
	protected function fillFromDB(array $row)
    {
        [
        'book_id'           => $this->book_id,
        'category_id'       => $this->category_id,
        'author_id'         => $this->author_id,
        'publisher_id'      => $this->publisher_id,
        'location_rack_id'  => $this->location_rack_id,
        'book_image'        => $this->book_image,
        'book_name'         => $this->book_name,
        'book_isbn_number'  => $this->book_isbn_number,
        'book_no_of_copy'   => $this->book_no_of_copy,
        'book_price'        => $this->book_price,
        'book_description'  => $this->book_description,
        'book_status'       => $this->book_status,
        'book_created_on'   => $this->book_created_on,
        'book_updated_on'   => $this->book_updated_on
        ] = $row;
        return $this;
    }
    
	public function save() {
        $result = false;
        if ($this->book_id >= 0) {
            $stmt = $this->db->prepare('UPDATE tbl_book 
                                        SET category_id = :category_id,
                                            author_id = :author_id, 
                                            publisher_id = :publisher_id, 
                                            location_rack_id = :location_rack_id, 
                                            book_image = :book_image, 
                                            book_name = :book_name, 
                                            book_isbn_number = :book_isbn_number, 
                                            book_no_of_copy = :book_no_of_copy, 
                                            book_price = :book_price,
                                            book_description = :book_description, 
                                            book_updated_on = :book_updated_on   
                                        WHERE book_id = :book_id');
            
            $result = $stmt->execute([
                                        'category_id'       => $this->category_id,
                                        'author_id'         => $this->author_id,
                                        'publisher_id'      => $this->publisher_id,
                                        'location_rack_id'  => $this->location_rack_id,
                                        'book_image'        => $this->book_image,
                                        'book_name'         => $this->book_name,
                                        'book_isbn_number'  => $this->book_isbn_number,
                                        'book_no_of_copy'   => $this->book_no_of_copy,
                                        'book_price'        => $this->book_price,
                                        'book_description'  => $this->book_description,
                                        'book_updated_on'   => date('Y-m-d H:i:s'),
                                        'book_id'           => $this->book_id
                                    ]);
            $book_image = $this->book_image;
            move_uploaded_file($_FILES['book_image']['tmp_name'], 'uploads/'.$book_image);
            
        } else {
            $stmt = $this->db->prepare('INSERT INTO tbl_book
                                        (category_id, author_id, publisher_id, location_rack_id, book_image, book_name, book_isbn_number, book_no_of_copy, book_price, book_description, book_status, book_created_on) 
                                        VALUES (:category_id, :author_id, :publisher_id, :location_rack_id, :book_image, :book_name, :book_isbn_number, :book_no_of_copy, :book_price, :book_description, :book_status, :book_created_on)');
            $result = $stmt->execute([
                                        'category_id' => $this->category_id,
                                        'author_id' => $this->author_id,
                                        'publisher_id' => $this->publisher_id,
                                        'location_rack_id' => $this->location_rack_id,
                                        'book_image' => $this->book_image,
                                        'book_name' => $this->book_name,
                                        'book_isbn_number' => $this->book_isbn_number,
                                        'book_no_of_copy' => $this->book_no_of_copy,
                                        'book_price' => $this->book_price,
                                        'book_description' => $this->book_description,
                                        'book_status' => 'Enable',
                                        'book_created_on' => date('Y-m-d H:i:s')
                                    ]);
            $book_image = $this->book_image;
            move_uploaded_file($_FILES['book_image']['tmp_name'], 'uploads/'.$book_image);
			
            if ($result) {
                $this->book_id = $this->db->lastInsertId();
            }
        }
        return $result;
    }
    
    public function update(array $data,  $file)
    {
        $this->fill($data,  $file);
        if ($this->validate()) {
            return $this->save();
        }
        return false;
    }

    public function find($book_id) {
        $stmt = $this->db->prepare('SELECT * FROM tbl_book WHERE book_id = :book_id');
        $stmt->execute(['book_id' => $book_id]);
        if ($row = $stmt->fetch()) {
            $this->fillFromDB($row);
            return $this;
        }
        return null;
    }

    // public function find_book_isbn_number($book_isbn_number) {
    //     $stmt = $this->db->prepare('SELECT * FROM tbl_book WHERE book_isbn_number = :book_isbn_number');
    //     $stmt->execute(['book_isbn_number' => $book_isbn_number]);
    //     if ($row = $stmt->fetch()) {
    //         $this->fillFromDB($row);
    //         return $this;
    //     }
    //     return null;
    // }

    public function all(){
        $books = [];
        $stmt = $this->db->prepare("SELECT * FROM tbl_book ORDER BY book_name ASC");
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $book = new Book($this->db);
            $book->fillFromDB($row);
            $books[] = $book;
        }
        return $books;
    }

    public function all_enable(){
        $books = [];
        $stmt = $this->db->prepare("SELECT * FROM tbl_book WHERE book_status = 'Enable' ORDER BY book_name ASC");
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $book = new Book($this->db);
            $book->fillFromDB($row);
            $books[] = $book;
        }
        return $books;
    }

    public function delete_book() {
        $stmt = $this->db->prepare('DELETE FROM tbl_book where book_id = :book_id');
        return $stmt->execute([':book_id' => $this->book_id]);
    }
    
    public function update_status_book() {
        if($this->book_status == 'Enable') {
            $stmt = $this->db->prepare("UPDATE tbl_book 
                                        SET book_status = 'Disable', book_updated_on = :book_updated_on  
                                        WHERE book_id = :book_id ");
			header("Location: book.php?msg=disable");
            return $stmt->execute([
                                    'book_updated_on' =>    date('Y-m-d H:i:s'),
                                    'book_id' => $this->book_id
                                ]);
			
        }   
        else if ($this->book_status == 'Disable') {
            $stmt = $this->db->prepare("UPDATE tbl_book
                                        SET book_status = 'Enable', book_updated_on = :book_updated_on   
                                        WHERE book_id = :book_id ");
			header("Location: book.php?msg=enable");
            return $stmt->execute([
                                    'book_updated_on' =>    date('Y-m-d H:i:s'),
                                    'book_id' => $this->book_id
                                ]);
        }
    }

    public function count_total_book_number() { 
		$total = 0;
		$stmt = $this->db->prepare("SELECT COUNT(book_id) AS Total FROM tbl_book WHERE book_status = 'Enable'");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$total = $row["Total"];
        }
		return $total;
    }

    public function update_plus_quantity_book() {
		$stmt = $this->db->prepare("  	UPDATE tbl_book 
										SET book_no_of_copy = book_no_of_copy + 1 
										WHERE book_id = :book_id");
		return $stmt->execute(['book_id' => $this->book_id]);
	}

    public function update_subtract_quantity_book() {
		$stmt = $this->db->prepare("  	UPDATE tbl_book 
										SET book_no_of_copy = book_no_of_copy - 1,
                                            book_updated_on = :book_updated_on
										WHERE book_id = :book_id");
        return $stmt->execute([
                                'book_updated_on' =>    date('Y-m-d H:i:s'),
                                'book_id' => $this->book_id
                    ]);
	}


}






