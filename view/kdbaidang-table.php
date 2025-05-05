<?php
include_once("controller/cKDbaidang.php");
$p = new ckdbaidang();
$data = $p->getallbaidang();
// Process form submissions
if (isset($_POST['btn_duyet'])) {
    $p->getduyetbai($_POST['idbv']);
    // Redirect to prevent form resubmission
    header("Location: /project/ad/kdbaidang");
    exit();
} elseif (isset($_POST['btn_tuchoi'])) {
    $id = $_POST['idbv'];
    $ghichu = $_POST['ly_do_tu_choi'];
    $p->gettuchoi($id, $ghichu);
    header("Location: /project/ad/kdbaidang");
    exit();
}

// Get filter values
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';
$filter_product = isset($_GET['product_type']) ? $_GET['product_type'] : '';
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Filter data based on criteria
$filtered_data = [];
foreach ($data as $item) {
    // Apply filters
    $status_match = empty($filter_status) || $item['trang_thai'] == $filter_status;
    $product_match = empty($filter_product) || $item['ten_loai_san_pham'] == $filter_product;
    $search_match = empty($search_term) || 
                   stripos($item['id'], $search_term) !== false || 
                   stripos($item['ten_loai_san_pham'], $search_term) !== false;
    
    if ($status_match && $product_match && $search_match) {
        $filtered_data[] = $item;
    }
}

