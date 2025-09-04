<?php
// Cấu hình VNPay
$vnp_TmnCode    = "AFC6ZM4W"; // Mã website
$vnp_HashSecret = "599LP8UPX1QUYKEJ1CYS7R2MEUF73A3Q"; // Chuỗi bí mật
$vnp_Url        = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl  = "https://b53989472b92.ngrok-free.app/choviet29/controller/vnpay/vnpay_return.php";

// Cấu hình khác
$vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
$apiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";

// Múi giờ
date_default_timezone_set('Asia/Ho_Chi_Minh');

function getReturnUrl() {
    // Kiểm tra xem có đang chạy trên localhost không
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    
    if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
        return "http://localhost:81/choviet29/controller/vnpay/vnpay_return.php";
    } else {
        // Nếu không phải localhost, sử dụng domain hiện tại
        return $protocol . "://" . $host . "/choviet29/controller/vnpay/vnpay_return.php";
    }
}
?>
