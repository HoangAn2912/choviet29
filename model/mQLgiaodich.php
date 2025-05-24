<?php
include_once("mConnect.php");

class mGiaodich {
    private $conn;
    
    public function __construct() {
        $this->conn = new Connect();
        $this->conn = $this->conn->connect();
    }
    
    // Get all transactions
    public function getAllTransactions() {
        $query = "SELECT gd.*, nd.ten_dang_nhap 
                 FROM giao_dich gd 
                 LEFT JOIN nguoi_dung nd ON gd.id_nguoi_dung = nd.id 
                 ORDER BY gd.ngay_tao DESC";
        $result = $this->conn->query($query);
        $data = array();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    // Get paginated transactions with filters
    public function getPaginatedTransactions($offset, $limit, $statusFilter = 'all', $typeFilter = 'all', $searchTerm = '') {
        $query = "SELECT gd.*, nd.ten_dang_nhap 
                 FROM giao_dich gd 
                 LEFT JOIN nguoi_dung nd ON gd.id_nguoi_dung = nd.id 
                 WHERE 1=1";
        
        // Apply status filter
        if ($statusFilter != 'all') {
            $query .= " AND gd.trang_thai = '" . $this->conn->real_escape_string($statusFilter) . "'";
        }
        
        // Apply type filter
        if ($typeFilter != 'all') {
            $query .= " AND gd.loai_giao_dich = '" . $this->conn->real_escape_string($typeFilter) . "'";
        }
        
        // Apply search filter
        if (!empty($searchTerm)) {
            $searchTerm = $this->conn->real_escape_string($searchTerm);
            $query .= " AND (gd.id LIKE '%$searchTerm%' OR nd.ten_dang_nhap LIKE '%$searchTerm%')";
        }
        
        $query .= " ORDER BY gd.ngay_tao DESC LIMIT $offset, $limit";
        
        $result = $this->conn->query($query);
        $data = array();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    // Count transactions for pagination
    public function countTransactions($statusFilter = 'all', $typeFilter = 'all', $searchTerm = '') {
        $query = "SELECT COUNT(*) as total 
                 FROM giao_dich gd 
                 LEFT JOIN nguoi_dung nd ON gd.id_nguoi_dung = nd.id 
                 WHERE 1=1";
        
        // Apply status filter
        if ($statusFilter != 'all') {
            $query .= " AND gd.trang_thai = '" . $this->conn->real_escape_string($statusFilter) . "'";
        }
        
        // Apply type filter
        if ($typeFilter != 'all') {
            $query .= " AND gd.loai_giao_dich = '" . $this->conn->real_escape_string($typeFilter) . "'";
        }
        
        // Apply search filter
        if (!empty($searchTerm)) {
            $searchTerm = $this->conn->real_escape_string($searchTerm);
            $query .= " AND (gd.id LIKE '%$searchTerm%' OR nd.ten_dang_nhap LIKE '%$searchTerm%')";
        }
        
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        
        return $row['total'];
    }
    
    // Get transaction by ID
    public function getTransactionById($id) {
        $id = $this->conn->real_escape_string($id);
        $query = "SELECT gd.*, nd.ten_dang_nhap 
                 FROM giao_dich gd 
                 LEFT JOIN nguoi_dung nd ON gd.id_nguoi_dung = nd.id 
                 WHERE gd.id = '$id'";
        
        $result = $this->conn->query($query);
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    // Add new transaction
    public function addTransaction($userId, $type, $amount, $status) {
        $userId = $this->conn->real_escape_string($userId);
        $type = $this->conn->real_escape_string($type);
        $amount = $this->conn->real_escape_string($amount);
        $status = $this->conn->real_escape_string($status);
        $date = date('Y-m-d H:i:s');
        
        $query = "INSERT INTO giao_dich (id_nguoi_dung, loai_giao_dich, so_tien, trang_thai, ngay_tao) 
                 VALUES ('$userId', '$type', '$amount', '$status', '$date')";
        
        if ($this->conn->query($query)) {
            return $this->conn->insert_id;
        }
        
        return false;
    }
    
    // Update transaction status
    public function updateTransactionStatus($id, $status) {
        $id = $this->conn->real_escape_string($id);
        $status = $this->conn->real_escape_string($status);
        
        $query = "UPDATE giao_dich SET trang_thai = '$status' WHERE id = '$id'";
        
        return $this->conn->query($query);
    }
    
    // Process bulk status update
    public function bulkUpdateStatus($ids, $status) {
        if (empty($ids)) return false;
        
        $idList = array();
        foreach ($ids as $id) {
            $idList[] = $this->conn->real_escape_string($id);
        }
        
        $idString = implode("','", $idList);
        $status = $this->conn->real_escape_string($status);
        
        $query = "UPDATE giao_dich SET trang_thai = '$status' WHERE id IN ('$idString')";
        
        return $this->conn->query($query);
    }
    
    // Get unique transaction types
    public function getTransactionTypes() {
        $query = "SELECT DISTINCT loai_giao_dich FROM giao_dich ORDER BY loai_giao_dich";
        $result = $this->conn->query($query);
        $types = array();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $types[] = $row['loai_giao_dich'];
            }
        }
        
        return $types;
    }
    
    // Get transaction statistics
    public function getTransactionStats() {
        $query = "SELECT 
                    COUNT(*) as total_transactions,
                    SUM(CASE WHEN trang_thai = 'Hoàn thành' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN trang_thai = 'Đang xử lý' THEN 1 ELSE 0 END) as processing,
                    SUM(CASE WHEN trang_thai = 'Hủy' THEN 1 ELSE 0 END) as cancelled,
                    SUM(CASE WHEN loai_giao_dich = 'Nạp tiền' THEN so_tien ELSE 0 END) as total_deposits,
                    SUM(CASE WHEN loai_giao_dich = 'Rút tiền' THEN so_tien ELSE 0 END) as total_withdrawals
                 FROM giao_dich";
        
        $result = $this->conn->query($query);
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return array(
            'total_transactions' => 0,
            'completed' => 0,
            'processing' => 0,
            'cancelled' => 0,
            'total_deposits' => 0,
            'total_withdrawals' => 0
        );
    }
    
    // Get users for dropdown
    public function getUsers() {
        $query = "SELECT id, ten_dang_nhap FROM nguoi_dung WHERE id_vai_tro = 2 AND trang_thai_hd = 1 ORDER BY ten_dang_nhap";
        $result = $this->conn->query($query);
        $users = array();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        
        return $users;
    }
}
?>