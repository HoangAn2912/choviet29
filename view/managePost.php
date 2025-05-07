<?php
include_once("controller/cPost.php");
include_once("model/mPost.php");

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Bạn cần đăng nhập để xem tin đăng của mình.'); window.location.href='index.php?login';</script>";
    exit;
}

$postCtrl = new cPost();
$userId = $_SESSION['user_id'] ?? 0;
$posts = $postCtrl->layDanhSachTinNguoiDung($userId);
$count = $postCtrl->demSoLuongTheoTrangThai($userId);
$user = $postCtrl->layThongTinNguoiDung($userId);

$tin = null;
if (isset($_GET['sua'])) {
    $tinId = intval($_GET['sua']);
    $tin = $postCtrl->laySanPhamTheoId($tinId);
    if (!$tin || $tin['id_nguoi_dung'] != $userId) {
        echo "<script>alert('Không tìm thấy bài viết hoặc bạn không có quyền chỉnh sửa.'); window.location.href='index.php?quan-ly-tin';</script>";
        exit;
    }
    echo "<script>document.addEventListener('DOMContentLoaded', function() {
      const modal = document.getElementById('suaTinModal');
      if(modal){
        modal.style.display = 'block';
        document.getElementById('form-sua-tin').style.display = 'block';
        document.getElementById('modal-subtitle').innerText = 'Sửa tin';
      }
  });</script>";
  
}

function getBadgeColor($status) {
  switch($status) {
      case 'dang_ban': return 'success';
      case 'da_ban': return 'secondary';
      case 'cho_duyet': return 'warning';
      case 'tu_choi': return 'danger';
      case 'da_an': return 'dark';
  }
}

function getNoProductText($status) {
  switch($status) {
      case 'dang_ban': return 'Chưa có sản phẩm đang bán.';
      case 'da_ban': return 'Chưa có sản phẩm đã bán.';
      case 'cho_duyet': return 'Chưa có sản phẩm chờ duyệt.';
      case 'tu_choi': return 'Chưa có sản phẩm bị từ chối.';
      case 'da_an': return 'Chưa có sản phẩm đã ẩn.';
      default: return 'Chưa có sản phẩm.';
  }
}
?>

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


<div class="container my-2">
  <!-- Tabs -->
  <div class="d-flex justify-content-between align-items-center mb-2" id="profileUser">
  <div class="d-flex align-items-center">
    <img src="img/<?= htmlspecialchars($user['anh_dai_dien'] ?? 'default-avatar.png') ?>" class="rounded-circle mr-3" alt="Avatar" width="50" height="50" style="object-fit: cover;">
    <div>
      <div class="font-weight-bold"><?= htmlspecialchars($user['ten_dang_nhap'] ?? 'Tên đăng nhập') ?></div>
    </div>
  </div>
  <div class="d-flex align-items-center">
    <i class="fas fa-coins text-warning mr-2"></i>
    <span class="font-weight-bold text-dark"><?= number_format($user['so_du'] ?? 0, 0, ',', '.') ?> đ</span>
    <button onclick="window.location.href='index.php?nap-tien'" class="btn btn-success btn-sm ml-3 rounded-circle" title="Nạp thêm" style="width: 30px; height: 30px; padding: 0;">+</button>

  </div>
  
</div>
<div class="w-100">
    <hr class="m-0" style="border-top: 2px solid #ddd;">
  </div>

  <ul class="nav nav-tabs mb-4" id="tabTinDang">
    
    <li class="nav-item"><a class="nav-link active" data-status="dang_ban" href="#">Đang bán (<?= $count['dang_ban'] ?? 0 ?>)</a></li>
    <li class="nav-item"><a class="nav-link" data-status="da_ban" href="#">Đã bán (<?= $count['da_ban'] ?? 0 ?>)</a></li>
    <li class="nav-item"><a class="nav-link" data-status="cho_duyet" href="#">Chờ duyệt (<?= $count['cho_duyet'] ?? 0 ?>)</a></li>
    <li class="nav-item"><a class="nav-link" data-status="tu_choi" href="#">Từ chối (<?= $count['tu_choi'] ?? 0 ?>)</a></li>
    <li class="nav-item"><a class="nav-link" data-status="da_an" href="#">Đã ẩn (<?= $count['da_an'] ?? 0 ?>)</a></li>
  </ul>

  <!-- Danh sách tin -->
  <div class="row" id="tinDangList">
    <?php 
    $statusList = ['dang_ban', 'da_ban', 'cho_duyet', 'tu_choi', 'da_an'];
    foreach ($statusList as $statusTab):
      $hasProduct = false;
      foreach ($posts as $post):
        if ($post['trang_thai_ban'] == 'da_ban') $status = 'da_ban';
        elseif ($post['trang_thai_ban'] == 'da_an') $status = 'da_an';
        elseif ($post['trang_thai'] == 'cho_duyet') $status = 'cho_duyet';
        elseif ($post['trang_thai'] == 'tu_choi') $status = 'tu_choi';
        else $status = 'dang_ban';

        if ($status == $statusTab):
          $hasProduct = true;
    ?>

