
<?php
include_once ("controller/cProduct.php");
$p = new cProduct();
if (isset($_GET['keyword']) && !empty(trim($_GET['keyword']))) {
    $keyword = trim($_GET['keyword']);
    $products = $p->searchProducts($keyword);
} else {
    $products = $p->getSanPhamMoi();
}

?>

<?php
include_once("view/header.php");
?>

<head>
    <style>
        .object-fit-cover {
            object-fit: cover;
        }
        .product-img-hover {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1;
            overflow: hidden;
            background-color: #f9f9f9;
        }

        .product-img-hover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-img-hover:hover img {
            transform: scale(1.05);
        }

        .product-meta {
            font-size: 13px;
            color: #888;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-item .text-danger {
            font-size: 16px;
            font-weight: 600;
        }
        .category-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;       /* Không bị méo ảnh */
            object-position: center; /* Lấy tâm ảnh làm gốc */
            display: block;
        }
</style>

</head>
   <!-- Carousel Start -->
<div class="container-fluid mb-3 ">
    <div class="row px-xl-5">
        <div class="col-lg-8">
            <div id="header-carousel" class="carousel slide carousel-fade mb-30 mb-lg-0" data-ride="carousel" data-interval="3000">
                <ol class="carousel-indicators">
                    <li data-target="#header-carousel" data-slide-to="0" class="active"></li>
                    <li data-target="#header-carousel" data-slide-to="1"></li>
                    <li data-target="#header-carousel" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item position-relative active" style="height: 430px;">
                        <img class="position-absolute w-100 h-100" src="img/carousel-1.jpg" style="object-fit: cover;">
                    </div>
                    <div class="carousel-item position-relative" style="height: 430px;">
                        <img class="position-absolute w-100 h-100" src="img/carousel-2.jpg" style="object-fit: cover;">
                    </div>
                    <div class="carousel-item position-relative" style="height: 430px;">
                        <img class="position-absolute w-100 h-100" src="img/carousel-3.jpg" style="object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Bên phải giữ nguyên các offer -->
        <div class="col-lg-4">
            <div class="product-offer mb-30" style="height: 200px;">
                <img class="img-fluid" src="img/offer-1.jpg" alt="">
                
            </div>
            <div class="product-offer mb-30" style="height: 200px;">
                <img class="img-fluid" src="img/offer-2.jpg" alt="">
                
            </div>
        </div>
    </div>
</div>
<!-- Carousel End -->

 <!-- Featured Start -->
<div class="container-fluid pt-1">
    <div class="row g-4 px-xl-5 pb-3">
        <div class="col-6 col-md-4 col-lg-2">
            <div class="d-flex flex-column align-items-center justify-content-center bg-light mb-4" style="padding: 30px;">
                <h1 class="fa fa-car text-primary mb-3"></h1>
                <h5 class="font-weight-semi-bold m-0 text-center">Xe Việt</h5>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="d-flex flex-column align-items-center justify-content-center bg-light mb-4" style="padding: 30px;">
                <h1 class="fa fa-bolt text-primary mb-3"></h1>
                <h5 class="font-weight-semi-bold m-0 text-center">Đồ Điện Tử Việt</h5>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="d-flex flex-column align-items-center justify-content-center bg-light mb-4" style="padding: 30px;">
                <h1 class="fa fa-tshirt text-primary mb-3"></h1>
                <h5 class="font-weight-semi-bold m-0 text-center">Thời Trang Việt</h5>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="d-flex flex-column align-items-center justify-content-center bg-light mb-4" style="padding: 30px;">
                <h1 class="fa fa-couch text-primary mb-3"></h1>
                <h5 class="font-weight-semi-bold m-0 text-center">Nội Thất Việt</h5>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="d-flex flex-column align-items-center justify-content-center bg-light mb-4" style="padding: 30px;">
                <h1 class="fa fa-gamepad text-primary mb-3"></h1>
                <h5 class="font-weight-semi-bold m-0 text-center">Giải Trí Việt</h5>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="d-flex flex-column align-items-center justify-content-center bg-light mb-4" style="padding: 30px;">
                <h1 class="fa fa-ellipsis-h text-primary mb-3"></h1>
                <h5 class="font-weight-semi-bold m-0 text-center">Khác</h5>
            </div>
        </div>
    </div>
</div>
<!-- Featured End -->

