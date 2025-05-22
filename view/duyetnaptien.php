<?php
include_once 'controller/cduyetnaptien.php';
$controller = new cduyetnaptien();

// Handle bulk actions
if (isset($_POST['bulk_action']) && isset($_POST['transaction_ids'])) {
    $action = $_POST['bulk_action'];
    $ids = $_POST['transaction_ids'];
    
    if (!empty($ids)) {
        if ($action === 'approve') {
            $result = $controller->bulkApproveTransactions($ids);
        } elseif ($action === 'reject') {
            $result = $controller->bulkRejectTransactions($ids);
        }
        
        $message = isset($result['message']) ? $result['message'] : 'Đã xử lý hàng loạt';
        $messageType = isset($result['success']) && $result['success'] ? 'success' : 'danger';
        
        // Redirect back to the page with a message
        header("Location: /project/ad/kdnaptien?msg=" . urlencode($message) . "&type=" . $messageType);
        exit;
    }
}

// Handle direct actions (approve/reject)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = (int)$_GET['id'];
    $result = null;
    
    if ($action === 'approve') {
        $result = $controller->approveTransaction($id);
        $message = $result['success'] ? "Giao dịch #$id đã được phê duyệt thành công!" : "Lỗi: " . $result['message'];
        $messageType = $result['success'] ? 'success' : 'danger';
    } elseif ($action === 'reject') {
        $result = $controller->rejectTransaction($id);
        $message = $result['success'] ? "Giao dịch #$id đã bị từ chối!" : "Lỗi: " . $result['message'];
        $messageType = $result['success'] ? 'success' : 'danger';
    }
    
    // Redirect back to the page with a message
    header("Location: /project/ad/kdnaptien?msg=" . urlencode($message) . "&type=" . $messageType);
    exit;
}

// Get transaction details if ID is provided
$transactionDetails = null;
if (isset($_GET['view']) && is_numeric($_GET['view'])) {
    $transactionDetails = $controller->getTransactionById((int)$_GET['view']);
}

// Get filter parameters
$status = isset($_GET['status']) && $_GET['status'] !== '' ? (int)$_GET['status'] : null;
$userId = isset($_GET['user_id']) && $_GET['user_id'] !== '' ? (int)$_GET['user_id'] : null;
$search = isset($_GET['search']) && $_GET['search'] !== '' ? $_GET['search'] : null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;

// Get transactions based on filters
$transactionData = $controller->getAllTransactions($status, $userId, $search, $page, $perPage);
$transactions = $transactionData['data'];
$pagination = $transactionData['pagination'];

