<?php
require_once 'model/mChat.php';

class cChat {
    private $model;

    public function __construct() {
        $this->model = new mChat();
    }

    public function saveMessage($from, $to, $content) {
        return $this->model->saveMessage($from, $to, $content);
    }

    public function getMessages($from, $to) {
        return $this->model->getMessages($from, $to);
    }

    public function getConversationUsers($current_user_id) {
        require_once("model/mChat.php");
        $chatModel = new mChat();
        $users = $chatModel->getConversationUsers($current_user_id);
    
        foreach ($users as &$user) {
            $fileName = $this->getChatFileName($current_user_id, $user['id']);
            $filePath = __DIR__ . "/../chat/" . $fileName;
    
            $last = $this->getLastMessageFromFile($filePath);
    
            $user['tin_cuoi'] = $last['noi_dung'];
            $user['thoi_gian'] = $last['thoi_gian'];
        }
    
        return $users;
    }
    
    // ✅ Tạo tên file chat
    private function getChatFileName($id1, $id2) {
        $min = min($id1, $id2);
        $max = max($id1, $id2);
        return "chat_{$min}_{$max}.json";
    }
    
    // ✅ Đọc dòng cuối từ file JSON
    private function getLastMessageFromFile($filePath) {
        if (!file_exists($filePath)) return ['noi_dung' => '', 'thoi_gian' => ''];
    
        $messages = json_decode(file_get_contents($filePath), true);
        if (!is_array($messages) || count($messages) === 0) return ['noi_dung' => '', 'thoi_gian' => ''];
    
        $last = end($messages);
        $timestamp = strtotime($last['timestamp']);
    
        return [
            'noi_dung' => $last['noi_dung'],
            'thoi_gian' => $this->formatThoiGian($timestamp)
        ];
    }
    
    // ✅ Format "5 phút trước"
    private function formatThoiGian($timestamp) {
        $now = time();
        $diff = $now - $timestamp;
    
        if ($diff < 60) return $diff . ' giây trước';
        if ($diff < 3600) return floor($diff / 60) . ' phút trước';
        if ($diff < 86400) return floor($diff / 3600) . ' giờ trước';
        if ($diff < 30 * 86400) return floor($diff / 86400) . ' ngày trước';
        return date('d/m/Y', $timestamp);
    }
    

    public function getMessagesFromFile($from, $to) {
        return $this->model->readChatFile($from, $to);
    }
   
    public function saveFileName($from, $to, $fileName) {
        return $this->model->saveFileName($from, $to, $fileName);
    }

    public function demTinNhanChuaDoc($userId) {
        $model = new mChat();
        return $model->demTinNhanChuaDoc($userId);
    }

    public function getFirstMessage($from, $to) {
        $model = new mChat();
        return $model->getFirstMessage($from, $to);
    }
}
