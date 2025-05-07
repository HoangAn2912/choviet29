<?php
require_once 'model/mReview.php';

class cReview {
    public function getReviewsBySeller($sellerId) {
        $model = new mReview();
        return $model->getReviewsBySellerId($sellerId);
    }
}