// Get transaction statistics
$stats = $controller->getTransactionStatistics();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Quản lý giao dịch - Majestic Admin Pro</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../admin/src/assets/vendors/mdi/css/materialdesignicons.min.css">
<link rel="stylesheet" href="../admin/src/assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../admin/src/assets/css/style.css">
<!-- endinject -->
<link rel="shortcut icon" href="../admin/src/assets/images/favicon.ico" />
<link rel="stylesheet" href="/project/css/kdnaptien.css">
</head>
<body>
<div class="">
    <div class="">
    <div class="">
        <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title">Quản lý giao dịch nạp tiền</h4>
                <?php if (isset($stats['pending_count']) && $stats['pending_count'] > 0): ?>
                <span class="badge badge-pending">
                    <?php echo $stats['pending_count']; ?> giao dịch đang chờ xử lý
                </span> 
                <?php endif; ?>
                </div>
                
                <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-<?php echo isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'success'; ?>">
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                </div>
                <?php endif; ?>
                
                <?php if ($transactionDetails): ?>
                <!-- Transaction Details Section -->
                <div class="transaction-details">
                    <h3>Chi tiết giao dịch</h3>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>ID giao dịch:</strong> <?php echo $transactionDetails['id_lich_su']; ?></p>
                            <p><strong>Người dùng:</strong> <?php echo $transactionDetails['ten_dang_nhap']; ?></p>
                            <p><strong>Email:</strong> <?php echo $transactionDetails['email']; ?></p>
                            <p><strong>Nội dung:</strong> <?php echo $transactionDetails['noi_dung_ck']; ?></p>
                            <p><strong>Trạng thái:</strong> 
                                <?php 
                                switch($transactionDetails['trang_thai_ck']) {
                                    case 0:
                                        echo '<span class="status-pending"><i class="mdi mdi-clock-outline"></i> Đang chờ xác nhận</span>';
                                        break;
                                    case 1:
                                        echo '<span class="status-approved"><i class="mdi mdi-check-circle"></i> Đã xác nhận</span>';
                                        break;
                                    case 2:
                                        echo '<span class="status-rejected"><i class="mdi mdi-close-circle"></i> Đã từ chối</span>';
                                        break;
                                    default:
                                        echo '<span>Không xác định</span>';
                                }
                                ?>
                            </p>
                            <p><strong>Số dư hiện tại:</strong> <?php echo number_format($transactionDetails['so_du'], 0, ',', '.'); ?> VND</p>
                        </div>
                        <div class="col-md-6">
                            <?php if (!empty($transactionDetails['hinh_anh_ck'])): ?>
                                <p><strong>Hình ảnh chuyển khoản:</strong></p>
                                <img src="/project/img/<?php echo $transactionDetails['hinh_anh_ck']; ?>" alt="Transfer Image" class="detail-image">
                            <?php else: ?>
                                <p><strong>Hình ảnh chuyển khoản:</strong> Không có hình ảnh</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if ($transactionDetails['trang_thai_ck'] == 0): ?>
                    <div class="mt-3">
                        <a href="/project/ad/kdnaptien?action=approve&id=<?php echo $transactionDetails['id_lich_su']; ?>" 
                            class="btn btn-success" 
                            onclick="return confirm('Bạn có chắc chắn muốn phê duyệt giao dịch này?');">
                            <i class="mdi mdi-check"></i> Phê duyệt
                        </a>
                        <a href="/project/ad/kdnaptien?action=reject&id=<?php echo $transactionDetails['id_lich_su']; ?>" 
                            class="btn btn-danger" 
                            onclick="return confirm('Bạn có chắc chắn muốn từ chối giao dịch này?');">
                            <i class="mdi mdi-close"></i> Từ chối
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mt-3">
                        <a href="/project/ad/kdnaptien" class="btn btn-secondary">Quay lại danh sách</a>
                    </div>
                </div>
                <?php else: ?>
                <!-- Statistics Section -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="stats-card">
                            <h5>Thống kê giao dịch</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="stats-item">
                                        <span class="stats-label">Tổng số giao dịch:</span>
                                        <span class="stats-value"><?php echo $stats['total_transactions']; ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stats-item">
                                        <span class="stats-label">Đang chờ xác nhận:</span>
                                        <span class="stats-value status-pending"><?php echo $stats['pending_count']; ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stats-item">
                                        <span class="stats-label">Đã xác nhận:</span>
                                        <span class="stats-value status-approved"><?php echo $stats['approved_count']; ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stats-item">
                                        <span class="stats-label">Đã từ chối:</span>
                                        <span class="stats-value status-rejected"><?php echo $stats['rejected_count']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Search and Filter Section -->
                <div class="filter-section">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <form method="GET" class="search-box">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" placeholder="Tìm kiếm theo tên, email hoặc nội dung...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="mdi mdi-magnify"></i> Tìm kiếm
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <form method="GET" class="row">
                        <div class="col-md-4 form-group">
                            <label for="status">Trạng thái</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Tất cả trạng thái</option>
                                <option value="0" <?php echo $status === 0 ? 'selected' : ''; ?>>Đang chờ xác nhận</option>
                                <option value="1" <?php echo $status === 1 ? 'selected' : ''; ?>>Đã xác nhận</option>
                                <option value="2" <?php echo $status === 2 ? 'selected' : ''; ?>>Đã từ chối</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="user_id">ID người dùng</label>
                            <input type="number" class="form-control" id="user_id" name="user_id" value="<?php echo $userId !== null ? $userId : ''; ?>" placeholder="Nhập ID người dùng">
                        </div>
                        <div class="col-md-4 form-group d-flex align-items-end">
                            <button type="submit" class="btn btn-primary mr-2">Lọc</button>
                            <a href="/project/ad/kdnaptien" class="btn btn-secondary">Đặt lại</a>
                        </div>
                        <?php if (isset($_GET['search'])): ?>
                            <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
                        <?php endif; ?>
                    </form>
                </div>
                
                <!-- Bulk Actions -->
                <form method="POST" id="bulkActionForm">
                    <div class="bulk-actions">
                        <select class="form-control" name="bulk_action" style="width: auto;">
                            <option value="">-- Chọn hành động --</option>
                            <option value="approve">Phê duyệt đã chọn</option>
                            <option value="reject">Từ chối đã chọn</option>
                        </select>
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Bạn có chắc chắn muốn thực hiện hành động này cho tất cả giao dịch đã chọn?');">Áp dụng</button>
                    </div>
                
                    <!-- Transactions Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>ID</th>
                                    <th>Người dùng</th>
                                    <th>Nội dung</th>
                                    <th>Hình ảnh</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($transactions)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Không có giao dịch nào</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($transactions as $transaction): ?>
                                        <tr>
                                            <td>
                                                <?php if ($transaction['trang_thai_ck'] == 0): ?>
                                                    <input type="checkbox" name="transaction_ids[]" value="<?php echo $transaction['id_lich_su']; ?>" class="transaction-checkbox">
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $transaction['id_lich_su']; ?></td>
                                            <td>
                                                <div><?php echo $transaction['ten_dang_nhap']; ?></div>
                                                <small class="text-muted"><?php echo $transaction['email']; ?></small>
                                            </td>
                                            <td><?php echo $transaction['noi_dung_ck']; ?></td>
                                            <td>
                                                <?php if (!empty($transaction['hinh_anh_ck'])): ?>
                                                    <a href="/project/img/<?php echo $transaction['hinh_anh_ck']; ?>" target="_blank">
                                                        <img src="/project/img/<?php echo $transaction['hinh_anh_ck']; ?>" 
                                                            alt="Transfer Image" 
                                                            class="transaction-image">
                                                    </a>
                                                <?php else: ?>
                                                    <span>Không có hình ảnh</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    switch($transaction['trang_thai_ck']) {
                                                        case 0:
                                                            echo '<span class="status-pending"><i class="mdi mdi-clock-outline"></i> Đang chờ xác nhận</span>';
                                                            break;
                                                        case 1:
                                                            echo '<span class="status-approved"><i class="mdi mdi-check-circle"></i> Đã xác nhận</span>';
                                                            break;
                                                        case 2:
                                                            echo '<span class="status-rejected"><i class="mdi mdi-close-circle"></i> Đã từ chối</span>';
                                                            break;
                                                        default:
                                                            echo '<span>Không xác định</span>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <a href="/project/ad/kdnaptien?view=<?php echo $transaction['id_lich_su']; ?>" class="btn btn-info btn-sm">
                                                    <i class="mdi mdi-eye"></i> Xem chi tiết
                                                </a>
                                                
                                                <?php if ($transaction['trang_thai_ck'] == 0): ?>
                                                    <div class="mt-1">
                                                        <a href="/project/ad/kdnaptien?action=approve&id=<?php echo $transaction['id_lich_su']; ?>" 
                                                        class="btn btn-success btn-sm" 
                                                        onclick="return confirm('Bạn có chắc chắn muốn phê duyệt giao dịch này?');">
                                                            <i class="mdi mdi-check"></i> Phê duyệt
                                                        </a>
                                                        <a href="/project/ad/kdnaptien?action=reject&id=<?php echo $transaction['id_lich_su']; ?>" 
                                                        class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Bạn có chắc chắn muốn từ chối giao dịch này?');">
                                                            <i class="mdi mdi-close"></i> Từ chối
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </form>
                
                <!-- Pagination -->
                <?php if ($pagination['total_pages'] > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="/project/ad/kdnaptien?page=1<?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?><?php echo isset($_GET['user_id']) ? '&user_id=' . $_GET['user_id'] : ''; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">&laquo; Đầu</a>
                            <a href="/project/ad/kdnaptien?page=<?php echo $page - 1; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?><?php echo isset($_GET['user_id']) ? '&user_id=' . $_GET['user_id'] : ''; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">&lsaquo; Trước</a>
                        <?php else: ?>
                            <span class="disabled">&laquo; Đầu</span>
                            <span class="disabled">&lsaquo; Trước</span>
                        <?php endif; ?>
                        
                        <?php
                        $startPage = max(1, $page - 2);
                        $endPage = min($pagination['total_pages'], $page + 2);
                        
                        for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <?php if ($i == $page): ?>
                                <span class="active"><?php echo $i; ?></span>
                            <?php else: ?>
                                <a href="/project/ad/kdnaptien?page=<?php echo $i; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?><?php echo isset($_GET['user_id']) ? '&user_id=' . $_GET['user_id'] : ''; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($page < $pagination['total_pages']): ?>
                            <a href="/project/ad/kdnaptien?page=<?php echo $page + 1; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?><?php echo isset($_GET['user_id']) ? '&user_id=' . $_GET['user_id'] : ''; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">Tiếp &rsaquo;</a>
                            <a href="/project/ad/kdnaptien?page=<?php echo $pagination['total_pages']; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?><?php echo isset($_GET['user_id']) ? '&user_id=' . $_GET['user_id'] : ''; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">Cuối &raquo;</a>
                        <?php else: ?>
                            <span class="disabled">Tiếp &rsaquo;</span>
                            <span class="disabled">Cuối &raquo;</span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>

<!-- plugins:js -->
<script src="../admin/src/assets/vendors/js/vendor.bundle.base.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- endinject -->

<script>
    $(document).ready(function() {
        // Select all checkbox
        $('#selectAll').change(function() {
            $('.transaction-checkbox').prop('checked', $(this).prop('checked'));
        });
        
        // Update select all checkbox when individual checkboxes change
        $('.transaction-checkbox').change(function() {
            if ($('.transaction-checkbox:checked').length === $('.transaction-checkbox').length) {
                $('#selectAll').prop('checked', true);
            } else {
                $('#selectAll').prop('checked', false);
            }
        });
        
        // Validate bulk action form submission
        $('#bulkActionForm').submit(function(e) {
            var action = $('select[name="bulk_action"]').val();
            var checkedBoxes = $('.transaction-checkbox:checked');
            
            if (action === '') {
                alert('Vui lòng chọn một hành động');
                e.preventDefault();
                return false;
            }
            
            if (checkedBoxes.length === 0) {
                alert('Vui lòng chọn ít nhất một giao dịch');
                e.preventDefault();
                return false;
            }
            
            return true;
        });
    });
</script>

</body>
</html>