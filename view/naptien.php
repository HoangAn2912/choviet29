<?php
include_once("controller/cTopUp.php");
$userId = $_SESSION['user_id'] ?? 0;

$cTopUp = new cTopUp();
$cTopUp->xuLyNopTien($userId); // Xử lý nếu có POST

$lichsu = $cTopUp->getLichSu($userId); // Lấy lịch sử cho view
?>
<!-- ...phần HTML giữ nguyên, chỉ dùng $lichsu để hiển thị bảng... -->

<?php include_once("view/header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <title>Chợ Việt - Nơi trao đổi hàng hóa</title>
    <link rel="icon" href="img/choviet-favicon.ico" type="icon">

    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <!-- <link href="img/favicon.ico" rel="icon"> -->

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">  

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/profile.css" rel="stylesheet">
    <link href="css/managePost.css" rel="stylesheet">
</head>
</html>

<div class="container my-4">
  <div class="row">
    <!-- Cột trái: Thông tin ngân hàng và QR -->
    <div class="col-md-7 mb-4 d-flex">
      <div class="card shadow-sm p-4 w-100">
        <h5 class="text-uppercase font-weight-bold text-primary mb-3">Thông tin nạp tiền</h5>
        <ul class="list-unstyled mb-3">
          <li><strong>Ngân hàng:</strong> MB Bank</li>
          <li><strong>Số tài khoản:</strong> 6369 36979 9999</li>
          <li><strong>Chủ tài khoản:</strong> Nguyễn Phúc Hoàng An</li>
        </ul>
        <div class="text-center mt-auto">
          <img src="img/qr_nap_tien.jpg" alt="QR Code" class="img-fluid border rounded" style="max-width: 300px;">
          <p class="mt-2 font-italic text-muted mb-0">Quét mã để nạp nhanh qua app ngân hàng</p>
        </div>
      </div>
    </div>

    <!-- Cột phải: Hướng dẫn nạp -->
    <div class="col-md-5 mb-4 d-flex">
      <div class="card shadow-sm p-4 w-100">
        <h5 class="text-danger font-weight-bold mb-3">📌 HƯỚNG DẪN NẠP TIỀN</h5>
        <ul class="mb-3 pl-3">
          <li>Sao chép <strong class="text-dark">chính xác nội dung chuyển khoản</strong> hoặc <strong>quét mã QR</strong> bên trái.</li>
          <li><strong>Số tiền nạp tối thiểu:</strong> <span class="text-danger">20.000 đ</span></li>
          <li>Nội dung chuyển khoản phải <strong>ghi đúng như hướng dẫn dưới đây</strong> để kế toán phụ trách kiểm tra và cộng tiền. Nếu sai chúng tôi không hồi tiền về cho bạn</li>
          <li><strong>Cú pháp:</strong> Mã chuyển khoản - Tên người dùng - Số tiền vừa nạp. (<i>Ví dụ: 1111 - Nguyễn Phúc Hoàng An - 20.000 đ.</i>)</li>
        </ul>

        <p class="mb-1 font-weight-bold text-muted">⚠️ Lưu ý quan trọng:</p>
        <ul class="pl-3 text-muted small">
          <li><strong class="text-danger">KHÔNG</strong> chuyển khoản qua MB Bank từ <b>23H đến 05H sáng</b>.</li>
          <li>Trong khung giờ này hệ thống sẽ xử lý vào <b>sáng hôm sau</b>.</li>
          <li>Mã chuyển khoản trong phần <b>trang cá nhân</b>.</li>
          <li>Tiền sẽ về trong <b>5-10 phút</b>.</li>
        </ul>
        <hr>
        <p class="mb-0"><span class="text-warning">💡 Gợi ý:</span> Ưu tiên sử dụng <b>MB Bank, TPBank, Techcombank</b> để nhận tiền nhanh.</p>
      </div>
    </div>
  </div>

  <!-- Form nạp tiền thủ công -->
<div class="card mb-4">
  <div class="card-header font-weight-bold">Nạp tiền thủ công (chuyển khoản)</div>
  <div class="card-body">
    <form action="" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="noi_dung_ck">Nội dung chuyển khoản <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="noi_dung_ck" name="noi_dung_ck" required>
      </div>
      <div class="form-group">
        <label for="hinh_anh_ck">Ảnh chuyển khoản <span class="text-danger">*</span></label>
        <input type="file" class="form-control-file" id="hinh_anh_ck" name="hinh_anh_ck" accept=".jpg,.jpeg,.png" required>
      </div>
      <button type="submit" name="submit_ck" class="btn btn-success">Gửi yêu cầu nạp tiền</button>
    </form>
  </div>
</div>

<?php
// Hàm lấy màu trạng thái như managePost.php
function getBadgeColorCK($status) {
  $map = [
    'Đang chờ duyệt' => 'warning',
    'Đã duyệt' => 'success',
    'Từ chối' => 'danger',
  ];
  return $map[$status] ?? 'secondary';
}

$lichsu = (new mTopUp())->getLichSuChuyenKhoan($userId);
?>

<div class="card">
  <div class="card-header font-weight-bold">Lịch sử chuyển khoản</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-bordered mb-0">
        <thead class="thead-light">
          <tr>
            <th>Thời gian</th>
            <th>Nội dung CK</th>
            <th>Ảnh CK</th>
            <th>Trạng thái</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($lichsu as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['ngay_tao']) ?></td>
              <td><?= htmlspecialchars($row['noi_dung_ck']) ?></td>
              <td>
                <?php if ($row['hinh_anh_ck']): ?>
                  <img src="img/<?= htmlspecialchars($row['hinh_anh_ck']) ?>" width="60" style="cursor:pointer" onclick="showImageModal('img/<?= htmlspecialchars($row['hinh_anh_ck']) ?>')">
                <?php endif; ?>
              </td>
              <td>
                <span class="badge badge-<?= getBadgeColorCK($row['trang_thai_ck']) ?>">
                  <?= htmlspecialchars($row['trang_thai_ck']) ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($lichsu)): ?>
            <tr><td colspan="4" class="text-center text-muted">Chưa có giao dịch chuyển khoản nào.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</div>

<!-- Modal phóng to ảnh -->
<div id="imgModal" style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.7); align-items:center; justify-content:center;">
  <span onclick="closeImgModal()" style="position:absolute;top:20px;right:40px;font-size:40px;color:#fff;cursor:pointer;z-index:2010;">&times;</span>
  <img id="imgModalSrc" src="" style="max-width:90vw;max-height:90vh;box-shadow:0 0 20px #000;">
</div>
<script>
function showImageModal(src) {
  document.getElementById('imgModalSrc').src = src;
  document.getElementById('imgModal').style.display = 'flex';
}
function closeImgModal() {
  document.getElementById('imgModal').style.display = 'none';
  document.getElementById('imgModalSrc').src = '';
}
</script>

<?php include_once("view/footer.php"); ?>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<!-- Toastify JS -->
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<!-- Hàm showToast -->
<script src="js/toast.js"></script>
<!-- Gọi toast nếu có -->
<?php include_once("toastify.php"); ?>
