<?php
    include_once("controller/cQLthongtin.php");
    $p = new cqlthongtin();
    
    if (isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
    }
    if (isset($_SESSION['role'])) {
        $idrole = $_SESSION['role'];
    }
    // Check if ID is provided

    $user = $p->getoneuser($id);
    
    // Check if user exists
    if (!$user) {
        header("Location: /project/index.php");
        exit();
    }
    
    $message = '';
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $hoten = $_POST['ten_dang_nhap'];
        $email = $_POST['email'];
        $sdt = $_POST['so_dien_thoai'];
        $dc = $_POST['dia_chi'];
        $vt = $_POST['id_vai_tro'];

        $anh = $_POST['anh_dai_dien_cu']; // mặc định giữ ảnh cũ

        if (isset($_FILES['anh_dai_dien']) && $_FILES['anh_dai_dien']['error'] === 0) {
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/project/img/";
            $imageFileType = strtolower(pathinfo($_FILES["anh_dai_dien"]["name"], PATHINFO_EXTENSION));
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageFileType, $allowed_types)) {
                $unique_name = uniqid('user_', true) . "." . $imageFileType;
                $target_file = $target_dir . $unique_name;

                if (move_uploaded_file($_FILES["anh_dai_dien"]["tmp_name"], $target_file)) {
                    $anh = $unique_name;
                }
            }
        }

        // Xử lý mật khẩu
        if (!empty($_POST['mat_khau'])) {
            $mat_khau = md5($_POST['mat_khau']);
            $result = $p->getupdateuser_with_password($id, $hoten, $email, $mat_khau, $sdt, $dc, $anh, $vt);
        } else {
            $result = $p->getupdateuser($id, $hoten, $email, $sdt, $dc, $anh, $vt);
        }

        if ($result) {
            header("Location: /project/ad");
            exit();
        } else {
            $message = '<div class="alert alert-danger">Cập nhật thất bại!. Vui lòng thử lại.</div>';
        }
    }

    // Function to safely output data
    function e($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/project/css/infoad.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="bi bi-person-fill me-2"></i>Thông tin cá nhân
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <?php foreach($user as $u): ?>
                        <div class="avatar-container text-center">
                            <img src="/project/img/<?php echo e($u['anh_dai_dien']); ?>" alt="Avatar" class="avatar-img" />
                            <div class="user-info">
                                <h4 class="mb-0"><?php echo e($u['ten_dang_nhap']); ?></h4>
                                <span class="user-role">Admin</span>
                            </div>
                        </div>
                        
                        <form method="POST" action="" class="needs-validation" enctype="multipart/form-data" novalidate>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ten_dang_nhap" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="ten_dang_nhap" name="ten_dang_nhap" 
                                               value="<?php echo e($u['ten_dang_nhap']); ?>" required>
                                        <div class="invalid-feedback">
                                            Vui lòng nhập họ và tên.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo e($u['email']); ?>" required>
                                        <div class="invalid-feedback">
                                            Vui lòng nhập email hợp lệ.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="mat_khau" class="form-label">Mật khẩu mới</label>
                                        <input type="password" class="form-control" id="mat_khau" name="mat_khau" 
                                               placeholder="Để trống nếu không đổi mật khẩu">
                                        <div class="form-text">Để trống nếu không muốn thay đổi mật khẩu.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                                        <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai" 
                                               value="<?php echo e($u['so_dien_thoai']); ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <input type="hidden" name="id_vai_tro" value="<?php echo $u['id_vai_tro']; ?>">
                                        <label for="id_vai_tro" class="form-label">Vai trò <span class="text-danger">*</span></label>
                                        <select class="form-select" id="id_vai_tro" name="id_vai_tro" disabled>
                                            <option value="2" <?php echo $u['id_vai_tro'] == 2 ? 'selected' : ''; ?>>Người dùng</option>
                                            <option value="1" <?php echo $u['id_vai_tro'] == 1 ? 'selected' : ''; ?>>Admin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="anh_dai_dien" class="form-label">Ảnh đại diện</label>
                                        <input type="file" class="form-control" id="anh_dai_dien" name="anh_dai_dien">
                                        <input type="hidden" name="anh_dai_dien_cu" value="<?php $u['anh_dai_dien']; ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="dia_chi" class="form-label">Địa chỉ</label>
                                <textarea class="form-control" id="dia_chi" name="dia_chi" rows="3"><?php echo e($u['dia_chi']); ?></textarea>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>Cập nhật
                                </button>
                            </div>
                        </form>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Form validation script -->
    <script>
    (function () {
        'use strict'
        
        // Fetch all forms we want to apply validation styles to
        var forms = document.querySelectorAll('.needs-validation')
        
        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    
                    form.classList.add('was-validated')
                }, false)
            })
    })()
    </script>
</body>
</html>