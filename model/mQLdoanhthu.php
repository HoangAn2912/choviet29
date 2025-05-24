<?php
include_once("mConnect.php");
class qldoanhthu {
    // Get revenue data with pagination and filtering
    function getRevenueData($offset, $limit, $startDate = null, $endDate = null, $userId = null) {
        $con = new Connect();
        $p = $con->connect();
        
        // Simplified query - just get all products and calculate revenue
        $sql = "SELECT 
                sp.id as san_pham_id, 
                sp.tieu_de, 
                sp.gia, 
                nd.id as nguoi_dung_id,
                nd.ten_dang_nhap,
                sp.ngay_tao,
                11000 as phi_doanh_thu
                FROM san_pham sp
                JOIN nguoi_dung nd ON sp.id_nguoi_dung = nd.id
                WHERE 1=1";
        
        // Add date filter if provided
        if ($startDate && $endDate) {
            $sql .= " AND (sp.ngay_tao BETWEEN '$startDate' AND '$endDate 23:59:59')";
        }
        
        // Add user filter if provided
        if ($userId) {
            $sql .= " AND sp.id_nguoi_dung = '$userId'";
        }
        
        // Add order and limit
        $sql .= " ORDER BY sp.id LIMIT $offset, $limit";
        
        $rs = mysqli_query($p, $sql);
        
        $data = array();
        if ($rs) {
            while ($row = mysqli_fetch_array($rs)) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    // Count total revenue records for pagination
    function countTotalRevenue($startDate = null, $endDate = null, $userId = null) {
        $con = new Connect();
        $p = $con->connect();
        
        // Simplified count query
        $sql = "SELECT COUNT(*) as total FROM san_pham sp WHERE 1=1";
        
        // Add date filter if provided
        if ($startDate && $endDate) {
            $sql .= " AND (sp.ngay_tao BETWEEN '$startDate' AND '$endDate 23:59:59')";
        }
        
        // Add user filter if provided
        if ($userId) {
            $sql .= " AND sp.id_nguoi_dung = '$userId'";
        }
        
        $rs = mysqli_query($p, $sql);
        
        if ($rs) {
            $row = mysqli_fetch_assoc($rs);
            return $row['total'];
        }
        
        return 0;
    }
    
    // Get summary statistics
    function getRevenueSummary($startDate = null, $endDate = null) {
        $con = new Connect();
        $p = $con->connect();
        
        // Simplified summary query
        $sql = "SELECT 
                COUNT(*) as total_posts,
                COUNT(DISTINCT id_nguoi_dung) as total_users,
                COUNT(*) * 11000 as total_revenue
                FROM san_pham
                WHERE 1=1";
        
        // Add date filter if provided
        if ($startDate && $endDate) {
            $sql .= " AND (ngay_tao BETWEEN '$startDate' AND '$endDate 23:59:59')";
        }
        
        $rs = mysqli_query($p, $sql);
        
        $summary = array('total_posts' => 0, 'total_users' => 0, 'total_revenue' => 0);
        
        if ($rs) {
            $row = mysqli_fetch_assoc($rs);
            $summary['total_posts'] = $row['total_posts'] ?? 0;
            $summary['total_users'] = $row['total_users'] ?? 0;
            $summary['total_revenue'] = $row['total_revenue'] ?? 0;
        }
        
        return $summary;
    }
    
    // Get top users by revenue
    function getTopUsersByRevenue($limit = 5, $startDate = null, $endDate = null) {
        $con = new Connect();
        $p = $con->connect();
        
        // Simplified top users query
        $sql = "SELECT 
                nd.id,
                nd.ten_dang_nhap,
                COUNT(sp.id) as total_posts,
                COUNT(*) * 11000 as total_revenue
                FROM nguoi_dung nd
                JOIN san_pham sp ON nd.id = sp.id_nguoi_dung
                WHERE 1=1";
        
        // Add date filter if provided
        if ($startDate && $endDate) {
            $sql .= " AND (sp.ngay_tao BETWEEN '$startDate' AND '$endDate 23:59:59')";
        }
        
        $sql .= " GROUP BY nd.id
                ORDER BY total_revenue DESC
                LIMIT $limit";
        
        $rs = mysqli_query($p, $sql);
        
        $data = array();
        if ($rs) {
            while ($row = mysqli_fetch_array($rs)) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    // Get monthly revenue data for chart
    function getMonthlyRevenue($year = null) {
        $con = new Connect();
        $p = $con->connect();
        
        $currentYear = $year ? $year : date('Y');
        
        // Simplified monthly revenue query
        $sql = "SELECT 
                MONTH(ngay_tao) as month,
                COUNT(*) * 11000 as monthly_revenue
                FROM san_pham
                WHERE YEAR(ngay_tao) = '$currentYear'
                GROUP BY MONTH(ngay_tao)
                ORDER BY month";
        
        $rs = mysqli_query($p, $sql);
        
        $data = array();
        if ($rs) {
            while ($row = mysqli_fetch_array($rs)) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    // Export revenue data to CSV
    function exportRevenueData($startDate = null, $endDate = null, $userId = null) {
        $con = new Connect();
        $p = $con->connect();
        
        // Simplified export query
        $sql = "SELECT 
                sp.id as san_pham_id, 
                sp.tieu_de, 
                sp.gia, 
                nd.id as nguoi_dung_id,
                nd.ten_dang_nhap,
                nd.email,
                sp.ngay_tao,
                11000 as phi_doanh_thu
                FROM san_pham sp
                JOIN nguoi_dung nd ON sp.id_nguoi_dung = nd.id
                WHERE 1=1";
        
        // Add date filter if provided
        if ($startDate && $endDate) {
            $sql .= " AND (sp.ngay_tao BETWEEN '$startDate' AND '$endDate 23:59:59')";
        }
        
        // Add user filter if provided
        if ($userId) {
            $sql .= " AND sp.id_nguoi_dung = '$userId'";
        }
        
        $sql .= " ORDER BY sp.ngay_tao DESC";
        
        $rs = mysqli_query($p, $sql);
        
        $data = array();
        if ($rs) {
            while ($row = mysqli_fetch_array($rs)) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    // Get all users for filter dropdown
    function getAllUsers() {
        $con = new Connect();
        $p = $con->connect();
        
        $sql = "SELECT id, ten_dang_nhap FROM nguoi_dung ORDER BY ten_dang_nhap";
        $rs = mysqli_query($p, $sql);
        
        $data = array();
        if ($rs) {
            while ($row = mysqli_fetch_array($rs)) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
}
?>