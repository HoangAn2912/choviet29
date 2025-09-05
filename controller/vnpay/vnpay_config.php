<?php
require_once '../../helpers/url_helper.php';

// Cấu hình VNPay
$vnp_TmnCode    = "AFC6ZM4W"; // Mã website
$vnp_HashSecret = "599LP8UPX1QUYKEJ1CYS7R2MEUF73A3Q"; // Chuỗi bí mật
$vnp_Url        = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl  = getBaseUrl() . "/controller/vnpay/vnpay_return.php";

// Cấu hình khác
$vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
$apiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";

// Múi giờ
date_default_timezone_set('Asia/Ho_Chi_Minh');

function getReturnUrl() {
    // Sử dụng helper function để lấy URL động
    return getBaseUrl() . "/controller/vnpay/vnpay_return.php";
}
?>
