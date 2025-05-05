<?php
    include_once("controller/cQLthongtin.php");
    $p = new cqlthongtin();
    if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
    }
    $id = $_GET['id'];
    $user = $p->getoneuser($id);
    if (!$user) {
    header("Location: index.php");
    exit();
    }
    $message = '';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hoten = $_POST['ho_ten'];
    $email = $_POST['email'];
    $sdt = $_POST['so_dien_thoai'];
    $dc = $_POST['dia_chi'];
    $anh = $_POST['anh_dai_dien'] ?: $u['anh_dai_dien'];

    // Only update password if provided
    $mat_khau = !empty($_POST['mat_khau']) ? md5($_POST['mat_khau']) : $u['mat_khau'];

    $result = $p->getupdateuser($id, $hoten, $email, $sdt, $dc, $anh);

    if ($result) {
        header("Location: /project/ad/taikhoan");
        exit();
    } else {
        $message = '<div class="alert alert-danger">Không thể cập nhật người dùng. Vui lòng thử lại.</div>';
    }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Chỉnh Sửa Người Dùng</title>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="/project/css/admin-sua.css">
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
        <div class="card-header">
            <h3 class="card-title">Chỉnh sửa người dùng</h3>
        </div>
        <div class="card-body">
            <?php echo $message; ?>
            <?php foreach($user as $u) ?>
            <div class="text-center mb-4">
            <img src="/project/img/<?php echo $u['anh_dai_dien']; ?>" alt="Avatar" class="avatar-img"/>
            <h5><?php echo $u['ho_ten']; ?></h5>
            </div>
            
            <form method="POST" action="">
            <div class="row mb-3">
                <div class="col-md-6">
                <div class="mb-3">
                    <label for="ho_ten" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="ho_ten" name="ho_ten" value="<?php echo $u['ho_ten']; ?>" required>
                </div>
                </div>
                <div class="col-md-6">
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $u['email']; ?>" required>
                </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                <div class="mb-3">
                    <label for="mat_khau" class="form-label">Mật khẩu mới</label>
                    <input type="password" class="form-control" id="mat_khau" name="mat_khau" placeholder="Để trống nếu không đổi mật khẩu">
                </div>
                </div>
                <div class="col-md-6">
                <div class="mb-3">
                    <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                    <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai" value="<?php echo $u['so_dien_thoai']; ?>">
                </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                <div class="mb-3">
                    <label for="id_vai_tro" class="form-label">Vai trò <span class="text-danger">*</span></label>
                    <select class="form-select" id="id_vai_tro" name="id_vai_tro" required>
                    <option value="2" <?php echo ($u['id_vai_tro'] == 2); ?>>Người dùng</option>
                    <option value="1" <?php echo ($u['id_vai_tro'] == 1); ?>>Admin</option>
                    </select>
                </div>
                </div>
                <div class="col-md-6">
                <div class="mb-3">
                    <label for="anh_dai_dien" class="form-label">Ảnh đại diện</label>
                    <input type="text" class="form-control" id="anh_dai_dien" name="anh_dai_dien" value="<?php echo $u['anh_dai_dien']; ?>" placeholder="Tên file ảnh">
                </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="dia_chi" class="form-label">Địa chỉ</label>
                <textarea class="form-control" id="dia_chi" name="dia_chi" rows="3"><?php echo $u['dia_chi']; ?></textarea>
            </div>
            <div class="d-flex justify-content-between">
                <a href="/project/ad/taikhoan" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
                </a>
                <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-2"></i>Cập nhật
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