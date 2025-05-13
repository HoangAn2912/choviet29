<?php
include_once("controller/cLoginLogout.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Đăng nhập tài khoản</title>

  <!-- CSS & Font -->
  <link rel="stylesheet" href="loginlogout/css/style.css" type="text/css" media="all" />
  <link rel="stylesheet" href="loginlogout/css/font-awesome.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- Toastify -->
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

  <!-- Custom inline -->
  <style>
    .bgr {
        background-image: url(loginlogout/video/keyboard.jpg);
        background-repeat: repeat-y;
        background-size: cover;
        background-position: center;
        min-height: 100vh;
    }
    .sub-main-w3ls {
      padding: 0 2.5em 2.5em;
    }

    /*=== FORM INPUT ===*/
    .input-group {
        position: relative;
        display: block;
        width: 100%;
    }

    .input-group input {
        width: 100%;
        padding: 12px 44px 12px 16px;
        border: none;
        border-radius: 4px;
        background: #eaf2ff;
        font-size: 16px;
        color: #333;
        box-sizing: border-box;
        height: 45px;
    }

    .input-group .icon1 {
        position: absolute;
        right: 16px;
        top: 65%;
        transform: translateY(-50%);
        color: #fcb50e;
        font-size: 18px;
        pointer-events: none;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .input-group .icon2 {
        position: absolute;
        right: 19px;
        top: 70%;
        transform: translateY(-50%);
        color: #fcb50e;
        font-size: 24px;
        pointer-events: none;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    /*=== NÚT ĐĂNG KÝ ===*/
    .register-btn {
        width: 100%;
        padding: 14px;
        background-color: #fcb50e;
        color: #000;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-top: 24px;
    }

    .register-btn:hover {
        background-color: #e0a800;
    }
  </style>
</head>
<body>
  <div class="bgr">
    <div class="main-container">
      <div class="header-w3l">
        <h1>Đăng nhập tài khoản</h1>
      </div>

      <div class="main-content-agile">
        <div class="w3ls-pro">
          <h2>Đăng nhập</h2>
        </div>

        <div class="sub-main-w3ls">
          <form action="" method="post">
            <!-- Email -->
            <div class="input-group">
              <input placeholder="Email" name="email" type="email" style="margin-top:24px; width: 83%;" required>
              <span class="icon1"><i class="fa fa-envelope" aria-hidden="true"></i></span>
            </div>

            <!-- Password -->
            <div class="input-group">
              <input placeholder="Mật khẩu" name="password" type="password" style="margin-top:24px; width: 83%;" required>
              <span class="icon2"><i class="fa fa-unlock-alt" aria-hidden="true"></i></span>
            </div>

            <!-- Button -->
            <button type="submit" name="login" class="register-btn" style="width: 83%;">Đăng nhập</button>

            <!-- Link -->
            <p style="text-align:center; margin-top:10px; font-family: 'Roboto', sans-serif;">
              Bạn chưa có tài khoản? <a href="/project/index.php?signup">Đăng ký</a>
            </p>
          </form>
        </div>
      </div>

        <div class="footer">
            <p style="font-family: 'Roboto', sans-serif;">
                &copy; 2025 Chợ Việt – Nền tảng mua bán đồ cũ C2C. Thiết kế và phát triển bởi nhóm 18.
            </p>
        </div>
    </div>
  </div>

  <!-- Toast -->
  <script src="js/toast.js"></script>
  <?php include_once("view/toastify.php"); ?>
</body>
</html>
