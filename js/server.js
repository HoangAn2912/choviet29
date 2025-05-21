console.log("🟡 Đang chạy đúng file server.js JSON");

const WebSocket = require('ws');
const http = require('http');
const fs = require('fs');
const path = require('path');

const wss = new WebSocket.Server({ port: 3000 });
let clients = {};

wss.on('connection', function connection(ws) {
  ws.on('message', function incoming(message) {
    const data = JSON.parse(message);

    if (data.type === 'register') {
      clients[data.user_id] = ws;
      ws.user_id = data.user_id;
      console.log(`🟢 User ${data.user_id} đã kết nối`);
      return;
    }

    if (data.type === 'message') {
      const { from, to, noi_dung, id_san_pham } = data;
      const timestamp = new Date().toISOString();

      const ids = [from, to].sort((a, b) => a - b);
      const fileName = `chat_${ids[0]}_${ids[1]}.json`;

      // ✅ CHỈ dùng thư mục 'chat/' ngang cấp với 'api'
      const chatFolderPath = path.join(__dirname, "../chat");
      const filePath = path.join(chatFolderPath, fileName);

      // ✅ Tạo thư mục chat nếu chưa có
      if (!fs.existsSync(chatFolderPath)) {
        fs.mkdirSync(chatFolderPath, { recursive: true });
      }

      // ✅ Nếu file chưa tồn tại thì tạo file trống và lưu DB
      if (!fs.existsSync(filePath)) {
        try {
          fs.writeFileSync(filePath, "[]");
          console.log("📁 Đã tạo file mới:", filePath);

          const postFileName = JSON.stringify({ from, to, file_name: fileName });
          const req2 = http.request({
            hostname: 'localhost',
            port: 8080,
            path: '/project/api/chat-save-filename.php',
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Content-Length': Buffer.byteLength(postFileName)
            }
          }, res => {
            console.log('📁 Đã lưu tên file vào DB:', fileName);
          });
          req2.on('error', error => console.error("❌ Lỗi lưu tên file:", error));
          req2.write(postFileName);
          req2.end();

        } catch (err) {
          console.error("❌ Lỗi tạo file:", err);
        }
      }

      // ✅ Đọc và cập nhật file JSON
      let messages = [];
      try {
        const fileContent = fs.readFileSync(filePath, 'utf-8');
        messages = JSON.parse(fileContent);
      } catch (err) {
        console.error("❌ Lỗi đọc file JSON:", err);
      }

      messages.push({ from, to, noi_dung, timestamp });

      fs.writeFile(filePath, JSON.stringify(messages, null, 2), err => {
        if (err) console.error("❌ Lỗi ghi file JSON:", err);
        else console.log("✅ Đã lưu tin nhắn vào file:", fileName);
      });

      // ✅ Gửi tin nhắn về 2 phía
      const socketMessage = JSON.stringify({ type: 'message', from, to, noi_dung, timestamp });
      if (clients[to]) clients[to].send(socketMessage);
      if (clients[from]) clients[from].send(socketMessage);

      // ✅ Gọi API lưu vào DB nếu cần
      const postData = JSON.stringify({ from, to, content: noi_dung, id_san_pham: id_san_pham || null });
      const req = http.request({
        hostname: 'localhost',
        port: 8080,
        path: '/project/api/chat-api.php',
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Content-Length': Buffer.byteLength(postData)
        }
      }, res => {
        console.log('📩 Gửi API chat-api.php:', res.statusCode);
        res.on('data', chunk => console.log('📦 Nội dung:', chunk.toString()));
      });
      req.on('error', error => console.error("❌ Lỗi gọi API PHP:", error));
      req.write(postData);
      req.end();
    }
  });

  ws.on('close', () => {
    if (ws.user_id && clients[ws.user_id]) {
      delete clients[ws.user_id];
      console.log(`🔴 User ${ws.user_id} đã ngắt kết nối`);
    }
  });
});