// Get unique product types for filter dropdown
$product_types = [];
foreach ($data as $item) {
    if (!in_array($item['ten_loai_san_pham'], $product_types)) {
        $product_types[] = $item['ten_loai_san_pham'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kiểm Duyệt Bài Đăng - Hệ Thống Quản Lý</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../admin/src/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../admin/src/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- endinject -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../admin/src/assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../admin/src/assets/images/favicon.ico" />
    <style>
        .table td img {
            width: 120px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .status-badge {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .status-badge i {
            margin-right: 6px;
        }
        
        .status-timeline {
            position: relative;
            padding-left: 30px;
            margin-top: 10px;
            font-size: 0.8rem;
        }
        
        .status-timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            height: 100%;
            width: 2px;
            background-color: #e9ecef;
        }
        
        .timeline-item {
            position: relative;
            padding-bottom: 12px;
            color: #6c757d;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -24px;
            top: 4px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #adb5bd;
        }
        
        .timeline-item.active::before {
            background-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
        }
        
        .timeline-item.rejected::before {
            background-color: #F44336;
            box-shadow: 0 0 0 3px rgba(244, 67, 54, 0.2);
        }
        
        .timeline-item.pending::before {
            background-color: #FFC107;
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.2);
        }
        
        .timeline-item.sold::before {
            background-color: #2196F3;
            box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.2);
        }
        
        .timeline-date {
            font-size: 0.75rem;
            color: #adb5bd;
            margin-left: 5px;
        }
        
        .post-details {
            display: none;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-top: 10px;
        }
        
        .post-details.show {
            display: block;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 8px;
        }
        
        .detail-label {
            width: 120px;
            font-weight: 500;
            color: #495057;
        }
        
        .detail-value {
            flex: 1;
        }
        
        .filter-section {
            background-color: #fff;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }
        
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
        }
        
        .card-title-with-count {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .post-count {
            background-color: #e9ecef;
            color: #495057;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
        }
        
        .modal-reason textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            min-height: 100px;
        }
        
        .expand-row {
            cursor: pointer;
            color: #007bff;
            font-size: 1.2rem;
        }
        
        tr.expanded {
            background-color: #f8f9fa;
        }
        
        .status-summary {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .status-card {
            flex: 1;
            background-color: #fff;
            border-radius: 4px;
            padding: 15px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            text-align: center;
            min-width: 120px;
        }
        
        .status-card .count {
            font-size: 1.8rem;
            font-weight: 600;
            margin: 5px 0;
        }
        
        .status-card .label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .status-card.waiting {
            border-top: 3px solid #FFC107;
        }
        
        .status-card.approved {
            border-top: 3px solid #4CAF50;
        }
        
        .status-card.rejected {
            border-top: 3px solid #F44336;
        }
        
        .status-card.sold {
            border-top: 3px solid #2196F3;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        
        .pagination .page-item {
            margin: 0 5px;
        }
        
        .pagination .page-link {
            border-radius: 4px;
            color: #007bff;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title-with-count">
                            <h4 class="card-title">Kiểm Duyệt Bài Đăng</h4>
                            <span class="post-count"><?php echo count($filtered_data); ?> bài đăng</span>
                        </div>
                        
                        <!-- Status Summary Cards -->
                        <div class="status-summary">
                            <?php
                            $waiting_count = 0;
                            $approved_count = 0;
                            $rejected_count = 0;
                            $sold_count = 0;
                            
                            foreach($data as $item) {
                                if($item['trang_thai'] == "Chờ duyệt") $waiting_count++;
                                if($item['trang_thai'] == "Đã duyệt") $approved_count++;
                                if($item['trang_thai'] == "Từ chối duyệt") $rejected_count++;
                                if($item['trang_thai_ban'] == "Đã bán") $sold_count++;
                            }
                            ?>
                            <div class="status-card waiting">
                                <div class="count"><?php echo $waiting_count; ?></div>
                                <div class="label">Chờ duyệt</div>
                            </div>
                            <div class="status-card approved">
                                <div class="count"><?php echo $approved_count; ?></div>
                                <div class="label">Đã duyệt</div>
                            </div>
                            <div class="status-card rejected">
                                <div class="count"><?php echo $rejected_count; ?></div>
                                <div class="label">Từ chối</div>
                            </div>
                            <div class="status-card sold">
                                <div class="count"><?php echo $sold_count; ?></div>
                                <div class="label">Đã bán</div>
                            </div>
                        </div>
                        
                        <!-- Filter Section -->
                        <div class="filter-section">
                            <form action="" method="GET" class="filter-form">
                                <div class="filter-group">
                                    <label for="status">Trạng thái duyệt</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="Chờ duyệt" <?php echo $filter_status == 'Chờ duyệt' ? 'selected' : ''; ?>>Chờ duyệt</option>
                                        <option value="Đã duyệt" <?php echo $filter_status == 'Đã duyệt' ? 'selected' : ''; ?>>Đã duyệt</option>
                                        <option value="Từ chối duyệt" <?php echo $filter_status == 'Từ chối duyệt' ? 'selected' : ''; ?>>Từ chối duyệt</option>
                                    </select>
                                </div>
                                <div class="filter-group">
                                    <label for="product_type">Loại sản phẩm</label>
                                    <select class="form-control" id="product_type" name="product_type">
                                        <option value="">Tất cả loại</option>
                                        <?php foreach($product_types as $type): ?>
                                            <option value="<?php echo $type; ?>" <?php echo $filter_product == $type ? 'selected' : ''; ?>><?php echo $type; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="filter-group">
                                    <label for="search">Tìm kiếm</label>
                                    <input type="text" class="form-control" id="search" name="search" placeholder="Nhập ID hoặc tên sản phẩm" value="<?php echo $search_term; ?>">
                                </div>
                                <div class="filter-buttons">
                                    <button type="submit" class="btn btn-primary">Lọc</button>
                                    <a href="/project/ad/kdbaidang" class="btn btn-outline-secondary">Đặt lại</a>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Posts Table -->
                        <div class="table-responsive">
                            <table class="table border">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;"></th>
                                        <th><b>ID</b></th>
                                        <th><b>Loại sản phẩm</b></th>
                                        <th><b>Hình ảnh</b></th>
                                        <th><b>Trạng thái</b></th>
                                        <th><b>Thời gian</b></th>
                                        <th><b>Thao tác</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(count($filtered_data) > 0) {
                                    foreach($filtered_data as $r){
                                        $trang_thai = $r['trang_thai'];
                                        $badge_class = "";
                                        $icon = "";
                                        
                                        if($trang_thai == "Đã duyệt"){
                                            $badge_class = "bg-success text-white";
                                            $icon = '<i class="fa fa-check-circle"></i> ';
                                        } else if ($trang_thai == "Chờ duyệt") {
                                            $badge_class = "bg-warning text-dark";
                                            $icon = '<i class="fa fa-clock"></i> ';
                                        } else if($trang_thai == "Từ chối duyệt") {
                                            $badge_class = "bg-danger text-white";
                                            $icon = '<i class="fa fa-times-circle"></i> ';
                                        } else {
                                            $badge_class = "bg-secondary text-white";
                                            $icon = '';
                                        }

                                        $trang_thai_ban = $r['trang_thai_ban'];
                                        $ban_badge = "";
                                        $ban_icon = "";

                                        if ($trang_thai_ban == "Đã bán") {
                                            $ban_badge = "bg-info text-white";
                                            $ban_icon = '<i class="fa fa-shopping-cart"></i> ';
                                        } else if ($trang_thai_ban == "Chưa bán") {
                                            $ban_badge = "bg-light text-dark";
                                            $ban_icon = '<i class="fa fa-tag"></i> ';
                                        } else {
                                            $ban_badge = "bg-secondary text-white";
                                            $ban_icon = '';
                                        }
                                        
                                        // Simulate timeline data (in a real app, this would come from the database)
                                        $timeline = [
                                            [
                                                'status' => 'Tạo bài đăng',
                                                'date' => $r['ngay_tao'],
                                                'class' => 'active'
                                            ]
                                        ];
                                        
                                        if($trang_thai == "Chờ duyệt") {
                                            $timeline[] = [
                                                'status' => 'Đang chờ duyệt',
                                                'date' => $r['ngay_tao'],
                                                'class' => 'pending'
                                            ];
                                        } else if($trang_thai == "Đã duyệt") {
                                            $timeline[] = [
                                                'status' => 'Đang chờ duyệt',
                                                'date' => $r['ngay_tao'],
                                                'class' => 'active'
                                            ];
                                            $timeline[] = [
                                                'status' => 'Đã duyệt bởi Admin',
                                                'date' => $r['ngay_cap_nhat'],
                                                'class' => 'active'
                                            ];
                                        } else if($trang_thai == "Từ chối duyệt") {
                                            $timeline[] = [
                                                'status' => 'Đang chờ duyệt',
                                                'date' => $r['ngay_tao'],
                                                'class' => 'active'
                                            ];
                                            $timeline[] = [
                                                'status' => 'Từ chối bởi Admin',
                                                'date' => $r['ngay_cap_nhat'],
                                                'class' => 'rejected'
                                            ];
                                        }
                                        
                                        if($trang_thai_ban == "Đã bán") {
                                            $timeline[] = [
                                                'status' => 'Đã bán',
                                                'date' => '',
                                                'class' => 'sold'
                                            ];
                                        }

                                        echo '<tr id="row-'.$r['id'].'">
                                            <td class="text-center">
                                                <span class="expand-row" data-id="'.$r['id'].'">
                                                    <i class="fa fa-chevron-down"></i>
                                                </span>
                                            </td>
                                            <td>'.$r['id'].'</td>
                                            <td>'.$r['ten_loai_san_pham'].'</td>
                                            <td><img src="/project/img/'.explode(',', $r['hinh_anh'])[0].'" alt=""></td>
                                            <td>
                                                <div class="status-badge '.$badge_class.'">'.$icon.$trang_thai.'</div>
                                                <div class="status-badge '.$ban_badge.'" style="margin-top: 5px;">'.$ban_icon.$trang_thai_ban.'</div>
                                            </td>
                                            <td>
                                                <div>Đăng: '.$r['ngay_tao'].'</div>
                                                <div>Cập nhật: '.$r['ngay_cap_nhat'].'</div>
                                            </td>
                                            <td>';
                                            
                                            if($trang_thai == "Chờ duyệt"){
                                                echo '<div class="action-buttons">  
                                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveModal" data-id="'.$r['id'].'">
                                                        <i class="fa fa-check"></i> Duyệt
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="'.$r['id'].'">
                                                        <i class="fa fa-times"></i> Từ chối
                                                    </button>
                                                    <form action="/project/ad/kdbaidang?ct&id='.$r['id'].'" method="post">
                                                    <input type="hidden" name="idsp" value="'.$r['id'].'">
                                                        <button type="submit" class="btn btn-primary btn-sm" name="btn_ct" data-bs-toggle="modal" data-bs-target="#viewDetailsModal" data-id="'.$r['id'].'">
                                                            <i class="fa fa-eye"></i> Chi tiết
                                                        </button>
                                                    </form>
                                                </div>';
                                            } else {
                                                echo '<form action="/project/ad/kdbaidang?ct&id='.$r['id'].'" method="post">
                                                    <input type="hidden" name="idsp" value="'.$r['id'].'">
                                                    <div class="action-buttons">
                                                        <button type="submit" class="btn btn-primary btn-sm" name="btn_ct" data-bs-toggle="modal" data-bs-target="#viewDetailsModal" data-id="'.$r['id'].'">
                                                            <i class="fa fa-eye"></i> Chi tiết
                                                        </button>
                                                    </div>
                                                </form>';
                                            }
                                            
                                            echo '</td>
                                        </tr>';
                                        
                                        // Expanded row with details
                                        echo '<tr class="detail-row" id="detail-'.$r['id'].'" style="display: none;">
                                            <td colspan="7">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h5>Chi tiết bài đăng</h5>
                                                        <div class="detail-row">
                                                            <div class="detail-label">Người đăng:</div>
                                                            <div class="detail-value">'.$r['ho_ten'].'</div>
                                                        </div>
                                                        <div class="detail-row">
                                                            <div class="detail-label">Tiêu đề:</div>
                                                            <div class="detail-value">'.$r['tieu_de'].'</div>
                                                        </div>
                                                        <div class="detail-row">
                                                            <div class="detail-label">Giá:</div>
                                                            <div class="detail-value">'.$r['gia'].' VNĐ</div>
                                                        </div>
                                                        <div class="detail-row">
                                                            <div class="detail-label">Mô tả:</div>
                                                            <div class="detail-value">'.$r['mo_ta'].'</div>
                                                        </div>
                                                        <div class="detail-row">
                                                            <div class="detail-label">Ngày đăng:</div>
                                                            <div class="detail-value">'.$r['ngay_tao'].'</div>
                                                        </div>';
                                                        
                                                        if($trang_thai == "Từ chối duyệt") {
                                                            echo '<div class="detail-row">
                                                                <div class="detail-label">Lý do từ chối:</div>
                                                                <div class="detail-value text-danger">'.$r['ghi_chu'].'</div>
                                                            </div>';
                                                        }
                                                        
                                                    echo '</div>
                                                    <div class="col-md-6">
                                                        <h5>Lịch sử trạng thái</h5>
                                                        <div class="status-timeline">';
                                                        
                                                        foreach($timeline as $item) {
                                                            echo '<div class="timeline-item '.$item['class'].'">
                                                                '.$item['status'].'
                                                                <span class="timeline-date">'.$item['date'].'</span>
                                                            </div>';
                                                        }
                                                        
                                                        echo '</div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="7" class="text-center">Không tìm thấy bài đăng nào phù hợp với điều kiện lọc</td></tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Trước</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Sau</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Xác nhận duyệt bài đăng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn duyệt bài đăng này?</p>
                        <p>Sau khi duyệt, bài đăng sẽ được hiển thị công khai trên hệ thống.</p>
                        <input type="hidden" name="idbv" id="approve_post_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-success" name="btn_duyet">Xác nhận duyệt</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Từ chối bài đăng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Vui lòng cung cấp lý do từ chối bài đăng:</p>
                        <div class="modal-reason">
                            <textarea name="ly_do_tu_choi" placeholder="Nhập lý do từ chối..." required></textarea>
                        </div>
                        <input type="hidden" name="idbv" id="reject_post_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger" name="btn_tuchoi">Xác nhận từ chối</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="../admin/src/assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../admin/src/assets/js/off-canvas.js"></script>
    <script src="../admin/src/assets/js/hoverable-collapse.js"></script>
    <script src="../admin/src/assets/js/misc.js"></script>
    
    <script>
        // Toggle row details
        document.querySelectorAll('.expand-row').forEach(function(element) {
            element.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const detailRow = document.getElementById('detail-' + id);
                const icon = this.querySelector('i');
                const mainRow = document.getElementById('row-' + id);
                
                if (detailRow.style.display === 'none') {
                    detailRow.style.display = 'table-row';
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                    mainRow.classList.add('expanded');
                } else {
                    detailRow.style.display = 'none';
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                    mainRow.classList.remove('expanded');
                }
            });
        });
        
        // Set post ID in modals
        document.getElementById('approveModal').addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const postId = button.getAttribute('data-id');
            document.getElementById('approve_post_id').value = postId;
        });
        
        document.getElementById('rejectModal').addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const postId = button.getAttribute('data-id');
            document.getElementById('reject_post_id').value = postId;
        });
        
        
        function printModalContent() {
            const postId = document.getElementById('modal_product_id').textContent;
            printPostDetails(postId);
        }
    </script>
</body>
</html>