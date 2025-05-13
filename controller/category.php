<?php
// xử lý danh mục con
include_once "model/mConnect.php";
$conn = (new Connect())->connect();

$idCha = isset($_GET['id_cha']) ? (int)$_GET['id_cha'] : 0;
$idCon = isset($_GET['id_con']) ? (int)$_GET['id_con'] : 0;

// Nếu có danh mục con, lọc theo id_con
if ($idCon > 0) {
    $sql = "SELECT * FROM san_pham WHERE id_loai_san_pham = $idCon";
}
// Nếu chỉ có danh mục cha, lọc theo các danh mục con của nó
elseif ($idCha > 0) {
    $sql = "SELECT sp.* FROM san_pham sp
            JOIN loai_san_pham lsp ON sp.id_loai_san_pham = lsp.id
            WHERE lsp.id_loai_san_pham_cha = $idCha";
}
// Nếu không có gì, hiển thị tất cả sản phẩm
else {
    $sql = "SELECT * FROM san_pham";
}

$result = mysqli_query($conn, $sql);
?>

<h2>Danh sách sản phẩm</h2>
<ul>
    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <li><?= htmlspecialchars($row['tieu_de']) ?> - <?= number_format($row['gia']) ?> VND</li>
    <?php endwhile; ?>
</ul>
