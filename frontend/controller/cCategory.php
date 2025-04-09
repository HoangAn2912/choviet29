<?php
include_once "model/mCategory.php";

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

        return $data; // ✅ Không include view
    }
}
?>