<!-- Khám phá danh mục -->
<?php
include_once "controller/cCategory.php";
$cCategory = new cCategory();
$categories = $cCategory->showCategoriesWithCount();
$images = ['ao.jpg', 'banghe.jpg', 'giaydep.jpg', 'dungcubep.jpg', 'dungcutrangtri.jpg', 'laptop.jpg', 'mayanh.jpg',
 'maytinhbang.jpg', 'mu.jpg', 'nhaccu.jpg', 'oto.jpg', 'phutungxe.jpg', 'quan.jpg', 'thietbichoigame.jpg', 'thietbithongminh.jpg', 'tuke.jpg', 'tuixach.jpg', 'xemay.jpg', 'xedien.jpg', 'dienthoai.jpg', 'dothethao.jpg', ];
$i = 0;
?>

<div class="container-fluid pt-1">
    <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">Khám phá danh mục</span>
    </h2>
    <div class="row px-xl-5 pb-3" id="category-list">
        <?php foreach ($categories as $cat): ?>
            <?php $img = $images[$i % count($images)]; ?>
            <div class="col-6 col-md-4 col-lg-2 pb-1 category-item <?= $i >= 12 ? 'd-none' : '' ?>">
                <a class="text-decoration-none" href="index.php?category=<?= $cat['id_loai'] ?>">
                    <div class="cat-item img-zoom d-flex align-items-center mb-4">
                        <div class="overflow-hidden" style="width: 100px; height: 100px;">
                            <img class="img-fluid" src="img/<?= $img ?>" alt="">
                        </div>
                        <div class="flex-fill pl-3">
                            <h6><?= htmlspecialchars($cat['ten_loai_san_pham']) ?></h6>
                            <small class="text-body"><?= $cat['so_luong'] ?> sản phẩm</small>
                        </div>
                    </div>
                </a>
            </div>
            <?php $i++; ?>
        <?php endforeach; ?>
    </div>

    <!-- Nút Xem thêm / Thu gọn -->
    <?php if (count($categories) > 12): ?>
    <div class="text-center mt-3">
        <button id="show-more-btn" class="btn btn-primary px-4">Xem thêm</button>
        <button id="collapse-btn" class="btn btn-primary px-4 d-none">Thu gọn</button>
    </div>
    <?php endif; ?>
</div>


<!-- Products Start -->
<div class="container-fluid pt-5 pb-3">
    <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">Tin dành cho bạn</span>
    </h2>
    <div class="row px-xl-5" id="product-list">
        <?php foreach ($products as $index => $sp): ?>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 pb-3 product-item-row <?= $index >= 18 ? 'd-none' : '' ?>">
                <div class="product-item bg-light h-100 p-2">
                    <div class="product-img-hover">
                    <img src="img/<?= htmlspecialchars($sp['anh_dau']) ?>" alt="">
                    </div>
                    <div class="text-center py-3 px-2">
                    <a class="h6 text-decoration-none text-truncate d-block mb-2" href="index.php?detail&id=<?= $sp['id'] ?>">
                            <?= htmlspecialchars($sp['tieu_de']) ?>
                        </a>
                        <div class="product-meta mb-1"><?= htmlspecialchars($sp['mo_ta']) ?></div>
                        
                        <div class="text-danger"><?= number_format($sp['gia']) ?> đ</div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Nút Xem thêm / Thu gọn -->
    <?php if (true): ?>
<div class="text-center mt-3">
    <button id="show-more-btn2" class="btn btn-primary px-4">Xem thêm</button>
    <button id="collapse-btn2" class="btn btn-primary px-4 d-none">Thu gọn</button>
</div>
<?php endif; ?>
</div>
<!-- Products End -->

