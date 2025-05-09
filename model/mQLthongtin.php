<?php
include_once("mConnect.php");
class qlthongtin{
    public function alluser(){
        $p = new Connect();
        $con = $p->connect();
        $sql = "select * from nguoi_dung";
        $kq = mysqli_query($con, $sql);
        $i = mysqli_num_rows($kq);
        if($i > 0)
		{
			while($r =mysqli_fetch_array($kq))
			{
				$id = $r['id'];
				$anh = $r['anh_dai_dien'];
				$ten = $r['ten_dang_nhap'];
				$email = $r['email'];
				$sdt = $r['so_dien_thoai'];
                $dc = $r['dia_chi'];
				$dl[]=array('id'=>$id,'anh_dai_dien'=>$anh,'ten_dang_nhap'=>$ten,'email'=>$email,'so_dien_thoai'=>$sdt,"dia_chi"=>$dc);
			}
			return $dl;
		}
    }
	public function oneuser($id){
		$p = new Connect();
        $con = $p->connect();
        $sql = "select * from nguoi_dung where id = '$id'";
        $kq = mysqli_query($con, $sql);
        $i = mysqli_num_rows($kq);
        if($i > 0)
		{
			while($r =mysqli_fetch_array($kq))
			{
				$id = $r['id'];
				$anh = $r['anh_dai_dien'];
				$ten = $r['ho_ten'];
				$email = $r['email'];
				$sdt = $r['so_dien_thoai'];
                $dc = $r['dia_chi'];
				$dl[]=array('id'=>$id,'anh_dai_dien'=>$anh,'ho_ten'=>$ten,'email'=>$email,'so_dien_thoai'=>$sdt,"dia_chi"=>$dc);
			}
			return $dl;
		}
	}
	public function updateuser($id, $hoten, $email, $sdt, $dc, $anh){
		$p = new Connect();
		$con = $p->connect();
	
		$sql = "UPDATE nguoi_dung SET ho_ten='$hoten',email='$email',so_dien_thoai='$sdt',dia_chi='$dc',anh_dai_dien='$anh',ngay_cap_nhat= NOW() 
				WHERE id='$id'";
	
		$kq = mysqli_query($con, $sql);
	
		return $kq;
	}
	public function insertUser($hoten, $email, $mk, $sdt, $dc, $anh) {
		$p = new Connect();
		$con = $p->connect();
	
		$sql = "INSERT INTO nguoi_dung 
				(ho_ten, email, mat_khau, so_dien_thoai, dia_chi, id_vai_tro, anh_dai_dien, ngay_tao, ngay_cap_nhat) 
				VALUES (?, ?, MD5(?), ?, ?, 2, ?, NOW(), NULL)";
	
		$stmt = $con->prepare($sql);
		if (!$stmt) {
			return false;
		}	
	
		$stmt->bind_param("ssssss", $hoten, $email, $mk, $sdt, $dc, $anh);
		$kq = $stmt->execute();
	
		$stmt->close();
		$con->close();
		return $kq;
	}
}
?>