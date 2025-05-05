<?php
include_once("controller/cKDbaidang.php");
$p = new ckdbaidang();
if(isset($_GET['id'])){
    $dt = $p->getonebaidang($_GET['id']);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết bài đăng</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
        }
        .product-title {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .meta-info {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .price {
            font-size: 1.75rem;
            font-weight: 700;
            color: #212529;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }
        .detail-label {
            color: #6c757d;
            font-size: 0.875rem;
        }
        .detail-value {
            font-weight: 500;
        }
        .detail-item {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        .detail-icon {
            color: #6c757d;
            margin-right: 1rem;
            width: 20px;
            text-align: center;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 0.375rem;
            transition: all 0.2s;
        }
        .thumbnail.active {
            border-color: #0d6efd;
        }
        .main-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 0.375rem;
        }
        .timeline {
            position: relative;
            padding-left: 30px;
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
        
    </style>
</head>
<body>

<div class="container">
    <?php foreach($dt as $ct) { ?>
    <?php $images = explode(',', $ct['hinh_anh']); ?>
    <div class="mb-4">
        <h1 class="product-title"><?php echo $ct['tieu_de']; ?></h1>
        <div class="meta-info">
            <span><i class="far fa-calendar me-1"></i> Đăng ngày: <?php echo $ct['ngay_cap_nhat']; ?></span>
            <span class="mx-2">•</span>
            <span><i class="far fa-user me-1"></i> Bởi: <?php echo $ct['ho_ten']; ?></span>
            <span class="mx-2">•</span>
            <span><i class="far fa-id-card me-1"></i> ID: <?php echo $ct['id']; ?></span>
        </div>
    </div>

    <div class="row g-4">
        <!-- Product Images -->
        <div class="col-md-6">
            <div class="card p-3">
                <img src="/project/img/<?php echo $images[0]; ?>" alt="<?php echo $ct['tieu_de']; ?>" class="main-image mb-3" id="mainImage">
                
                <div class="d-flex overflow-auto gap-2 pb-2">
                    <?php foreach($images as $img) { ?>
                        <img src="/project/img/<?php echo $img; ?>" alt="Thumbnail" class="thumbnail" onclick="changeImage(this, '/project/img/<?php echo $img; ?>')">
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <div class="price"><?php echo number_format($ct['gia'], 0, ',', '.'); ?> VNĐ</div>
                        <span class="badge <?php echo ($ct['trang_thai'] == 'Đã duyệt') ? 'badge-success' : 'badge-pending'; ?> mt-2">
                            <?php echo $ct['trang_thai']; ?>
                        </span>
                    </div>
                    <button class="btn btn-primary">Liên hệ người bán</button>
                </div>

                <hr>

                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-tag"></i></div>
                    <div>
                        <div class="detail-label">Loại sản phẩm</div>
                        <div class="detail-value"><?php echo $ct['ten_loai_san_pham']; ?></div>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-user"></i></div>
                    <div>
                        <div class="detail-label">Người đăng</div>
                        <div class="detail-value"><?php echo $ct['ho_ten']; ?></div>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-dollar-sign"></i></div>
                    <div>
                        <div class="detail-label">Giá</div>
                        <div class="detail-value"><?php echo number_format($ct['gia'], 0, ',', '.'); ?> VNĐ</div>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="detail-label">Ngày đăng</div>
                        <div class="detail-value"><?php echo $ct['ngay_cap_nhat']; ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card p-4">
                    <h3 class="fs-4 fw-bold mb-3">Mô tả sản phẩm</h3>
                    <p class="mb-0"><?php echo nl2br($ct['mo_ta']); ?></p>
                </div>
            </div>
        </div>
    <?php
        $trang_thai = $ct['trang_thai'];
        $trang_thai_ban = $ct['trang_thai_ban'];
        $timeline = [
            [
                'status' => 'Tạo bài đăng',
                'date' => $ct['ngay_tao'],
                'class' => 'active'
            ]
        ];
        
        if($trang_thai == "Chờ duyệt") {
            $timeline[] = [
                'status' => 'Đang chờ duyệt',
                'date' => $ct['ngay_tao'],
                'class' => 'pending'
            ];
        } else if($trang_thai == "Đã duyệt") {
            $timeline[] = [
                'status' => 'Đang chờ duyệt',
                'date' => $ct['ngay_tao'],
                'class' => 'active'
            ];
            $timeline[] = [
                'status' => 'Đã duyệt bởi Admin',
                'date' => $ct['ngay_cap_nhat'],
                'class' => 'active'
            ];
        } else if($trang_thai == "Từ chối duyệt") {
            $timeline[] = [
                'status' => 'Đang chờ duyệt',
                'date' => $ct['ngay_tao'],
                'class' => 'active'
            ];
            $timeline[] = [
                'status' => 'Từ chối bởi Admin',
                'date' => $ct['ngay_cap_nhat'],
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




        echo '</div>
        <div class="card p-4 mt-4">
            <h5 class="fs-4 fw-bold mb-3">Lịch sử trạng thái</h5>
            <div class="timeline">';
            
            foreach($timeline as $item) {
                echo '<div class="timeline-item '.$item['class'].'">
                    '.$item['status'].'
                    <span class="timeline-date">'.$item['date'].'</span>
                </div>';
            }
    ?>
    </div>
    
    
    
    
    <?php } ?>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function changeImage(thumbnail, src) {
        // Update main image
        document.getElementById('mainImage').src = src;
        
        // Update active thumbnail
        const thumbnails = document.querySelectorAll('.thumbnail');
        thumbnails.forEach(thumb => {
            thumb.classList.remove('active');
        });
        thumbnail.classList.add('active');
    }
</script>
</body>
</html>