<!-- Blog -->
<div class="container-fluid mt-2 mb-4 ">
    <div class="row justify-content-center">
        <div class="col-xl-11 col-lg-12 col-md-12">
            <div class="bg-white p-4 rounded shadow-sm">
                <h5 class="font-weight-bold mb-3">Chợ Việt – Nền Tảng Mua Bán Đồ Cũ C2C Hàng Đầu Của Người Việt</h5>
                <p>
                    <strong>Chợ Việt</strong> là nền tảng thương mại điện tử kết nối người mua và người bán đồ cũ trực tuyến theo mô hình <strong>C2C (Consumer to Consumer)</strong>. Với mục tiêu tạo ra một kênh trao đổi minh bạch, tiết kiệm và tiện lợi, Chợ Việt giúp mọi người dễ dàng <strong>đăng bán hoặc tìm mua những món đồ đã qua sử dụng</strong> một cách nhanh chóng.
                </p>
                <p>
                    Tại Chợ Việt, bạn có thể đăng bài hoàn toàn <strong>miễn phí</strong>, kèm hình ảnh thực tế và mô tả chi tiết sản phẩm. Tất cả tin đăng sẽ được <strong>kiểm duyệt nội dung trước khi hiển thị</strong> để đảm bảo chất lượng và tuân thủ chính sách cộng đồng.
                </p>
                <p>
                    Hệ thống phân loại sản phẩm rõ ràng hỗ trợ bạn dễ dàng tìm kiếm theo nhu cầu với các nhóm chính:
                    <ul class="mb-2">
                        <li><strong>Xe cộ:</strong> Xe máy, Ô tô, Xe điện, Phụ tùng xe</li>
                        <li><strong>Đồ điện tử:</strong> Laptop, Điện thoại, Máy tính bảng, Máy ảnh, Thiết bị thông minh</li>
                        <li><strong>Thời trang & Phụ kiện:</strong> Quần, Áo, Túi xách, Dép, Mũ</li>
                        <li><strong>Nội thất & Trang trí:</strong> Bàn ghế, Tủ kệ, Dụng cụ bếp, Dụng cụ trang trí</li>
                        <li><strong>Giải trí & Thể thao:</strong> Nhạc cụ, Đồ thể thao, Thiết bị chơi game</li>
                    </ul>
                </p>
                <p>
                    Ngoài việc mua bán, người dùng còn có thể <strong>trò chuyện trực tiếp qua hệ thống tin nhắn nội bộ</strong> để thương lượng giá, hỏi thêm thông tin hoặc hẹn gặp. Sau giao dịch, bạn có thể để lại <strong>đánh giá người bán</strong>, giúp xây dựng một cộng đồng giao dịch minh bạch, đáng tin cậy.
                </p>
                <p>
                    Đừng để những món đồ cũ phủ bụi – hãy để <strong>Chợ Việt</strong> giúp bạn biến chúng thành giá trị cho người khác. Rất đơn giản, bạn chỉ cần chụp hình, mô tả sản phẩm và đăng tin miễn phí.
                </p>
                <p>
                    Chợ Việt cũng sẽ sớm ra mắt <strong>blog chia sẻ kinh nghiệm</strong>, hướng dẫn mẹo chọn đồ cũ chất lượng, tư vấn giá cả và cập nhật xu hướng tiêu dùng xanh.
                </p>
                <p class="text-muted font-italic">💡 Đừng vứt bỏ – hãy biến món đồ cũ thành giá trị mới cùng Chợ Việt.</p>
            </div>
        </div>
    </div>
</div>
<!-- Blog nhỏ gọn kết thúc -->

<?php
    include_once("view/footer.php");
?>

<!-- phần khám phá danh mục -->
<script>
const showMoreBtn = document.getElementById('show-more-btn');
const collapseBtn = document.getElementById('collapse-btn');

if (showMoreBtn && collapseBtn) {
    showMoreBtn.addEventListener('click', function () {
        document.querySelectorAll('.category-item.d-none').forEach(item => item.classList.remove('d-none'));
        showMoreBtn.classList.add('d-none');
        collapseBtn.classList.remove('d-none');
    });

    collapseBtn.addEventListener('click', function () {
        document.querySelectorAll('.category-item').forEach((item, index) => {
            if (index >= 12) item.classList.add('d-none');
        });
        collapseBtn.classList.add('d-none');
        showMoreBtn.classList.remove('d-none');
        window.scrollTo({ top: document.getElementById('category-list').offsetTop - 100, behavior: 'smooth' });
    });
}
</script>

<!-- Phần sản phầm -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const showMoreBtn2 = document.getElementById('show-more-btn2');
    const collapseBtn2 = document.getElementById('collapse-btn2');
    const productItems = document.querySelectorAll('.product-item-row');
    let visibleCount = 18;
    const increment = 6;

    if (showMoreBtn2 && collapseBtn2) {
        showMoreBtn2.addEventListener('click', function () {
            const total = productItems.length;
            const nextVisible = Math.min(visibleCount + increment, total);

            productItems.forEach((item, index) => {
                if (index < nextVisible) item.classList.remove('d-none');
            });

            visibleCount = nextVisible;

            if (visibleCount >= total) {
                showMoreBtn2.classList.add('d-none');
                collapseBtn2.classList.remove('d-none');
            }
        });

        collapseBtn2.addEventListener('click', function () {
            productItems.forEach((item, index) => {
                item.classList.toggle('d-none', index >= 18);
            });
            visibleCount = 18;
            showMoreBtn2.classList.remove('d-none');
            collapseBtn2.classList.add('d-none');
            window.scrollTo({ top: document.getElementById('product-list').offsetTop - 100, behavior: 'smooth' });
        });
    }
});

</script>