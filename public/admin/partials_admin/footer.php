
    </main>
    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Bản quyền thuộc về &copy; DucAdmin <?php echo date('Y'); ?></div>
                <div>
                    <a style="text-decoration: none;" href="#">Chính sách riêng tư</a>
                    &middot;
                    <a style="text-decoration: none;" href="#">Điều khoản &amp; Dịch vụ</a>
                </div>
            </div>
        </div>
    </footer>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="../asset/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../asset/js/scripts.js"></script>
    <script src="../asset/js/simple-datatables@latest.js" crossorigin="anonymous"></script>
    <script src="../asset/js/datatables-simple-demo.js"></script>

    <script type="text/javascript">
		$(document).ready(function () {
			$(document).click(function() {
				$(".alert").remove();
			});
			$(".alert").first().hide().fadeIn(500).delay(3000).fadeOut(500, function () {
				$(this).remove(); 
			});
		});

        $(document).ready(function () {
            $('div>select').selectize({
                sortField: 'text'
            });
        });
            
	</script>

    </body>

</html>