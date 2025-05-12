<?php
include_once 'model/mDuyetNapTien.php';

class cduyetnaptien {
    private $model;
    
    public function __construct() {
        $this->model = new mduyetnaptien();
    }
    
    // Get all pending transactions
    public function getPendingTransactions() {
        return $this->model->getPendingTransactions();
    }
    
    // Get all transactions with optional filters and pagination
    public function getAllTransactions($status = null, $userId = null, $search = null, $page = 1, $perPage = 10) {
        return $this->model->getAllTransactions($status, $userId, $search, $page, $perPage);
    }
    
    // Get transaction by ID
    public function getTransactionById($id) {
        return $this->model->getTransactionById($id);
    }
    
    // Approve transaction
    public function approveTransaction($id) {
        // Get transaction details
        $transaction = $this->model->getTransactionById($id);
        
        if (!$transaction) {
            return [
                'success' => false,
                'message' => 'Giao dịch không tồn tại'
            ];
        }
        
        if ($transaction['trang_thai_ck'] != 0) {
            return [
                'success' => false,
                'message' => 'Giao dịch này đã được xử lý trước đó'
            ];
        }
        
        // Extract amount from content
        $amount = $this->model->extractAmountFromContent($transaction['noi_dung_ck']);
        
        // Begin transaction
        try {
            // Update transaction status to approved (1)
            $statusUpdated = $this->model->updateTransactionStatus($id, 1);
            
            if (!$statusUpdated) {
                throw new Exception('Không thể cập nhật trạng thái giao dịch');
            }
            
            // Update user balance
            $balanceUpdated = $this->model->updateUserBalance($transaction['id_ck'], $amount);
            
            if (!$balanceUpdated) {
                throw new Exception('Không thể cập nhật số dư tài khoản');
            }
            
            return [
                'success' => true,
                'message' => 'Giao dịch đã được phê duyệt và số dư đã được cập nhật'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    // Reject transaction
    public function rejectTransaction($id) {
        // Get transaction details
        $transaction = $this->model->getTransactionById($id);
        
        if (!$transaction) {
            return [
                'success' => false,
                'message' => 'Giao dịch không tồn tại'
            ];
        }
        
        if ($transaction['trang_thai_ck'] != 0) {
            return [
                'success' => false,
                'message' => 'Giao dịch này đã được xử lý trước đó'
            ];
        }
        
        // Update transaction status to rejected (2)
        $statusUpdated = $this->model->updateTransactionStatus($id, 2);
        
        if ($statusUpdated) {
            return [
                'success' => true,
                'message' => 'Giao dịch đã bị từ chối'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Không thể cập nhật trạng thái giao dịch'
            ];
        }
    }
    
    // Bulk approve transactions
    public function bulkApproveTransactions($ids) {
        if (empty($ids)) {
            return [
                'success' => false,
                'message' => 'Không có giao dịch nào được chọn'
            ];
        }
        
        // Update all selected transactions to approved (1)
        $statusUpdated = $this->model->updateMultipleTransactionStatus($ids, 1);
        
        if ($statusUpdated) {
            // For each approved transaction, update the user balance
            $successCount = 0;
            $failCount = 0;
            
            foreach ($ids as $id) {
                $transaction = $this->model->getTransactionById($id);
                if ($transaction && $transaction['trang_thai_ck'] == 1) {
                    $amount = $this->model->extractAmountFromContent($transaction['noi_dung_ck']);
                    $balanceUpdated = $this->model->updateUserBalance($transaction['id_ck'], $amount);
                    
                    if ($balanceUpdated) {
                        $successCount++;
                    } else {
                        $failCount++;
                    }
                }
            }
            
            return [
                'success' => true,
                'message' => "Đã phê duyệt $successCount giao dịch thành công" . ($failCount > 0 ? ", $failCount giao dịch thất bại" : "")
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Không thể cập nhật trạng thái giao dịch'
            ];
        }
    }
    
    // Bulk reject transactions
    public function bulkRejectTransactions($ids) {
        if (empty($ids)) {
            return [
                'success' => false,
                'message' => 'Không có giao dịch nào được chọn'
            ];
        }
        
        // Update all selected transactions to rejected (2)
        $statusUpdated = $this->model->updateMultipleTransactionStatus($ids, 2);
        
        if ($statusUpdated) {
            return [
                'success' => true,
                'message' => 'Đã từ chối tất cả giao dịch đã chọn'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Không thể cập nhật trạng thái giao dịch'
            ];
        }
    }
    
    // Get transaction statistics
    public function getTransactionStatistics() {
        return $this->model->getTransactionStatistics();
    }
    
    // Export transactions to CSV
    public function exportTransactions($status = null, $userId = null, $search = null) {
        $transactions = $this->model->exportTransactions($status, $userId, $search);
        
        if (empty($transactions)) {
            return false;
        }
        
        // Create CSV content
        $output = fopen('php://temp', 'w');
        
        // Add CSV header
        fputcsv($output, [
            'ID', 
            'Người dùng', 
            'Email', 
            'Nội dung', 
            'Hình ảnh', 
            'Trạng thái', 
            'Số dư'
        ]);
        
        // Add data rows
        foreach ($transactions as $transaction) {
            fputcsv($output, [
                $transaction['id_lich_su'],
                $transaction['ten_dang_nhap'],
                $transaction['email'],
                $transaction['noi_dung_ck'],
                $transaction['hinh_anh_ck'],
                $transaction['trang_thai_text'],
                number_format($transaction['so_du'], 0, ',', '.')
            ]);
        }
        
        // Get CSV content
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
}
?>