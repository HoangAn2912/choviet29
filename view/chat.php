<?php

include_once("view/header.php");
require_once("controller/cChat.php");
require_once("controller/cUser.php");
require_once("model/mReview.php");

$mReview = new mReview();
$cChat = new cChat();
$cUser = new cUser();

$current_user_id = $_SESSION['user_id'];
$to_user_id = isset($_GET['to']) ? intval($_GET['to']) : 0;
$id_san_pham = isset($_GET['id_san_pham']) ? intval($_GET['id_san_pham']) : 0;
$conversations = $cChat->getConversationUsers($current_user_id);
$receiver = ($to_user_id) ? $cUser->getUserById($to_user_id) : null;
?>

<?php if ($to_user_id): ?>
<script>
const CURRENT_USER_ID = <?= $current_user_id ?>;
const TO_USER_ID = <?= $to_user_id ?>;
const ID_SAN_PHAM = <?= $id_san_pham ?>;
</script>
<?php endif; ?>

<style>
  .chat-user.active {
    border: 2px solid #ffc107 !important;
    background-color: #fff8e1;
  }
  .chat-bubble {
    max-width: 60%;
    word-wrap: break-word;
  }
  .chat-bubble-received {
    background-color: #f1f3f5;
    color: #212529;
    padding: 10px 15px;
    border-radius: 10px;
    display: inline-block;
    max-width: 60%;
    word-break: break-word;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
  }
  .btn-suggestion {
    background-color: #fff;
    color: #000;
    border: 1px solid #ffc107;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 14px;
    margin: 4px;
    transition: 0.2s;
  }
  .btn-suggestion:hover {
    background-color: #ffe082;
    color: #000;
    border-color: #ffc107;
  }
  .chat-user {
    border: 1px solid #dee2e6;
    background-color: #ffffff;
    transition: background-color 0.2s;
  }
  .chat-user:hover {
    background-color: #f8f9fa;
  }
  .chat-user.active {
    border: 2px solid #ffc107 !important;
    background-color: #fff8e1;
  }
  .chat-wrapper {
    margin-top: -30px; 
  }


</style>

<div class="container-fluid chat-wrapper" style="max-width: 1200px;">
  <div class="row border rounded shadow-sm" style="height: 84vh; overflow: hidden;">
    <!-- Danh sách người dùng -->
    <div class="col-md-4 col-lg-3 bg-light p-3 overflow-auto" style="border-right: 1px solid #dee2e6;">
    <input type="text" class="form-control mb-3" placeholder="Tìm người dùng..." id="searchUserInput">
      <ul class="list-unstyled">
        <?php foreach ($conversations as $user): ?>
        <li class="media p-2 mb-2 rounded chat-user <?= ($user['id'] == $to_user_id ? 'active' : '') ?>" 
            data-id="<?= $user['id'] ?>"
            style="cursor: pointer;" 
            onclick="window.location.href='?tin-nhan&to=<?= $user['id'] ?>'">
          <img src="img/<?= htmlspecialchars($user['anh_dai_dien']) ?>" class="mr-3 rounded-circle" width="50" height="50">
          <div class="media-body">
            <h6 class="mb-0 font-weight-bold"><?= htmlspecialchars($user['ten_dang_nhap']) ?></h6>
            <small class="text-muted"><?= htmlspecialchars($user['thoi_gian']) ?></small><br>
            <small><?= htmlspecialchars($user['tin_cuoi']) ?></small>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- Khung chat -->
    <div class="col-md-8 col-lg-9 d-flex flex-column p-4 bg-white">
      <?php if ($receiver): ?>
      <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
        <div class="d-flex align-items-center">
        <img src="img/<?= htmlspecialchars($receiver['anh_dai_dien']) ?>" class="rounded-circle mr-2" width="40" height="40">
      <strong><?= htmlspecialchars($receiver['ten_dang_nhap']) ?></strong>
    </div>

      </div>

      <div id="chatMessages" class="flex-grow-1 overflow-auto mb-3" style="max-height: 60vh;"></div>

      <form class="d-flex align-items-center" id="formChat" onsubmit="event.preventDefault(); sendMessage(this.noi_dung.value); this.noi_dung.value='';">
        <input name="noi_dung" type="text" class="form-control" placeholder="Nhập tin nhắn..." required>
        <button class="btn btn-warning text-white ml-2"><i class="fa fa-paper-plane"></i></button>
      </form>
      <?php else: ?>
      <div class="text-center text-muted m-auto">
        <img src="img/chat.png" alt="Chọn người" style="max-width: 400px;">
        <p class="mt-3">Chọn người để bắt đầu trò chuyện</p>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Modal đánh giá -->
