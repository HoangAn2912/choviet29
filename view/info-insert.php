    <?php
    include_once("controller/cQLthongtin.php");

    $message = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $p = new cqlthongtin();
    
        $hoten = $_POST['ho_ten'];
        $email = $_POST['email'];
        $mk = md5($_POST['mat_khau']);
        $sdt = $_POST['so_dien_thoai'];
        $dc = $_POST['dia_chi'];
    
        $anh = 'default.jpg';

        if (isset($_FILES['anh_dai_dien']) && $_FILES['anh_dai_dien']['error'] === 0) {
            if ($_FILES['anh_dai_dien']['name']) {
                $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/project/img/';
                $ext = pathinfo($_FILES['anh_dai_dien']['name'], PATHINFO_EXTENSION);
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array(strtolower($ext), $allowed_extensions)) {
                    $ten_file = $sdt;
                    $file_name = $ten_file . '.' . $ext;

                    $target_path = $upload_dir . $file_name;

                    if (move_uploaded_file($_FILES['anh_dai_dien']['tmp_name'], $target_path)) {
                        $anh = $file_name;
                    } else {
                        echo "Có lỗi trong việc upload file.";
                    }
                } else {
                    echo "File ảnh không hợp lệ. Vui lòng chọn file jpg, png, hoặc gif.";
                }
            } else {
                echo "Chưa chọn ảnh đại diện.";
            }
        }
    
        $result = $p->getinsertuser($hoten, $email, $mk, $sdt, $dc, $anh);
    
        if ($result) {
            header("Location: /project/ad/taikhoan");
            exit();
        } else {
            $message = '<div class="alert alert-danger">Không thể thêm người dùng. Vui lòng thử lại.</div>';
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thêm Người Dùng Mới</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/project/css/admin-them.css">
    </head>

    <body>
    <div class="container">
        <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thêm người dùng mới</h3>
            </div>
            <div class="card-body">
                <?php echo $message; ?>
                
                <form method="POST" action="" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label for="ho_ten" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ho_ten" name="ho_ten" required>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label for="mat_khau" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="mat_khau" name="mat_khau" required>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai">
                    </div>
                    </div>
                </div>

                <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="anh_dai_dien" class="form-label">Ảnh đại diện</label>
                        <input type="file" class="form-control" id="anh_dai_dien" name="anh_dai_dien">
                        <small class="text-muted">Không chọn ảnh sẽ dùng ảnh mặc định</small>
                    </div>
                </div>
                </div>

                <div class="mb-3">
                    <label for="dia_chi" class="form-label">Địa chỉ</label>
                    <textarea class="form-control" id="dia_chi" name="dia_chi" rows="3"></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="/project/ad/taikhoan" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Thêm mới
                    </button>
                </div>
                </form>
            </div>
            </div>
        </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>