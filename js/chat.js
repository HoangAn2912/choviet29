const socket = new WebSocket("ws://localhost:3000");

let chatBox = null;
const shownMessages = new Set();

function renderMessage(msg, isFromSocket = false) {
  const content = msg.content || msg.noi_dung;
  const timestamp = msg.timestamp || "";
  const messageKey = `${msg.from}_${content}_${timestamp}`;

  if (shownMessages.has(messageKey)) return;
  shownMessages.add(messageKey);

  const isMe = msg.from == CURRENT_USER_ID;
  const html = `<div class="${isMe ? 'text-right' : 'text-left'} mb-2">
    <span class="${isMe ? 'bg-warning text-white' : 'chat-bubble-received'} px-3 py-2 rounded d-inline-block">
      ${content}
    </span>
  </div>`;

  if (chatBox) {
    chatBox.insertAdjacentHTML('beforeend', html);
    chatBox.scrollTop = chatBox.scrollHeight;
  }
}

// Gửi tin nhắn
function sendMessage(noiDung) {
  if (noiDung.trim() && socket.readyState === WebSocket.OPEN) {
    socket.send(JSON.stringify({
      type: 'message',
      from: CURRENT_USER_ID,
      to: TO_USER_ID,
      noi_dung: noiDung,
      id_san_pham: ID_SAN_PHAM
    }));
  }
}

// Khi socket mở
socket.addEventListener("open", () => {
  socket.send(JSON.stringify({
    type: 'register',
    user_id: CURRENT_USER_ID
  }));
});

// Khi nhận tin nhắn mới từ socket
socket.addEventListener("message", (event) => {
  const msg = JSON.parse(event.data);
  if (msg.type === 'message') {
    renderMessage(msg, true);
  }
});

// Load tin nhắn cũ từ file
window.addEventListener("DOMContentLoaded", () => {
  chatBox = document.getElementById('chatMessages');
  fetch(`/project/api/chat-file-api.php?from=${CURRENT_USER_ID}&to=${TO_USER_ID}`)
    .then(res => res.json())
    .then(messages => {
      messages.forEach(msg => renderMessage(msg, false));
    })
    .catch(err => console.error("❌ Lỗi khi đọc file JSON:", err));
});
