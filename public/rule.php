<?php
	include "../bootstrap.php";
	include 'function.php';

	if(!is_user_login())
	{
		header('location:user_login.php');
	}

    use CT275\Nienluannganh\Setting;
	$setting = new Setting($PDO);

?>

<?php include '../partials/header.php'; ?>

<main>

<div class="mt-4">
    <?php 
        $settings = $setting->all();
        foreach($settings as $setting):
	?>
    <div class="row" style="font-family: 'Roboto', sans-serif;">
        <div class="col-md-12">
            <h2 class="fw-normal mb-4">NỘI QUY THƯ VIỆN SÁCH</h2>
            <ol>
                <li>
                    <span class="list-header">Xuất trình thẻ SV hoặc CCCD khi vào phòng đọc.</span>
                </li>

                <li>
                    <span class="list-header">Không cho người khác mượn thẻ</span>
                </li>

                <li>
                    <span class="list-header">Khi vào thư viện phải để cặp, giỏ xách đúng nơi qui định.</span>
                </li>

                <li>
                    <span class="list-header">Phải giữ trật tự, an toàn trong thư viện: Đi nhẹ, nói khẽ, không hút thuốc lá, không mang chất cháy nổ vào thư viện.</span>
                </li>

                <li>
                    <span class="list-header">Giữ gìn mỹ quan trong thư viện. Không ăn uống trong thư viện và bỏ rác đúng nơi qui định. Không viết vẽ lên mặt bàn, lên tường, không ngồi gác chân lên ghế.</span>
                </li>

                <li>
                    <span class="list-header">Mượn và trả sách theo đúng qui định phòng đọc.</span>
                </li>

                <li>
                    <span class="list-header">Không mang sách ra khỏi thư viện khi chưa có sự đồng ý của thủ thư.</span>
                </li>

                <li>
                    <span class="list-header">Máy tính chỉ dùng để phục vụ tra cứu thông tin và hổ trợ quá trình học tập, nghiên cứu. Không truy cập vào các Website có nội dung không lành mạnh.</span>
                </li>

                <li>
                    <span class="list-header">Khuyến khích sử dụng máy tính cá nhân (laptop).</span>
                </li>
            </ol>
        </div>
    </div> 


    <!-- 2 -->
    <div class="row" style="font-family: 'Roboto', sans-serif;">
        <div class="col-md-12">
            <h2 class="fw-normal mb-4">NỘI QUY PHÒNG ĐỌC</h2>
            <ol>
                <li>
                    <strong class="list-header">Giờ mượn sách</strong>
                    <ul>
                        <li>Sáng 07:30 - 11:00,</li>
                        <li>Chiều 14:00 - 16:30,</li>
                        <li>Thứ 7, Chủ nhật và các ngày lễ nghỉ.</li>
                    </ul>
                </li>

                <li>
                    <strong class="list-header">Đọc tại chỗ</strong>
                    <ul>
                        <li>Mỗi bạn đọc chỉ được mượn tối đa <?=htmlspecialchars($setting->library_issue_total_book_per_user)?> cuốn. Đọc xong phải để lại chỗ cũ rồi mới được lấy cuốn khác.</li>
                        <li>Không làm ướt, xé, viết vẽ lên tài liệu thư viện, không đánh dấu tài liệu bằng cách gấp trang.</li>
                    </ul>
                </li>

                <li>
                    <strong class="list-header">Mượn về nhà</strong>
                    <ul>
                        <li>Số lượng đầu tên tài liệu và thời hạn mượn tài liệu về nhà được quy định theo từng loại hình bạn đọc như sau:</li>
                        <li>Giảng viên, sinh viên: được mượn tối đa <?=htmlspecialchars($setting->library_issue_total_book_per_user)?> cuốn sách trong thời hạn <?=htmlspecialchars($setting->library_total_book_issue_day)?> ngày và được phép gia hạn thêm 3 ngày.</li>
                        <li>Chi phí trễ hạn: <?=htmlspecialchars($setting->library_one_day_fine)?>đ/ngày.</li>
                    </ul>
                </li>

                <li>
                    <strong class="list-header">LƯU Ý:</strong>
                    <ul>
                        <li>Các trường hợp bồi thường:</li>
                        <li>Sách ướt hoặc mất sách phải đến bù gấp <?=htmlspecialchars($setting->library_lost_book_rate)?> lần giá tiền cuốn sách.</li>
                        <li>Sách hư hỏng như long bìa, rách trang phải đền <?=htmlspecialchars($setting->library_damaged_return_book_rate)*100?>% giá trị cuốn sách.</li>
                    </ul>
                </li>
            </ol>
        </div>
    </div> 

    <!-- 3 -->
    <div class="row" style="font-family: 'Roboto', sans-serif;">
        <div class="col-md-12">
            <h2 class="fw-normal mb-4">NỘI QUY PHÒNG TRA CỨU</h2>
            <ol>
                <li>
                    <span class="list-header">Không sử dụng máy tính sai mục đích như chát, chơi Game… hoặc vào các Website có nội dung không lành mạnh.</span>
                </li>

                <li>
                    <span class="list-header">Không gây ồn ào, đi lại tự do, làm mất trật tự ảnh hưởng đến cá nhân khác</span>
                </li>

                <li>
                    <span class="list-header">Không ăn uống, hút thuốc trong phòng tra cứu.</span>
                </li>

                <li>
                    <span class="list-header">Khi vào phòng tra cứu HS-SV phải để nón mũ, giỏ xách đúng nơi quy định.</span>
                </li>

                <li>
                    <span class="list-header">Liên hệ với thủ thư về mọi sự cố kỹ thuật trong quá trình sử dụng.</span>
                </li>

                <li>
                    <span class="list-header">Không cài đặt, thay đổi cấu hình máy tính. Không tự ý xê dịch, tháo lắp, sữa chữa máy tính</span>
                </li>

                <li>
                    <span class="list-header">HS-SV làm hư hại các thiết bị trong phòng tra cứu phải đền bù thiệt hại.Nếu HS-SV nào vi phạm các quy định trên sẽ phải chịu kỷ luật của nhà trường.</span>
                </li>

            </ol>
        </div>
    </div> 
    <p class="text-center">HIỆU TRƯỞNG</p>
    <p class="text-center">(Đã ký)</p>
    <?php endforeach ?>
</div>

</main>


<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3928.841454343721!2d105.76842661411114!3d10.029938975271696!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a0895a51d60719%3A0x9d76b0035f6d53d0!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBD4bqnbiBUaMah!5e0!3m2!1svi!2s!4v1674639389135!5m2!1svi!2s" width="100%" height="450px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

<?php include '../partials/footer.php'; ?>

<script type="text/javascript">
    $(document).ready(function () {
        $(document).click(function() {
            $(".alert").remove();
        });
        $(".alert").first().hide().fadeIn(500).delay(3000).fadeOut(500, function () {
            $(this).remove(); 
        });
    });
</script>