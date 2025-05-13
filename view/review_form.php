<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<?php
require_once("controller/cUser.php");
require_once("model/mReview.php");

$id_nguoi_danh_gia = $_GET['from'] ?? 0;
$id_nguoi_duoc_danh_gia = $_GET['to'] ?? 0;
$id_san_pham = $_GET['id_san_pham'] ?? 0;

$mReview = new mReview();
$cUser = new cUser();

$daDanhGia = $mReview->daDanhGia($id_nguoi_danh_gia, $id_nguoi_duoc_danh_gia, $id_san_pham);
$receiver = $cUser->getUserById($id_nguoi_duoc_danh_gia);
?>

<?php include("view/header.php"); ?>
<div class="container py-4">
  <h4 class="mb-4">Đánh giá người bán</h4>
  <?php if (!$daDanhGia): ?>
  <form action="api/review-api.php?act=themDanhGia" method="post">
    <input type="hidden" name="id_nguoi_danh_gia" value="<?= $id_nguoi_danh_gia ?>">
    <input type="hidden" name="id_nguoi_duoc_danh_gia" value="<?= $id_nguoi_duoc_danh_gia ?>">
    <input type="hidden" name="id_san_pham" value="<?= $id_san_pham ?>">

    <div class="mb-3">
      <label class="form-label">Người được đánh giá:</label>
      <div class="d-flex align-items-center">
        <img src="img/<?= $receiver['anh_dai_dien'] ?>" alt="avatar" width="50" class="rounded-circle me-2">
        <strong><?= htmlspecialchars($receiver['ten_dang_nhap']) ?></strong>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Số sao</label>
      <select name="so_sao" class="form-control" required>
        <?php for ($i = 5; $i >= 1; $i--): ?>
          <option value="<?= $i ?>"><?= $i ?> sao</option>
        <?php endfor; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Bình luận</label>
      <textarea name="binh_luan" class="form-control" rows="4" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
  </form>
  <?php else: ?>
    <div class="alert alert-info mt-3">Bạn đã đánh giá người này rồi.</div>
  <?php endif; ?>
</div>
<?php include("view/footer.php"); ?>