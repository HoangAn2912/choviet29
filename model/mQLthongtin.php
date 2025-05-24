<?php
include_once("mConnect.php");
class qlthongtin{
    // Get all users with status information
    function selectAllUser() {
        $con = new Connect();
        $p = $con->connect();
        
        $sql = "SELECT * FROM nguoi_dung order by id asc";
        $rs = mysqli_query($p, $sql);
        
        $data = array();
        while ($row = mysqli_fetch_array($rs)) {
            $data[] = $row;
        }
        
        return $data;
    }
    
    // Get one user by ID
    function selectOneUser($id) {
        $con = new Connect();
        $p = $con->connect();
        
        $sql = "SELECT * FROM nguoi_dung WHERE id = '$id'";
        $rs = mysqli_query($p, $sql);
        
        $data = array();
        while ($row = mysqli_fetch_array($rs)) {
            $data[] = $row;
        }
        
        return $data;
    }
    
    // Update user information
    function updateUser($id, $hoten, $email, $sdt, $dc, $anh, $vai_tro) {
        $con = new Connect();
        $p = $con->connect();
        
        $sql = "UPDATE nguoi_dung SET 
                ten_dang_nhap = '$hoten', 
                email = '$email', 
                so_dien_thoai = '$sdt', 
                dia_chi = '$dc', 
                anh_dai_dien = '$anh',
                id_vai_tro = '$vai_tro',
                ngay_cap_nhat = NOW()
                WHERE id = '$id'";
                
        $rs = mysqli_query($p, $sql);
        
        return $rs;
    }
    
    // Update user with password
    function updateUserWithPassword($id, $hoten, $email, $mat_khau, $sdt, $dc, $anh, $vai_tro) {
        $con = new Connect();
        $p = $con->connect();
        
        $sql = "UPDATE nguoi_dung SET 
                ten_dang_nhap = '$hoten', 
                email = '$email',
                mat_khau = '$mat_khau', 
                so_dien_thoai = '$sdt', 
                dia_chi = '$dc', 
                anh_dai_dien = '$anh',
                id_vai_tro = '$vai_tro',
                ngay_cap_nhat = NOW()
                WHERE id = '$id'";
                
        $rs = mysqli_query($p, $sql);
        
        return $rs;
    }
    
    // Disable user (set trang_thai_hd = 0)
    function disableUser($id) {
        $con = new Connect();
        $p = $con->connect();
        
        $sql = "UPDATE nguoi_dung SET 
                trang_thai_hd = 0,
                ngay_cap_nhat = NOW()
                WHERE id = '$id'";
                
        $rs = mysqli_query($p, $sql);
        
        return $rs;
    }
    
    // Restore user (set trang_thai_hd = 1)
    function restoreUser($id) {
        $con = new Connect();
        $p = $con->connect();
        
        $sql = "UPDATE nguoi_dung SET 
                trang_thai_hd = 1,
                ngay_cap_nhat = NOW()
                WHERE id = '$id'";
                
        $rs = mysqli_query($p, $sql);
        
        return $rs;
    }
    
	// Get paginated users with optional status filter
    function selectPaginatedUsers($offset, $limit, $statusFilter = 'all') {
        $con = new Connect();
        $p = $con->connect();
        
        $whereClause = "WHERE id_vai_tro = 2";
        
        if ($statusFilter === 'active') {
            $whereClause .= " AND trang_thai_hd = 1";
        } else if ($statusFilter === 'disabled') {
            $whereClause .= " AND trang_thai_hd = 0";
        }
        
        $sql = "SELECT * FROM nguoi_dung {$whereClause} ORDER BY id LIMIT {$offset}, {$limit}";
        $rs = mysqli_query($p, $sql);
        
        $data = array();
        while ($row = mysqli_fetch_array($rs)) {
            $data[] = $row;
        }
        
        return $data;
    }
    
    // Count total users with optional status filter
    function countTotalUsers($statusFilter = 'all') {
        $con = new Connect();
        $p = $con->connect();
        
        $whereClause = "WHERE id_vai_tro = 2";
        
        if ($statusFilter === 'active') {
            $whereClause .= " AND trang_thai_hd = 1";
        } else if ($statusFilter === 'disabled') {
            $whereClause .= " AND trang_thai_hd = 0";
        }
        
        $sql = "SELECT COUNT(*) as total FROM nguoi_dung {$whereClause}";
        $rs = mysqli_query($p, $sql);
        $row = mysqli_fetch_assoc($rs);
        
        return $row['total'];
    }

	public function insertuser($hoten, $email, $mk, $sdt, $dc, $anh) {
		$p = new Connect();
		$con = $p->connect();
		$sql = "INSERT INTO nguoi_dung 
				(ten_dang_nhap, email, mat_khau, so_dien_thoai, dia_chi, id_vai_tro, anh_dai_dien, ngay_tao, ngay_cap_nhat, trang_thai_hd) 
				VALUES (?, ?, ?, ?, ?, 2, ?, NOW(), NULL, 1)"	;
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