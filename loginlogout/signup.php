<?php
include_once("controller/cLoginLogout.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8" />
<title>Đăng ký tài khoản</title>
<link rel="stylesheet" href="loginlogout/css/style.css" type="text/css" media="all" />
<link rel="stylesheet" href="loginlogout/css/font-awesome.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<style>
    .bgr { background-image: url(loginlogout/video/keyboard.jpg); }
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

.input-group .icon1{
    position: absolute;
    right: 16px;
    top: 50%;
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

.input-group .icon3{
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #fcb50e;
    font-size: 21px;
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
            <h1 style="font-family: 'Roboto', sans-serif;">Đăng ký tài khoản</h1>
        </div>
        <div class="main-content-agile">
            <div class="w3ls-pro">
                <h2 style="font-family: 'Roboto' , sans-serif;">Đăng ký</h2>
            </div>
            <div class="sub-main-w3ls" style="padding: 0 2.5em 2.5em 2.5em;">
				<form action="" method="post">
					<div class="input-group" style="margin-bottom: 24px;">
						<input placeholder="Họ và tên người dùng" name="ten_dang_nhap" type="text" required="" style="width: 83%; margin-top:24px;">
						<span class="icon3"><i class="fa fa-user" aria-hidden="true"></i></span>
					</div>
					<div class="input-group">
						<input placeholder="Email" name="email" type="email" style="width: 83%;" required="">
						<span class="icon1"><i class="fa fa-envelope" aria-hidden="true"></i></span>
					</div>
					<div class="input-group">
						<input placeholder="Mật khẩu" name="password" type="password" style="margin-top:24px; width: 83%;" required="">
						<span class="icon2"><i class="fa fa-unlock-alt" aria-hidden="true"></i></span>
					</div>
					<div class="input-group">
						<input placeholder="Nhập lại mật khẩu" name="repassword" type="password" style="margin-top:24px; width: 83%;" required="">
						<span class="icon2"><i class="fa fa-unlock-alt" aria-hidden="true"></i></span>
					</div>
					<button type="submit" name="register" class="register-btn" style="width: 83%;">Đăng ký</button>
					<p style="text-align:center; margin-top:10px; font-family: 'Roboto' , sans-serif;">
						Đã có tài khoản? <a href="/project/index.php?login">Đăng nhập</a>
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

<!-- File toast dùng chung -->
<script src="js/toast.js"></script>
<?php include_once("view/toastify.php"); ?>