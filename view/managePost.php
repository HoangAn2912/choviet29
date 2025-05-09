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
  $map = [
    'Đang bán' => 'success',
    'Đã bán' => 'secondary',
    'Chờ duyệt' => 'warning',
    'Từ chối' => 'danger',
    'Đã ẩn' => 'dark',
  ];
  return $map[$status] ?? 'secondary';
}

function getNoProductText($status) {
  $map = [
    'Đang bán' => 'Chưa có sản phẩm đang bán.',
    'Đã bán' => 'Chưa có sản phẩm đã bán.',
    'Chờ duyệt' => 'Chưa có sản phẩm chờ duyệt.',
    'Từ chối' => 'Chưa có sản phẩm bị từ chối.',
    'Đã ẩn' => 'Chưa có sản phẩm đã ẩn.',
  ];
  return $map[$status] ?? 'Chưa có sản phẩm.';
}
?>

<?php include_once("view/header.php"); ?>

<div class="container my-2">
  <div class="d-flex justify-content-between align-items-center mb-2" id="profileUser">
    <div class="d-flex align-items-center">
      <img src="img/<?= htmlspecialchars($user['anh_dai_dien'] ?? 'default-avatar.png') ?>" class="rounded-circle mr-3" alt="Avatar" width="50" height="50" style="object-fit: cover;">
      <div><div class="font-weight-bold"><?= htmlspecialchars($user['ten_dang_nhap'] ?? 'Tên đăng nhập') ?></div></div>
    </div>
    <div class="d-flex align-items-center">
      <i class="fas fa-coins text-warning mr-2"></i>
      <span class="font-weight-bold text-dark"><?= number_format($user['so_du'] ?? 0, 0, ',', '.') ?> đ</span>
      <button onclick="window.location.href='index.php?nap-tien'" class="btn btn-success btn-sm ml-3 rounded-circle" title="Nạp thêm" style="width: 30px; height: 30px; padding: 0;">+</button>
    </div>
  </div>

  <hr class="m-0" style="border-top: 2px solid #ddd;">

  <ul class="nav nav-tabs mb-4" id="tabTinDang">
    <?php $statusList = ['Đang bán', 'Đã bán', 'Chờ duyệt', 'Từ chối', 'Đã ẩn']; ?>
    <?php foreach ($statusList as $tab): ?>
      <li class="nav-item">
        <a class="nav-link<?= $tab === 'Đang bán' ? ' active' : '' ?>" data-status="<?= $tab ?>" href="#">
          <?= $tab ?> (<?= $count[$tab] ?? 0 ?>)
        </a>
      </li>
    <?php endforeach; ?>
  </ul>

  <div class="row" id="tinDangList">
    <?php foreach ($statusList as $statusTab): ?>
      <?php $hasProduct = false; ?>
      <?php foreach ($posts as $post): ?>
        <?php $status = $post['trang_thai_ban'] ?? $post['trang_thai']; ?>
        <?php if ($status === $statusTab): ?>
          <?php $hasProduct = true; ?>
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
                      <?= htmlspecialchars($user['dia_chi'] ?? 'Chưa cập nhật') ?>
                    </p>
                    <p class="card-text small text-muted mb-1">
                      <i class="fas fa-clock mr-1" style="color: #6c757d;"></i>
                      Cập nhật: <?= $post['thoi_gian_cu_the'] ?>
                    </p>
                    <p class="card-text small text-muted">
                      <i class="fas fa-info-circle mr-1" style="color: #007bff;"></i>Trạng thái:
                      <span class="badge badge-<?= getBadgeColor($status) ?>"><?= $status ?></span>
                    </p>
                  </div>
                  <?php if ($status === 'Đang bán'): ?>
                    <div class="d-flex flex-wrap align-items-center gap-2 pd">
                      <div class="btn-group">
                        <button type="button" class="btn btn-action btn-sm dropdown-toggle color-text" data-toggle="dropdown">
                          <i class="fas fa-sync-alt mr-1"></i> Cập nhật trạng thái
                        </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item d-flex align-items-center" href="#" onclick="xacNhanCapNhat(<?= $post['id'] ?>, 'Đã bán')">
                            <i class="fas fa-check-circle mr-2" style="color: #28a745;"></i> Đã bán
                          </a>
                          <a class="dropdown-item d-flex align-items-center" href="#" onclick="xacNhanCapNhat(<?= $post['id'] ?>, 'Đã ẩn')">
                            <i class="fas fa-eye-slash mr-2" style="color: #6c757d;"></i> Đã ẩn
                          </a>
                        </div>
                      </div>
                      <button class="btn btn-edit btn-sm" onclick="window.location.href='index.php?quan-ly-tin&sua=<?= $post['id'] ?>'">
                        <i class="fas fa-edit mr-1"></i> Sửa tin
                      </button>
                      <button class="btn btn-push btn-sm" onclick="xacNhanDayTin(<?= $post['id'] ?>)">
                        <i class="fas fa-bullhorn mr-1"></i> Đẩy tin
                      </button>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>

      <?php if (!$hasProduct): ?>
        <div class="col-12 no-product text-center text-muted my-4" data-status="<?= $statusTab ?>">
          <?= getNoProductText($statusTab) ?>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>
</div>

<?php include_once("view/footer.php"); ?>

<script>
function xacNhanDayTin(id) {
    if (confirm("Bạn sẽ mất phí 11.000đ để đẩy tin này đến với nhiều người xem mới hơn.\nBạn có chắc chắn muốn tiếp tục?")) {
        window.location.href = 'index.php?quan-ly-tin&daytin=' + id;
    }
}
</script>
