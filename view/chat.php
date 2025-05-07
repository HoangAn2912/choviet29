<?php

include_once("view/header.php");
require_once("controller/cChat.php");
require_once("controller/cUser.php");

$cChat = new cChat();
$cUser = new cUser();

$current_user_id = $_SESSION['user_id'];
$to_user_id = isset($_GET['to']) ? intval($_GET['to']) : 0;
$id_san_pham = isset($_GET['id_san_pham']) ? intval($_GET['id_san_pham']) : 'null';

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
