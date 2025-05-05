<?php
include_once("controller/cQLthongtin.php");
$p = new cqlthongtin();
$data = $p->getalluser();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Majestic Admin Pro</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../admin/src/assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../admin/src/assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../admin/src/assets/css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../admin/src/assets/images/favicon.ico" />
  <style>
    .btn a{
      text-decoration: none;
      color: #ffffff;
    }
  </style>
</head>

<body>
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title">Quản lý thông tin người dùng</h3>
        <button type="button" class="btn btn-primary btn-lg">
          <a href="?them">  
            <i class="mdi mdi-account"></i> Thêm người dùng mới
          </a>
        </button>
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th>Avata</th>
                <th>ID</th>
                <th>Họ và tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php
                foreach($data as $u)
                echo '<tr>
                  <td class="py-1">
                    <img src="../img/'.$u['anh_dai_dien'].'" alt="image"/>
                  </td>
                  <td>'.$u['id'].'</td>
                  <td>'.$u['ho_ten'].'</td>
                  <td>'.$u['email'].'</td>
                  <td>'.$u['so_dien_thoai'].'</td>
                  <td>'.$u['dia_chi'].'</td>
                  <td>
                    <button type="button" class="btn btn-info">
                      <a href="?sua&id='.$u['id'].'">Sửa</a>
                    </button>
                    <button type="button" class="btn btn-danger">
                      <a href="?xoa&idx'.$u['id'].'">Xóa</a>
                    </button>
                  </td>
                </tr>';
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
