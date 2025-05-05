<?php
include_once("model/mQLthongtin.php");
class cqlthongtin {
    public function getalluser() {
        $p = new qlthongtin();
        return $p->alluser();
    }
    public function getoneuser($id){
        $p = new qlthongtin();
        return $p->oneuser($id);
    }
    // public function getupdateuser($id, $hoten, $email, $sdt, $dc, $anh, $ngayupdate){
    //     if (isset($_POST['btn_update'])) {
    //         echo $hoten;
    //         $id = $_POST['id'];
    //         $hoten = $_POST['ho_ten'];
    //         $email = $_POST['email'];
    //         $sdt = $_POST['so_dien_thoai'];
    //         $dc = $_POST['dia_chi'];
    //         $ngayupdate = date("Y-m-d H:i:s");
        
    //         // Xử lý ảnh nếu có upload
    //         if (isset($_FILES['anh_dai_dien']) && $_FILES['anh_dai_dien']['error'] == 0) {
    //             $anh = "uploads/" . basename($_FILES['anh_dai_dien']['name']);
    //             move_uploaded_file($_FILES['anh_dai_dien']['tmp_name'], $anh);
    //         } else {
    //             $anh = $_POST['anh_dai_dien_old']; // ảnh cũ nếu không upload mới
    //         }
        
    //         $p = new qlthongtin();
    //         $kq = $p->updateuser($id, $hoten, $email, $sdt, $dc, $anh, $ngayupdate);
        
    //         if ($kq) {
    //             echo "<script>alert('Cập nhật thành công!'); window.location='danhsachnguoidung.php';</script>";
    //         } else {
    //             echo "<script>alert('Cập nhật thất bại!'); history.back();</script>";
    //         }
    //     }
    // }
    public function getinsertuser($hoten, $email, $mk, $sdt, $dc, $anh) {
        $p = new qlthongtin();
        return $p->insertuser($hoten, $email, $mk, $sdt, $dc, $anh);
    }
    public function getupdateuser($id, $hoten, $email, $sdt, $dc, $anh){
        $p = new qlthongtin();
        return $p->updateuser($id, $hoten, $email, $sdt, $dc, $anh);
    }
}
?>