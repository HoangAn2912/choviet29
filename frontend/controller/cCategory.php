<?php
include_once "frontend/model/mCategory.php";

class cCategory {
    public function index() {
        $mCategory = new mCategory();
        $categories = $mCategory->layDanhMuc();

        $data = [];
        foreach ($categories as $row) {
            $idCha = $row['id_cha'];
            if (!isset($data[$idCha])) {
                $data[$idCha] = [
                    'ten_cha' => $row['ten_cha'],
                    'con' => []
                ];
            }
            if ($row['id_con'] != null) {
                $data[$idCha]['con'][] = [
                    'id_con' => $row['id_con'],
                    'ten_con' => $row['ten_con']
                ];
            }
        }

        return $data;
    }

    public function getProductsByCategory() {
        if (isset($_GET['id_loai'])) {
            $id_loai = $_GET['id_loai'];
            $model = new mCategory();
            $products = $model->getProductsByCategoryId($id_loai);
            header('Content-Type: application/json');
            echo json_encode($products);
        }
    }
}

if (isset($_GET['action'])) {
    $controller = new cCategory();
    switch ($_GET['action']) {
        case 'getProductsByCategory':
            $controller->getProductsByCategory();
            break;
    }
}
?>
