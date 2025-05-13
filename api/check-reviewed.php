<?php
require_once("../model/mReview.php");
$mReview = new mReview();

$from = intval($_GET['from'] ?? 0);
$to = intval($_GET['to'] ?? 0);
$id_san_pham = intval($_GET['id_san_pham'] ?? 0);

$reviewed = $mReview->daDanhGia($from, $to, $id_san_pham);
echo json_encode(['reviewed' => $reviewed]);