<div class="col-12 mb-3 product-item" data-status="<?= $statusTab ?>">
  <div class="card shadow-sm product-card">
    <div class="row no-gutters align-items-stretch" style="min-height: 220px;">
      <div class="col-md-3">
        <img src="img/<?= explode(',', $post['hinh_anh'])[0] ?>" class="card-img-top product-image">
      </div>
      <div class="col-md-9 d-flex flex-column justify-content-center">
        <div class="card-body pb-2">
          <h5 class="card-title mb-2"><?= htmlspecialchars($post['tieu_de']) ?></h5>
          <p class="text-danger font-weight-bold mb-1"><?= number_format($post['gia']) ?> đ</p>
          <p class="card-text small text-muted mb-1">
            <i class="fas fa-map-marker-alt mr-1" style="color:rgb(49, 49, 49);"></i> 
            <?= htmlspecialchars($user['dia_chi'] ?? 'Chưa cập nhật') ?></p>
          <p class="card-text small text-muted mb-1"><i class="fas fa-clock mr-1" style="color: #6c757d;"></i> Cập nhật: <?= $post['thoi_gian_cu_the'] ?></p>
          <p class="card-text small text-muted"><i class="fas fa-info-circle mr-1" style="color: #007bff;"></i>Trạng thái: <span class="badge badge-<?= getBadgeColor($status) ?>"><?= str_replace('_', ' ', $status) ?></span></p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2 pd">
  <?php if ($status == 'dang_ban'): ?>
    <div class="btn-group">
      <button type="button" class="btn btn-action btn-sm dropdown-toggle color-text" data-toggle="dropdown">
        <i class="fas fa-sync-alt mr-1"></i> Cập nhật trạng thái
      </button>
      <div class="dropdown-menu">
        <a class="dropdown-item d-flex align-items-center" href="#" onclick="xacNhanCapNhat(<?= $post['id'] ?>, 'da_ban')">
          <i class="fas fa-check-circle mr-2" style="color: #28a745;"></i> Đã bán
        </a>
        <a class="dropdown-item d-flex align-items-center" href="#" onclick="xacNhanCapNhat(<?= $post['id'] ?>, 'da_an')">
          <i class="fas fa-eye-slash mr-2" style="color: #6c757d;"></i> Đã ẩn
        </a>
      </div>
    </div>

    <button class="btn btn-edit btn-sm" onclick="window.location.href='index.php?quan-ly-tin&sua=<?= $post['id'] ?>'">
      <i class="fas fa-edit mr-1"></i> Sửa tin
    </button>

    <button class="btn btn-push btn-sm"
        onclick="xacNhanDayTin(<?= $post['id'] ?>)">
  <i class="fas fa-bullhorn mr-1"></i> Đẩy tin
</button>

  <?php endif; ?>
</div>


      </div>
    </div>
  </div>
</div>

    <?php
        endif; // end if status match
      endforeach; // end foreach posts

      if (!$hasProduct): 
    ?>
      <div class="col-12 no-product text-center text-muted my-4" data-status="<?= $statusTab ?>">
        <?= getNoProductText($statusTab) ?>
      </div>
    <?php endif; ?>
    <?php endforeach; ?>
  </div>
</div>

<?php include_once("view/footer.php"); ?>

<!-- Modal Sửa Tin -->
<div id="suaTinModal" class="modal" style="display: <?= isset($tin) ? 'block' : 'none' ?>; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto; z-index: 1050;">
  <div class="modal-content p-4 rounded" style="background: white; width: 600px; margin: 80px auto;">
    <h4 class="font-weight-bold m-0 text-center" style="color: #333;">Sửa tin</h4>
    <button onclick="document.getElementById('suaTinModal').style.display='none'"
          class="btn btn-link p-0"
          style="position: absolute; top: 10px; right: 10px; font-size: 22px; color: #555;">
          <i class="fas fa-times"></i>
        </button>
    <div style="position: relative; padding: 1px 12px 12px 12px; border-bottom: 1px solid #dee2e6;">
        
      </div>

    <?php if (isset($tin)): ?>   
    <form method="POST" action="index.php?action=suaTin&id=<?= $tin['id'] ?>" enctype="multipart/form-data">
      <input type="hidden" name="id_loai_san_pham" value="<?= $tin['id_loai_san_pham'] ?>">
      <h5 id="modal-subtitle" class="font-weight-bold mb-3" style="color: #555;"></h5>
      <div class="form-group">
        <label class="font-weight-bold">Tiêu đề bài đăng <span class="text-danger">*</span></label>
        <input type="text" name="tieu_de" class="form-control" value="<?= htmlspecialchars($tin['tieu_de']) ?>" required>
      </div>

      <div class="form-group">
        <label class="font-weight-bold">Giá bán (đ) <span class="text-danger">*</span></label>
        <input type="number" name="gia" class="form-control" value="<?= htmlspecialchars($tin['gia']) ?>" required>
      </div>

      <div class="form-group">
        <label class="font-weight-bold">Mô tả chi tiết <span class="text-danger">*</span></label>
        <textarea name="mo_ta" class="form-control" rows="5"><?= htmlspecialchars($tin['mo_ta']) ?></textarea>
      </div>

      <div class="form-group">
        <label class="font-weight-bold">Hình ảnh sản phẩm hiện tại</label><br>
        <?php foreach (explode(',', $tin['hinh_anh']) as $anh): ?>
          <img src="img/<?= $anh ?>" width="80" style="margin: 5px;">
        <?php endforeach; ?>
      </div>

      <div class="form-group">
        <label>Chọn từ 2 đến 6 ảnh mới nếu muốn thay đổi (định dạng .jpg, .png).</label>
        <input type="file" name="hinh_anh[]" class="form-control-file" multiple accept=".jpg,.png,.jpeg">
      </div>

      <button type="submit" class="btn btn-warning w-100 text-white font-weight-bold">Cập nhật</button>
    </form>
    <?php endif; ?>
  </div>
</div>

<?php include_once("js/managePost.php"); ?>
<script>
function xacNhanDayTin(id) {
    if (confirm("Bạn sẽ mất phí 11.000đ để đẩy tin này đến với nhiều người xem mới hơn.\nBạn có chắc chắn muốn tiếp tục?")) {
        window.location.href = 'index.php?quan-ly-tin&daytin=' + id;
    }
}
</script>
