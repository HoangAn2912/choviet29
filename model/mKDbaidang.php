<?php
include_once("mConnect.php");

class kdbaidang {
    public function allbaidang() {
        $p = new Connect();
        $con = $p->connect();

        $sql = "SELECT * FROM san_pham sp join nguoi_dung nd on sp.id_nguoi_dung = nd.id 
                                        join loai_san_pham lsp on sp.id_loai_san_pham = lsp.id";
        $kq = mysqli_query($con, $sql);
        $i = mysqli_num_rows($kq);

        if ($i > 0) {
            while ($r = mysqli_fetch_array($kq)) {
                $id = $r['id'];
                $ten_nguoi_ban = $r['ten_dang_nhap'];
                $ten_loai_san_pham = $r['ten_loai_san_pham'];
                $tieu_de = $r['tieu_de'];
                $mo_ta = $r['mo_ta'];
                $gia = $r['gia'];
                $hinh = $r['hinh_anh'];
                $trang_thai = $r['trang_thai'];
                $trang_thai_ban = $r['trang_thai_ban'];
                $ngay_tao = $r['ngay_tao'];
                $ngay_cap_nhat = $r['ngay_cap_nhat'];
                $ghi_chu = $r['ghi_chu'];

                $dl[] = array(
                    'id' => $id,
                    'ho_ten' => $ten_nguoi_ban,
                    'ten_loai_san_pham' => $ten_loai_san_pham,
                    'tieu_de' => $tieu_de,
                    'mo_ta' => $mo_ta,
                    'gia' => $gia,
                    'hinh_anh' => $hinh,
                    'trang_thai' => $trang_thai,
                    'trang_thai_ban' => $trang_thai_ban,
                    'ngay_tao' => $ngay_tao,
                    'ngay_cap_nhat' => $ngay_cap_nhat,
                    'ghi_chu' => $ghi_chu
                );
            }
            return $dl;
        } else {
            return null;
        }
    }
    public function onebaidang($id){
        $p = new Connect();
        $con = $p->connect();

        $sql = "SELECT * FROM san_pham sp join nguoi_dung nd on sp.id_nguoi_dung = nd.id 
                join loai_san_pham lsp on sp.id_loai_san_pham = lsp.id where sp.id = '$id'";
        $kq = mysqli_query($con, $sql);
        $i = mysqli_num_rows($kq);

        if ($i > 0) {
            while ($r = mysqli_fetch_array($kq)) {
                $id = $r['id'];
                $ten_nguoi_ban = $r['ten_dang_nhap'];
                $ten_loai_san_pham = $r['ten_loai_san_pham'];
                $tieu_de = $r['tieu_de'];
                $mo_ta = $r['mo_ta'];
                $gia = $r['gia'];
                $hinh = $r['hinh_anh'];
                $trang_thai = $r['trang_thai'];
                $trang_thai_ban = $r['trang_thai_ban'];
                $ngay_tao = $r['ngay_tao'];
                $ngay_cap_nhat = $r['ngay_cap_nhat'];
                $ghi_chu = $r['ghi_chu'];

                $dl[] = array(
                    'id' => $id,
                    'ho_ten' => $ten_nguoi_ban,
                    'ten_loai_san_pham' => $ten_loai_san_pham,
                    'tieu_de' => $tieu_de,
                    'mo_ta' => $mo_ta,
                    'gia' => $gia,
                    'hinh_anh' => $hinh,
                    'trang_thai' => $trang_thai,
                    'trang_thai_ban' => $trang_thai_ban,
                    'ngay_tao' => $ngay_tao,
                    'ngay_cap_nhat' => $ngay_cap_nhat,
                    'ghi_chu' => $ghi_chu
                );
            }
            return $dl;
        } else {
            return null;
        }
    }
    public function duyetBai($id) {
        $p = new Connect();
        $conn = $p->connect();
        $query = "UPDATE san_pham SET trang_thai = 'Đã duyệt' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
    
    public function tuChoiBai($id, $ghi_chu) {
        $p = new Connect();
        $conn = $p->connect();
        $query = "UPDATE san_pham SET trang_thai = 'Từ chối duyệt', ghi_chu = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $ghi_chu, $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
}
?>