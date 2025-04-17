<?php
require_once 'model/mReview.php';

class cReview {
    public function getReviewsBySeller() {
        if (!isset($_SESSION['user_id'])) {
            return []; // Không đăng nhập thì không có đánh giá
        }

        $sellerId = $_SESSION['user_id'];
        $model = new mReview();
        return $model->getReviewsBySellerId($sellerId);
    }
}
