<?php
include_once("mConnect.php");
class mduyetnaptien {
    private $conn;

    public function __construct() {
        $p = new Connect();
        $this->conn = $p->connect();
    }

    // Get all pending transactions
    public function getPendingTransactions() {
        $query = "
            SELECT lsck.*, tck.id_nguoi_dung, tck.so_du, u.ten_dang_nhap, u.email
            FROM lich_su_chuyen_khoan lsck
            JOIN taikhoan_chuyentien tck ON lsck.id_lich_su = tck.id_ck
            JOIN nguoi_dung u ON tck.id_nguoi_dung = u.id
            WHERE lsck.trang_thai_ck = 0
            ORDER BY lsck.id_lich_su
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    // Get all transactions with optional filters and pagination
    public function getAllTransactions($status = null, $userId = null, $search = null, $page = 1, $perPage = 10) {
        $query = "
            SELECT lsck.*, tck.id_nguoi_dung, tck.so_du, u.ten_dang_nhap, u.email
            FROM lich_su_chuyen_khoan lsck
            JOIN taikhoan_chuyentien tck ON lsck.id_lich_su = tck.id_ck
            JOIN nguoi_dung u ON tck.id_nguoi_dung = u.id
            WHERE 1=1
        ";

        $countQuery = "
            SELECT COUNT(*) as total
            FROM lich_su_chuyen_khoan lsck
            JOIN taikhoan_chuyentien tck ON lsck.id_lich_su = tck.id_ck
            JOIN nguoi_dung u ON tck.id_nguoi_dung = u.id
            WHERE 1=1
        ";

        $types = "";
        $params = [];

        if ($status !== null) {
            $query .= " AND lsck.trang_thai_ck = ?";
            $countQuery .= " AND lsck.trang_thai_ck = ?";
            $types .= "i";
            $params[] = $status;
        }

        if ($userId !== null) {
            $query .= " AND tck.id_nguoi_dung = ?";
            $countQuery .= " AND tck.id_nguoi_dung = ?";
            $types .= "i";
            $params[] = $userId;
        }

        if ($search !== null) {
            $query .= " AND (u.ten_dang_nhap LIKE ? OR u.email LIKE ? OR lsck.noi_dung_ck LIKE ?)";
            $countQuery .= " AND (u.ten_dang_nhap LIKE ? OR u.email LIKE ? OR lsck.noi_dung_ck LIKE ?)";
            $types .= "sss";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Calculate pagination
        $offset = ($page - 1) * $perPage;
        $query .= " ORDER BY lsck.id_lich_su LIMIT ?, ?";
        $types .= "ii";
        $params[] = $offset;
        $params[] = $perPage;

        // Get total count for pagination
        $countStmt = $this->conn->prepare($countQuery);
        if (!empty($params)) {
            // Remove the last two parameters (offset and limit) for the count query
            $countParams = array_slice($params, 0, -2);
            $countTypes = substr($types, 0, -2);
            if (!empty($countParams)) {
                $countStmt->bind_param($countTypes, ...$countParams);
            }
        }
        $countStmt->execute();
        $countResult = $countStmt->get_result();
        $totalCount = $countResult->fetch_assoc()['total'];
        $totalPages = ceil($totalCount / $perPage);

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
            SELECT lsck.*, tck.id_nguoi_dung, tck.so_du, u.ten_dang_nhap, u.email
            FROM lich_su_chuyen_khoan lsck
            JOIN taikhoan_chuyentien tck ON lsck.id_lich_su = tck.id_ck
            JOIN nguoi_dung u ON tck.id_nguoi_dung = u.id
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
        $query = "
            UPDATE lich_su_chuyen_khoan
            SET trang_thai_ck = ?
            WHERE id_lich_su = ?
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $status, $id);
        return $stmt->execute();
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
            WHERE id_lich_su IN ($placeholders) AND trang_thai_ck = 0
        ";

        $types = "i" . str_repeat("i", count($ids));
        $params = array_merge([$status], $ids);

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        return $stmt->execute();
    }

    // Update user balance
    public function updateUserBalance($accountId, $amount) {
        $this->conn->begin_transaction();

        try {
            $query = "
                SELECT so_du FROM taikhoan_chuyentien
                WHERE id_ck = ? FOR UPDATE
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $accountId);
            $stmt->execute();
            $result = $stmt->get_result();
            $account = $result->fetch_assoc();

            $newBalance = $account['so_du'] + $amount;

            $query = "
                UPDATE taikhoan_chuyentien
                SET so_du = ?
                WHERE id_ck = ?
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("di", $newBalance, $accountId);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    // Extract amount from transaction content
    public function extractAmountFromContent($content) {
        preg_match('/\d+/', $content, $matches);
        if (isset($matches[0])) {
            return (int)$matches[0];
        }
        return 0;
    }

    // Get transaction statistics
    public function getTransactionStatistics() {
        $query = "
            SELECT 
                COUNT(*) as total_transactions,
                SUM(CASE WHEN trang_thai_ck = 0 THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN trang_thai_ck = 1 THEN 1 ELSE 0 END) as approved_count,
                SUM(CASE WHEN trang_thai_ck = 2 THEN 1 ELSE 0 END) as rejected_count
            FROM lich_su_chuyen_khoan
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Export transactions to CSV
    public function exportTransactions($status = null, $userId = null, $search = null) {
        $query = "
            SELECT 
                lsck.id_lich_su, 
                u.ten_dang_nhap, 
                u.email, 
                lsck.noi_dung_ck, 
                lsck.hinh_anh_ck, 
                lsck.trang_thai_ck,
                tck.so_du
            FROM lich_su_chuyen_khoan lsck
            JOIN taikhoan_chuyentien tck ON lsck.id_lich_su = tck.id_ck
            JOIN nguoi_dung u ON tck.id_nguoi_dung = u.id
            WHERE 1=1
        ";

        $types = "";
        $params = [];

        if ($status !== null) {
            $query .= " AND lsck.trang_thai_ck = ?";
            $types .= "i";
            $params[] = $status;
        }

        if ($userId !== null) {
            $query .= " AND tck.id_nguoi_dung = ?";
            $types .= "i";
            $params[] = $userId;
        }

        if ($search !== null) {
            $query .= " AND (u.ten_dang_nhap LIKE ? OR u.email LIKE ? OR lsck.noi_dung_ck LIKE ?)";
            $types .= "sss";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $query .= " ORDER BY lsck.id_lich_su DESC";

        $stmt = $this->conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            // Convert status code to text
            switch ($row['trang_thai_ck']) {
                case 0:
                    $row['trang_thai_text'] = 'Đang chờ xác nhận';
                    break;
                case 1:
                    $row['trang_thai_text'] = 'Đã xác nhận';
                    break;
                case 2:
                    $row['trang_thai_text'] = 'Đã từ chối';
                    break;
                default:
                    $row['trang_thai_text'] = 'Không xác định';
            }
            $data[] = $row;
        }

        return $data;
    }
}
?>