<?php
require_once 'mConnect.php';

class mChat extends Connect {
    public function saveMessage($from, $to, $content) {
        $conn = $this->connect();
        $stmt = $conn->prepare("INSERT INTO tin_nhan (id_nguoi_gui, id_nguoi_nhan, noi_dung) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $from, $to, $content);
        return $stmt->execute();
    }    

    public function getMessages($user1, $user2) {
        $conn = $this->connect();
        
        // Đảm bảo chỉ lấy các tin nhắn từ 2 người, không bị hoán vị lặp
        $stmt = $conn->prepare("
            SELECT * FROM tin_nhan 
            WHERE (id_nguoi_gui = ? AND id_nguoi_nhan = ?) 
               OR (id_nguoi_gui = ? AND id_nguoi_nhan = ?)
            ORDER BY thoi_gian ASC
        ");
        
        // 🛠 Gắn đúng giá trị
        $stmt->bind_param("iiii", $user1, $user2, $user2, $user1);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    

    public function getConversationUsers($currentUserId) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT u.id, u.ten_dang_nhap, u.anh_dai_dien,
                (SELECT noi_dung FROM tin_nhan 
                 WHERE (id_nguoi_gui = u.id AND id_nguoi_nhan = ?) OR (id_nguoi_gui = ? AND id_nguoi_nhan = u.id)
                 ORDER BY thoi_gian DESC LIMIT 1) as tin_cuoi,
                (SELECT DATE_FORMAT(thoi_gian, '%H:%i %d/%m') FROM tin_nhan 
                 WHERE (id_nguoi_gui = u.id AND id_nguoi_nhan = ?) OR (id_nguoi_gui = ? AND id_nguoi_nhan = u.id)
                 ORDER BY thoi_gian DESC LIMIT 1) as thoi_gian
            FROM nguoi_dung u
            WHERE u.id != ?
              AND EXISTS (
                  SELECT 1 FROM tin_nhan t 
                  WHERE (t.id_nguoi_gui = ? AND t.id_nguoi_nhan = u.id) 
                     OR (t.id_nguoi_gui = u.id AND t.id_nguoi_nhan = ?)
              )
            ORDER BY thoi_gian DESC
        ");
        
        $stmt->bind_param("iiiiiii", 
            $currentUserId, $currentUserId, 
            $currentUserId, $currentUserId, 
            $currentUserId, $currentUserId, $currentUserId
        );
        
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function sendMessage($from, $to, $content, $idSanPham = null) {
        $conn = $this->connect();
        $min = min($from, $to);
        $max = max($from, $to);
        $fileName = "chat_" . $min . "_" . $max . ".json";
    
        // ⚠️ Kiểm tra nếu chưa có dòng nào thì mới insert tên file vào DB
        $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM tin_nhan WHERE 
            (id_nguoi_gui = ? AND id_nguoi_nhan = ?) OR 
            (id_nguoi_gui = ? AND id_nguoi_nhan = ?)");
        $stmtCheck->bind_param("iiii", $from, $to, $to, $from);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();
    
        if ($count == 0) {
            // ✅ Chỉ lưu 1 dòng duy nhất để ghi nhớ tên file
            $stmt = $conn->prepare("INSERT INTO tin_nhan (id_nguoi_gui, id_nguoi_nhan, id_san_pham, noi_dung, thoi_gian) 
                                    VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("iiis", $from, $to, $idSanPham, $fileName);
            return $stmt->execute();
        }
    
        return true; // Nếu đã có rồi thì không cần lưu thêm nữa
    }

    public function readChatFile($from, $to) {
        $ids = [$from, $to];
        sort($ids);
        $filePath = __DIR__ . "/../../chat/chat_{$ids[0]}_{$ids[1]}.json";
    
        if (!file_exists($filePath)) return [];
    
        $messages = json_decode(file_get_contents($filePath), true);
        if (!is_array($messages)) return [];
    
        return $messages;
    }
    
    public function getChatFileName($user1, $user2) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT noi_dung FROM tin_nhan 
            WHERE ((id_nguoi_gui = ? AND id_nguoi_nhan = ?) 
                OR (id_nguoi_gui = ? AND id_nguoi_nhan = ?))
                ORDER BY thoi_gian ASC LIMIT 1");
        $stmt->bind_param("iiii", $user1, $user2, $user2, $user1);
        $stmt->execute();
        $stmt->bind_result($fileName);
        $stmt->fetch();
        $stmt->close();
        return $fileName;
    }

    public function saveFileName($from, $to, $fileName) {
        $conn = $this->connect();
    
        // Kiểm tra xem đã tồn tại đoạn chat chưa
        $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM tin_nhan WHERE 
            (id_nguoi_gui = ? AND id_nguoi_nhan = ?) 
            OR (id_nguoi_gui = ? AND id_nguoi_nhan = ?)");
        $stmtCheck->bind_param("iiii", $from, $to, $to, $from);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();
    
        if ($count == 0) {
            $stmt = $conn->prepare("INSERT INTO tin_nhan (id_nguoi_gui, id_nguoi_nhan, noi_dung, thoi_gian) 
                                    VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iis", $from, $to, $fileName);
            return $stmt->execute();
        }
    
        return true; // Đã tồn tại thì không cần lưu thêm
    }

    public function getLastMessageFromFile($user1_id, $user2_id) {
        $file1 = "chat/chat_{$user1_id}_{$user2_id}.json";
        $file2 = "chat/chat_{$user2_id}_{$user1_id}.json";
        $file = file_exists($file1) ? $file1 : (file_exists($file2) ? $file2 : null);
    
        if (!$file) return ['noi_dung' => '', 'thoi_gian' => ''];
    
        $messages = json_decode(file_get_contents($file), true);
        if (!$messages || count($messages) === 0) return ['noi_dung' => '', 'thoi_gian' => ''];
    
        $last = end($messages);
        $timestamp = strtotime($last['timestamp']);
        return [
            'noi_dung' => $last['noi_dung'],
            'thoi_gian' => $this->tinhThoiGian($timestamp)
        ];
    }
    
    private function tinhThoiGian($timestamp) {
        $now = time();
        $diff = $now - $timestamp;
        if ($diff < 60) return $diff . ' giây trước';
        elseif ($diff < 3600) return floor($diff / 60) . ' phút trước';
        elseif ($diff < 86400) return floor($diff / 3600) . ' giờ trước';
        elseif ($diff < 30 * 86400) return floor($diff / 86400) . ' ngày trước';
        else return date('d/m/Y', $timestamp);
    }

    public function demTinNhanChuaDoc($idNguoiDung) {
        $conn = (new mConnect())->connect();
        $sql = "SELECT COUNT(*) AS so_chua_doc FROM tin_nhan WHERE id_nguoi_nhan = ? AND da_doc = 0";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$idNguoiDung]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($row['so_chua_doc'] ?? 0);
    }

    public function getFirstMessage($from, $to) {
        $conn = $this->connect();
        $sql = "SELECT * FROM tin_nhan 
                WHERE ((id_nguoi_gui = ? AND id_nguoi_nhan = ?) OR (id_nguoi_gui = ? AND id_nguoi_nhan = ?)) 
                ORDER BY thoi_gian ASC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $from, $to, $to, $from);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    
    
    
}
