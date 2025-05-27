<?php
include_once("mConnect.php");

class mDuyetNapTien {
    private $conn;

    public function __construct() {
        $p = new Connect();
        $this->conn = $p->connect();
    }

    // Get all transactions with optional filters and pagination
    public function getAllTransactions($status = null, $userId = null, $search = null, $page = 1, $perPage = 10) {
        $query = "
            SELECT lsck.*, nd.ten_dang_nhap, nd.email, tck.so_du
            FROM lich_su_chuyen_khoan lsck
            LEFT JOIN nguoi_dung nd ON lsck.id_nguoi_dung = nd.id
            LEFT JOIN taikhoan_chuyentien tck ON lsck.id_nguoi_dung = tck.id_nguoi_dung
            WHERE 1=1
        ";

        $countQuery = "
            SELECT COUNT(*) as total
            FROM lich_su_chuyen_khoan lsck
            LEFT JOIN nguoi_dung nd ON lsck.id_nguoi_dung = nd.id
            LEFT JOIN taikhoan_chuyentien tck ON lsck.id_nguoi_dung = tck.id_nguoi_dung
            WHERE 1=1
        ";

        $types = "";
        $params = [];

        if ($status !== null && $status !== '') {
            $query .= " AND lsck.trang_thai_ck = ?";
            $countQuery .= " AND lsck.trang_thai_ck = ?";
            $types .= "s";
            $params[] = $status;
        }

        if ($userId !== null && $userId !== '') {
            $query .= " AND lsck.id_nguoi_dung = ?";
            $countQuery .= " AND lsck.id_nguoi_dung = ?";
            $types .= "i";
            $params[] = $userId;
        }

        if ($search !== null && $search !== '') {
            $query .= " AND (nd.ten_dang_nhap LIKE ? OR nd.email LIKE ? OR lsck.noi_dung_ck LIKE ?)";
            $countQuery .= " AND (nd.ten_dang_nhap LIKE ? OR nd.email LIKE ? OR lsck.noi_dung_ck LIKE ?)";
            $types .= "sss";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Get total count for pagination
        $countStmt = $this->conn->prepare($countQuery);
        if (!empty($params)) {
            $countStmt->bind_param($types, ...$params);
        }
        $countStmt->execute();
        $countResult = $countStmt->get_result();
        $totalCount = $countResult->fetch_assoc()['total'];
        $totalPages = ceil($totalCount / $perPage);

        // Calculate pagination
        $offset = ($page - 1) * $perPage;
        $query .= " ORDER BY lsck.ngay_tao DESC LIMIT ?, ?";
        $types .= "ii";
        $params[] = $offset;
        $params[] = $perPage;

        // Get paginated data
        $stmt = $this->conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return [
            'data' => $data,
            'pagination' => [
                'total' => $totalCount,
                'per_page' => $perPage,
                'current_page' => $page,
                'total_pages' => $totalPages
            ]
        ];
    }

    // Get transaction by ID
    public function getTransactionById($id) {
        $query = "
            SELECT lsck.*, nd.ten_dang_nhap, nd.email, tck.so_du
            FROM lich_su_chuyen_khoan lsck
            LEFT JOIN nguoi_dung nd ON lsck.id_nguoi_dung = nd.id
            LEFT JOIN taikhoan_chuyentien tck ON lsck.id_nguoi_dung = tck.id_nguoi_dung
            WHERE lsck.id_lich_su = ?
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Update transaction status
    public function updateTransactionStatus($id, $status) {
        try {
            $query = "UPDATE lich_su_chuyen_khoan SET trang_thai_ck = ? WHERE id_lich_su = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("si", $status, $id);
            
            if (!$stmt->execute()) {
                throw new Exception('Không thể cập nhật trạng thái: ' . $stmt->error);
            }
            
            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            error_log("Update status error: " . $e->getMessage());
            throw $e;
        }
    }

    // Update multiple transaction statuses
    public function updateMultipleTransactionStatus($ids, $status) {
        if (empty($ids)) {
            return false;
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "
            UPDATE lich_su_chuyen_khoan
            SET trang_thai_ck = ?
            WHERE id_lich_su IN ($placeholders) AND trang_thai_ck = 'Đang chờ duyệt'
        ";

        $types = "s" . str_repeat("i", count($ids));
        $params = array_merge([$status], $ids);

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        return $stmt->execute();
    }

    // Update user balance
    public function updateUserBalance($userId, $amount) {
        try {
            // Get current balance
            $query = "SELECT so_du FROM taikhoan_chuyentien WHERE id_nguoi_dung = ? FOR UPDATE";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $account = $result->fetch_assoc();

            if (!$account) {
                throw new Exception('Tài khoản không tồn tại');
            }

            $newBalance = $account['so_du'] + $amount;

            // Update balance
            $query = "UPDATE taikhoan_chuyentien SET so_du = ? WHERE id_nguoi_dung = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("di", $newBalance, $userId);
            
            if (!$stmt->execute()) {
                throw new Exception('Không thể cập nhật số dư: ' . $stmt->error);
            }

            return true;
        } catch (Exception $e) {
            error_log("Update balance error: " . $e->getMessage());
            throw $e;
        }
    }

    // Extract amount from transaction content
    public function extractAmountFromContent($content) {
        preg_match('/(\d+)$/', $content, $matches);
        if (isset($matches[1])) {
            return (int)$matches[1];
        }
        return 0;
    }

    // Get transaction statistics
    public function getTransactionStatistics() {
        $query = "
            SELECT 
                COUNT(*) as total_transactions,
                SUM(CASE WHEN trang_thai_ck = 'Đang chờ duyệt' THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN trang_thai_ck = 'Đã duyệt' THEN 1 ELSE 0 END) as approved_count,
                SUM(CASE WHEN trang_thai_ck = 'Từ chối duyệt' THEN 1 ELSE 0 END) as rejected_count
            FROM lich_su_chuyen_khoan
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Get users for dropdown
    public function getAllUsers() {
        $query = "SELECT id, ten_dang_nhap FROM nguoi_dung ORDER BY ten_dang_nhap";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    // Begin transaction
    public function beginTransaction() {
        return $this->conn->begin_transaction();
    }

    // Commit transaction
    public function commitTransaction() {
        return $this->conn->commit();
    }

    // Rollback transaction
    public function rollbackTransaction() {
        return $this->conn->rollback();
    }
}
?>
