<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.108.0">
    <title>Headers · Bootstrap v5.3</title>
    <!-- 1 -->
    <link href="asset/css/simple-datatables-style.css" rel="stylesheet" />
    <link href="asset/css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- 2 -->
    <link href="../asset/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
      body {
        background: #ffffe6;
        /* background-repeat: no-repeat; */
        background-size: cover;
      }




      .text-small {
        font-size: 85%;
      }

      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      .bi {
        vertical-align: -.125em;
        fill: currentColor;
      }

      header {
        padding: 10px 0;
      }

      .header-buttons a {
        text-decoration: none;
        color: #fff;
      }

      .navbar-light .navbar-nav .nav-link {
          color: #0033cc;
      }

      .nav-link {
        color: #333; /* màu chữ */
        font-size: 18px; /* cỡ chữ */
        font-weight: bold; /* độ đậm */
        text-transform: uppercase; /* chuyển thành chữ hoa */
        padding: 5px 15px; /* khoảng cách giữa các nút */
      }

    </style>
</head>
<?php
  use CT275\Nienluannganh\Setting;
  $setting = new Setting($PDO);
?>
<body>

    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="bootstrap" viewBox="0 0 118 94">
            <title>Bootstrap</title>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z"></path>
        </symbol>
    </svg>

    <header>
  <img src="https://www.ctu.edu.vn/images/upload/logomobile.png" alt="Library logo" style="max-width: 300px; height: auto;">  
  <div id="language-selector" style="float:right" class="d-flex justify-content-end mx-3"> 
    <img src="https://anvientv.com.vn/uploads/upload/1664959678_anh-co-do-sao-vang.jpg" style="width:2%;"  alt="US flag" id="us-flag">
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e2/Flag_of_the_United_States_%28Pantone%29.svg/285px-Flag_of_the_United_States_%28Pantone%29.svg.png" style="width:3%" alt="Vietnam flag" id="vietnam-flag">
  </div>
  <nav class="navbar navbar-expand-lg navbar-light bg-warning">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="search_book.php">Sách</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="issue_book_details.php">Mượn Trả</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="feedback.php">Góp ý</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="rule.php">Quy định</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="profile.php">Hồ sơ</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Đăng xuất</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>



<div class="container">
    