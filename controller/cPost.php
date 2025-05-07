<?php
include_once("model/mPost.php");

class cPost {
    public function dangTin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idLoaiSanPham = intval($_POST['id_loai_san_pham'] ?? 0);
            if ($idLoaiSanPham == 0) {
                header("Location: index.php?toast=" . urlencode("❌ Bạn chưa chọn danh mục sản phẩm!") . "&type=error");
                exit;
            }

            $tieuDe = trim($_POST['tieu_de'] ?? '');
            $gia = floatval($_POST['gia'] ?? 0);
            $moTa = trim($_POST['mo_ta'] ?? '');
            $idNguoiDang = $_SESSION['user_id'] ?? 0;

            if ($idNguoiDang == 0) {
                header("Location: index.php?toast=" . urlencode("❌ Bạn cần đăng nhập để đăng tin!") . "&type=error");
                exit;
            }

            $anhTenList = [];

            if (isset($_FILES['hinh_anh'])) {
                $total = count($_FILES['hinh_anh']['name']);
                for ($i = 0; $i < $total; $i++) {
                    $tmpName = $_FILES['hinh_anh']['tmp_name'][$i];
                    $fileName = $_FILES['hinh_anh']['name'][$i];

                    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                        header("Location: index.php?toast=" . urlencode("❌ Chỉ cho phép tải ảnh JPG hoặc PNG!") . "&type=error");
                        exit;
                    }

                    $newFileName = uniqid() . '.' . $ext;
                    $targetDir = "img/";
                    $targetFile = $targetDir . $newFileName;

                    if (move_uploaded_file($tmpName, $targetFile)) {
                        $anhTenList[] = $newFileName;
                    }
                }
            }

            if (count($anhTenList) < 2) {
                header("Location: index.php?toast=" . urlencode("❌ Bạn phải chọn ít nhất 2 ảnh để đăng tin.") . "&type=error");
                exit;
            }

            $hinhAnh = implode(",", $anhTenList);

            $model = new mPost();
            $soLuong = $model->demSoLuongTin($idNguoiDang);

            if ($soLuong >= 3) {
                $thongTin = $model->layThongTinNguoiDung($idNguoiDang);
                $soDu = intval($thongTin['so_du'] ?? 0);

                if ($soDu < 11000) {
                    header("Location: index.php?toast=" . urlencode("❌ Tài khoản không đủ 11.000đ để đăng tin.") . "&type=error");
                    exit;
                }
            }

            $result = $model->insertSanPham($tieuDe, $gia, $moTa, $hinhAnh, $idNguoiDang, $idLoaiSanPham);

            if ($result) {
                header("Location: index.php?toast=" . urlencode("🎉 Đăng tin thành công! Tin đang chờ duyệt.") . "&type=success");
                exit;
            } else {
                header("Location: index.php?toast=" . urlencode("❌ Đăng tin thất bại. Vui lòng thử lại!") . "&type=error");
                exit;
            }
        }
    }

    public function layDanhSachTinNguoiDung($userId) {
        $model = new mPost();
        return $model->layTatCaTinDangTheoNguoiDung($userId);
    }

    public function layTinDang() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
            return;
        }

        $idNguoiDung = $_SESSION['user_id'];
        $status = $_GET['status'] ?? 'dang_ban';

        $m = new mPost();
        $data = $m->getTinDangByStatus($idNguoiDung, $status);

        echo json_encode(['status' => 'success', 'data' => $data]);
    }

    public function capNhatTrangThaiBan() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
            return;
        }

        $idTin = intval($_POST['id']);
        $loai = $_POST['loai'];

        if (!in_array($loai, ['da_ban', 'da_an'])) {
            echo json_encode(['status' => 'error', 'message' => 'Trạng thái không hợp lệ']);
            return;
        }

        $m = new mPost();
        $ok = $m->updateTrangThaiBan($idTin, $loai);

        echo json_encode(['status' => $ok ? 'success' : 'error', 'message' => $ok ? '' : 'Không cập nhật được']);
    }

    public function demSoLuongTheoTrangThai($userId) {
        $model = new mPost();
        return $model->demSoLuongTheoTrangThai($userId);
    }

    public function layThongTinNguoiDung($userId) {
        $model = new mPost();
        return $model->layThongTinNguoiDung($userId);
    }

    public function getBadgeColor($status) {
        switch($status) {
            case 'dang_ban': return 'success';
            case 'da_ban': return 'secondary';
            case 'cho_duyet': return 'warning';
            case 'tu_choi': return 'danger';
            case 'da_an': return 'dark';
        }
    }

    public function getNoProductText($status) {
        switch($status) {
            case 'dang_ban': return 'Chưa có sản phẩm đang bán.';
            case 'da_ban': return 'Chưa có sản phẩm đã bán.';
            case 'cho_duyet': return 'Chưa có sản phẩm chờ duyệt.';
            case 'tu_choi': return 'Chưa có sản phẩm bị từ chối.';
            case 'da_an': return 'Chưa có sản phẩm đã ẩn.';
            default: return 'Chưa có sản phẩm.';
        }
    }

    public function suaTin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_GET['id'] ?? 0);
            $idLoaiSanPham = intval($_POST['id_loai_san_pham'] ?? 0);
            $tieuDe = trim($_POST['tieu_de'] ?? '');
            $gia = floatval($_POST['gia'] ?? 0);
            $moTa = trim($_POST['mo_ta'] ?? '');
            $idNguoiDang = $_SESSION['user_id'] ?? 0;

            $model = new mPost();
            $tinCu = $model->laySanPhamTheoId($id);
            if (!$tinCu || $tinCu['id_nguoi_dung'] != $idNguoiDang) {
                header("Location: index.php?toast=" . urlencode("❌ Không tìm thấy tin!") . "&type=error");
                exit;
            }

            $anhTenList = explode(',', $tinCu['hinh_anh']);
            if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['name'][0] != '') {
                $anhTenList = [];
                foreach ($_FILES['hinh_anh']['tmp_name'] as $i => $tmpName) {
                    $fileName = $_FILES['hinh_anh']['name'][$i];
                    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    if (!in_array($ext, ['jpg', 'jpeg', 'png'])) continue;
                    $newName = uniqid() . '.' . $ext;
                    if (move_uploaded_file($tmpName, "img/$newName")) $anhTenList[] = $newName;
                }
                if (count($anhTenList) < 2) {
                    header("Location: index.php?toast=" . urlencode("❌ Vui lòng chọn ít nhất 2 ảnh hợp lệ (.jpg, .png)!") . "&type=error");
                    exit;
                }
            }

            $hinhAnh = implode(',', $anhTenList);
            $ok = $model->capNhatSanPham($id, $tieuDe, $gia, $moTa, $hinhAnh, $idLoaiSanPham, $idNguoiDang);

            if ($ok) {
                header("Location: index.php?quan-ly-tin&toast=" . urlencode("✔️ Đã cập nhật và chuyển về chờ duyệt!") . "&type=success");
            } else {
                header("Location: index.php?quan-ly-tin&toast=" . urlencode("❌ Cập nhật thất bại!") . "&type=error");
            }
        }
    }

    public function laySanPhamTheoId($id) {
        $model = new mPost();
        return $model->laySanPhamTheoId($id);
    }

    public function dayTin($idTin) {
        $idTin = intval($idTin);
        $idNguoiDung = $_SESSION['user_id'] ?? 0;
        $model = new mPost();

        $soDu = $model->laySoDuNguoiDung($idNguoiDung);
        if ($soDu < 11000) {
            header("Location: index.php?quan-ly-tin&toast=" . urlencode("⚠️ Bạn không đủ tiền để đẩy tin. Vui lòng nạp thêm.") . "&type=warning");
            return;
        }

        $ok = $model->dayTin($idTin, $idNguoiDung);
        if ($ok) {
            header("Location: index.php?quan-ly-tin&toast=" . urlencode("🚀 Đã đẩy tin thành công!") . "&type=success");
        } else {
            header("Location: index.php?quan-ly-tin&toast=" . urlencode("❌ Có lỗi xảy ra khi đẩy tin!") . "&type=error");
        }
    }
}
?>