<div class="modal fade" id="modalDanhGia" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <form action="api/review-api.php?act=themDanhGia" method="post">
      <input type="hidden" name="id_nguoi_danh_gia" value="">
      <input type="hidden" name="id_nguoi_duoc_danh_gia" value="">
      <input type="hidden" name="id_san_pham" value="">

      <div class="modal-header">
        <h5 class="modal-title">Đánh giá người bán</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <label>Số sao</label>
        <select name="so_sao" class="form-control" required>
          <?php for ($i = 5; $i >= 1; $i--): ?>
            <option value="<?= $i ?>"><?= $i ?> sao</option>
          <?php endfor; ?>
        </select>

        <label class="mt-2">Bình luận</label>
        <textarea name="binh_luan" class="form-control" required></textarea>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
      </div>
    </form>
  </div>
</div>



<?php if ($to_user_id): ?>
<script src="js/chat.js"></script>
<script>
  // Gợi ý tin nhắn
  const suggestions = [
    "Sản phẩm này còn không?",
    "Giá có thương lượng không?",
    "Cho tôi xin địa chỉ được không?",
    "Còn bạn."
  ];

  const form = document.querySelector("form.d-flex");
  const input = form.querySelector("input");
  const suggestContainer = document.createElement("div");
  suggestContainer.className = "d-flex flex-wrap gap-2 mt-2";

  suggestions.forEach(msg => {
    const btn = document.createElement("button");
    btn.type = "button";
    btn.className = "btn btn-sm btn-outline-secondary btn-suggestion mr-2 mb-2";
    btn.textContent = msg;
    btn.onclick = () => {
      input.value = msg;
      input.focus();
    };
    suggestContainer.appendChild(btn);
  });

  form.parentNode.insertBefore(suggestContainer, form);
</script>
<?php endif; ?>

<script>
document.getElementById("searchUserInput").addEventListener("input", function () {
  const keyword = this.value.toLowerCase().trim();
  const users = document.querySelectorAll(".chat-user");

  users.forEach(user => {
    const name = user.querySelector("h6").textContent.toLowerCase();
    if (name.includes(keyword)) {
      user.style.display = "flex";
    } else {
      user.style.display = "none";
    }
  });
});
</script>
<script>
// Hàm gọi API lấy tin đầu và thêm nút "Viết đánh giá"
async function checkFirstMessageAndShowButton(from, to, selector) {
  try {
    // Kiểm tra đã đánh giá chưa
    const checkRes = await fetch(`api/check-reviewed.php?from=${from}&to=${to}&id_san_pham=${ID_SAN_PHAM}`);
    if (!checkRes.ok) return;
    const checkData = await checkRes.json();
    if (checkData.reviewed) return; // Đã đánh giá thì không hiển thị nút
console.log('API check-reviewed:', checkData);
    // Lấy tin nhắn đầu tiên
    const res = await fetch(`api/chat-first-message.php?from=${from}&to=${to}`);
    if (!res.ok) return;
    const msg = await res.json();

    const firstTime = new Date(msg.thoi_gian).getTime();
    const now = Date.now();
    const isSender = msg.id_nguoi_gui == from;
    const timePassed = (now - firstTime) > 3600000; // hơn 1 giờ

    if (isSender && timePassed) {
      const html = `<a href="index.php?action=danhgia&from=${msg.id_nguoi_gui}&to=${msg.id_nguoi_nhan}&id_san_pham=${msg.id_san_pham}" 
  class="btn btn-sm btn-outline-warning mt-1">Viết đánh giá</a>`;
      const el = document.querySelector(selector);
      if (el && !el.querySelector('.btn-outline-warning')) {
        el.insertAdjacentHTML("beforeend", html);
      }
    }
  } catch (err) {
    console.error("❌ Lỗi API chat-first-message hoặc check-reviewed:", err);
  }
}

// Hàm hiển thị modal và gán giá trị
function openReviewModal(idNguoiDanhGia, idNguoiDuocDanhGia, idSanPham) {
  const modalEl = document.getElementById('modalDanhGia');
  if (!modalEl) {
    console.error("Không tìm thấy modal DOM");
    return;
  }

  // Gán dữ liệu vào form
  modalEl.querySelector('input[name="id_nguoi_danh_gia"]').value = idNguoiDanhGia;
  modalEl.querySelector('input[name="id_nguoi_duoc_danh_gia"]').value = idNguoiDuocDanhGia;
  modalEl.querySelector('input[name="id_san_pham"]').value = idSanPham;

  // Delay để đảm bảo bootstrap đã load
  setTimeout(() => {
    if (typeof bootstrap === "undefined") {
      console.error("Bootstrap chưa được load!");
      return;
    }
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
  }, 50); // delay nhẹ để đảm bảo script bootstrap được load xong
}


// Chạy sau khi load
document.addEventListener("DOMContentLoaded", () => {
  const fromId = CURRENT_USER_ID;
  document.querySelectorAll(".chat-user").forEach(userEl => {
    const toId = userEl.getAttribute("data-id");
    const selector = `.chat-user[data-id="${toId}"] .media-body`;
    checkFirstMessageAndShowButton(fromId, toId, selector);
  });
});

</script>


