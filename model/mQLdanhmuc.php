<?php
include_once("mConnect.php");

class mLoaiSanPham {
    private $conn;
    
    public function __construct() {
        $this->conn = new Connect();
        $this->conn = $this->conn->connect();
    }
    
    // Parent Category Methods
    
    // Get all parent categories
    public function getAllParentCategories() {
        $query = "SELECT * FROM loai_san_pham_cha ORDER BY ten_loai_san_pham_cha";
        $result = $this->conn->query($query);
        $data = array();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    // Get parent category by ID
    public function getParentCategoryById($id) {
        $id = $this->conn->real_escape_string($id);
        $query = "SELECT * FROM loai_san_pham_cha WHERE id_loai_san_pham_cha = '$id'";
        $result = $this->conn->query($query);
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    // Add new parent category
    public function addParentCategory($name) {
        $name = $this->conn->real_escape_string($name);
        $query = "INSERT INTO loai_san_pham_cha (ten_loai_san_pham_cha) VALUES ('$name')";
        
        if ($this->conn->query($query)) {
            return $this->conn->insert_id;
        }
        
        return false;
    }
    
    // Update parent category
    public function updateParentCategory($id, $name) {
        $id = $this->conn->real_escape_string($id);
        $name = $this->conn->real_escape_string($name);
        $query = "UPDATE loai_san_pham_cha SET ten_loai_san_pham_cha = '$name' WHERE id_loai_san_pham_cha = '$id'";
        
        return $this->conn->query($query);
    }
    
    // Delete parent category
    public function deleteParentCategory($id) {
        $id = $this->conn->real_escape_string($id);
        
        // First check if there are child categories
        $checkQuery = "SELECT COUNT(*) as count FROM loai_san_pham WHERE id_loai_san_pham_cha = '$id'";
        $result = $this->conn->query($checkQuery);
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            return false; // Cannot delete parent with children
        }
        
        $query = "DELETE FROM loai_san_pham_cha WHERE id_loai_san_pham_cha = '$id'";
        return $this->conn->query($query);
    }
    
    // Count child categories for a parent
    public function acountChildCategories($parentId) {
        $parentId = $this->conn->real_escape_string($parentId);
        $query = "SELECT COUNT(*) as count FROM loai_san_pham WHERE id_loai_san_pham_cha = '$parentId'";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        
        return $row['count'];
    }
    
    // Child Category Methods
    
    // Get all child categories with parent info
    public function getAllChildCategories() {
        $query = "SELECT c.*, p.ten_loai_san_pham_cha 
                 FROM loai_san_pham c 
                 LEFT JOIN loai_san_pham_cha p ON c.id_loai_san_pham_cha = p.id_loai_san_pham_cha 
                 ORDER BY p.ten_loai_san_pham_cha, c.ten_loai_san_pham";
        $result = $this->conn->query($query);
        $data = array();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    // Get paginated child categories with filters
    public function getPaginatedChildCategories($offset, $limit, $parentFilter = 'all', $searchTerm = '') {
        $query = "SELECT c.*, p.ten_loai_san_pham_cha 
                 FROM loai_san_pham c 
                 LEFT JOIN loai_san_pham_cha p ON c.id_loai_san_pham_cha = p.id_loai_san_pham_cha 
                 WHERE 1=1";
        
        // Apply parent filter
        if ($parentFilter != 'all') {
            $query .= " AND c.id_loai_san_pham_cha = '" . $this->conn->real_escape_string($parentFilter) . "'";
        }
        
        // Apply search filter
        if (!empty($searchTerm)) {
            $searchTerm = $this->conn->real_escape_string($searchTerm);
            $query .= " AND (c.ten_loai_san_pham LIKE '%$searchTerm%' OR p.ten_loai_san_pham_cha LIKE '%$searchTerm%')";
        }
        
        $query .= " ORDER BY p.ten_loai_san_pham_cha, c.ten_loai_san_pham LIMIT $offset, $limit";
        
        $result = $this->conn->query($query);
        $data = array();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    // Count child categories for pagination
    public function countChildCategories($parentFilter = 'all', $searchTerm = '') {
        $query = "SELECT COUNT(*) as total 
                FROM loai_san_pham c 
                LEFT JOIN loai_san_pham_cha p ON c.id_loai_san_pham_cha = p.id_loai_san_pham_cha 
                WHERE 1=1";
        
        // Apply parent filter
        if ($parentFilter != 'all') {
            $query .= " AND c.id_loai_san_pham_cha = '" . $this->conn->real_escape_string($parentFilter) . "'";
        }
        
        // Apply search filter
        if (!empty($searchTerm)) {
            $searchTerm = $this->conn->real_escape_string($searchTerm);
            $query .= " AND (c.ten_loai_san_pham LIKE '%$searchTerm%' OR p.ten_loai_san_pham_cha LIKE '%$searchTerm%')";
        }
        
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        
        return $row['total'];
    }
    
    // Get child categories by parent ID
    public function getChildCategoriesByParentId($parentId) {
        $parentId = $this->conn->real_escape_string($parentId);
        $query = "SELECT * FROM loai_san_pham WHERE id_loai_san_pham_cha = '$parentId' ORDER BY ten_loai_san_pham";
        $result = $this->conn->query($query);
        $data = array();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    // Get child category by ID
    public function getChildCategoryById($id) {
        $id = $this->conn->real_escape_string($id);
        $query = "SELECT c.*, p.ten_loai_san_pham_cha 
                 FROM loai_san_pham c 
                 LEFT JOIN loai_san_pham_cha p ON c.id_loai_san_pham_cha = p.id_loai_san_pham_cha 
                 WHERE c.id = '$id'";
        $result = $this->conn->query($query);
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    // Add new child category
    public function addChildCategory($name, $parentId) {
        $name = $this->conn->real_escape_string($name);
        $parentId = $this->conn->real_escape_string($parentId);
        $query = "INSERT INTO loai_san_pham (ten_loai_san_pham, id_loai_san_pham_cha) VALUES ('$name', '$parentId')";
        
        if ($this->conn->query($query)) {
            return $this->conn->insert_id;
        }
        
        return false;
    }
    
    // Update child category
    public function updateChildCategory($id, $name, $parentId) {
        $id = $this->conn->real_escape_string($id);
        $name = $this->conn->real_escape_string($name);
        $parentId = $this->conn->real_escape_string($parentId);
        $query = "UPDATE loai_san_pham SET ten_loai_san_pham = '$name', id_loai_san_pham_cha = '$parentId' WHERE id = '$id'";
        
        return $this->conn->query($query);
    }
    
    // Delete child category
    public function deleteChildCategory($id) {
        $id = $this->conn->real_escape_string($id);
        
        // Check if there are products using this category
        // This would require a products table with a category_id field
        // For now, we'll assume it's safe to delete
        
        $query = "DELETE FROM loai_san_pham WHERE id = '$id'";
        return $this->conn->query($query);
    }
    
    // Count products using a category
    public function countProductsInCategory($categoryId) {
        // This would require a products table with a category_id field
        // For now, we'll return 0
        return 0;
    }
    
    // Statistics Methods
    
    // Get category statistics
    public function getCategoryStats() {
        $query = "SELECT 
                    (SELECT COUNT(*) FROM loai_san_pham_cha) as total_parent_categories,
                    (SELECT COUNT(*) FROM loai_san_pham) as total_child_categories";
        
        $result = $this->conn->query($query);
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return array(
            'total_parent_categories' => 0,
            'total_child_categories' => 0
        );
    }
}
?>