<?php 
use CT275\Nienluannganh\Issue_book;
use CT275\Nienluannganh\Book;
use CT275\Nienluannganh\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once '../../vendor/autoload.php';
include "../../bootstrap.php";

$issue_book = new Issue_book($PDO);
$book = new Book($PDO);
$user = new User($PDO);

// Tạo một đối tượng PhpSpreadsheet mới
$spreadsheet = new Spreadsheet();

// Lấy trang tính đầu tiên trong file Excel
$sheet = $spreadsheet->getActiveSheet();

// Thiết lập các tiêu đề cho các cột trong bảng
$sheet->setCellValue('A1', 'Mã sách');
$sheet->setCellValue('B1', 'UID');
$sheet->setCellValue('C1', 'Ngày mượn');
$sheet->setCellValue('D1', 'Hạn trả');
$sheet->setCellValue('E1', 'Ngày xử lý hoàn trả');
$sheet->setCellValue('F1', 'Tiền trả trễ/bồi thường');
$sheet->setCellValue('G1', 'Trạng thái');

// Lấy dữ liệu từ CSDL
$issue_books = $issue_book->all();

// Thiết lập số dòng ban đầu cho dữ liệu
$row = 2;

foreach($issue_books as $issue_book) {
    $status = $issue_book->book_issue_status;
						
    $book_fines = $issue_book->book_fines;

    if($status == 'Issue') {
        $status = 'Mượn';
    } elseif($status == 'Not Return') {
        $status = 'Trễ hạn';
    } elseif($status == 'Return') {
        $status = 'Hoàn trả';
    } elseif($status == 'Damaged Return') {
        $status ='Hoàn trả (hỏng)';
    } elseif($status == 'Lost') {
        $status = 'Mất sách';
    }

    $sheet->setCellValue('A' . $row, $book->find(htmlspecialchars($issue_book->book_id))->book_isbn_number);
    $sheet->setCellValue('B' . $row, $user->find(htmlspecialchars($issue_book->user_id))->user_unique_id);
    $sheet->setCellValue('C' . $row, htmlspecialchars($issue_book->issue_date_time));
    $sheet->setCellValue('D' . $row, htmlspecialchars($issue_book->expected_return_date));
    $sheet->setCellValue('E' . $row, htmlspecialchars($issue_book->return_date_time));
    $sheet->setCellValue('F' . $row, htmlspecialchars($book_fines)." VNĐ");
    $sheet->setCellValue('G' . $row, $status);

    $row++;
}

// Đặt kiểu file excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

// Đặt tên file excel
header('Content-Disposition: attachment;filename="muontra.xlsx"');

// Tắt các bộ đệm
header('Cache-Control: max-age=0');

// Tạo một đối tượng Xlsx mới và lưu file excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');


?>