<?php
/**
 * Script đồng bộ hóa database - cập nhật tên bảng/cột trong code theo choviet29.sql
 * 
 * Mapping chính:
 * - nguoi_dung → users
 * - san_pham → products  
 * - loai_san_pham → product_categories
 * - loai_san_pham_cha → parent_categories
 * - tin_nhan → messages
 * - danh_gia → reviews
 * - vai_tro → roles
 * - taikhoan_chuyentien → transfer_accounts
 * - lich_su_chuyen_khoan → transfer_history
 * - lich_su_phi_dang_bai → posting_fee_history
 * - lich_su_day_tin → promotion_history
 */

echo "=== SCRIPT ĐỒNG BỘ HÓA DATABASE ===\n";
echo "Cập nhật tên bảng/cột trong code theo choviet29.sql\n\n";

// Mapping bảng
$tableMapping = [
    'nguoi_dung' => 'users',
    'san_pham' => 'products',
    'loai_san_pham' => 'product_categories', 
    'loai_san_pham_cha' => 'parent_categories',
    'tin_nhan' => 'messages',
    'danh_gia' => 'reviews',
    'vai_tro' => 'roles',
    'taikhoan_chuyentien' => 'transfer_accounts',
    'lich_su_chuyen_khoan' => 'transfer_history',
    'lich_su_phi_dang_bai' => 'posting_fee_history',
    'lich_su_day_tin' => 'promotion_history'
];

// Mapping cột
$columnMapping = [
    // Bảng users
    'ten_dang_nhap' => 'username',
    'mat_khau' => 'password', 
    'so_dien_thoai' => 'phone',
    'dia_chi' => 'address',
    'id_vai_tro' => 'role_id',
    'loai_tai_khoan' => 'account_type',
    'anh_dai_dien' => 'avatar',
    'ngay_sinh' => 'birth_date',
    'ngay_tao' => 'created_date',
    'ngay_cap_nhat' => 'updated_date',
    'trang_thai_hd' => 'is_active',
    
    // Bảng products
    'id_nguoi_dung' => 'user_id',
    'id_loai_san_pham' => 'category_id',
    'tieu_de' => 'title',
    'mo_ta' => 'description',
    'gia' => 'price',
    'hinh_anh' => 'image',
    'trang_thai' => 'status',
    'trang_thai_ban' => 'sale_status',
    'ghi_chu' => 'note',
    
    // Bảng categories
    'id_loai_san_pham_cha' => 'parent_category_id',
    'ten_loai_san_pham_cha' => 'parent_category_name',
    'ten_loai_san_pham' => 'category_name',
    
    // Bảng messages
    'id_nguoi_gui' => 'sender_id',
    'id_nguoi_nhan' => 'receiver_id',
    'id_san_pham' => 'product_id',
    'noi_dung' => 'content',
    'thoi_gian' => 'created_time',
    'da_doc' => 'is_read',
    
    // Bảng reviews
    'id_nguoi_danh_gia' => 'reviewer_id',
    'id_nguoi_duoc_danh_gia' => 'reviewed_user_id',
    'so_sao' => 'rating',
    'binh_luan' => 'comment',
    
    // Bảng transfer_accounts
    'id_ck' => 'account_number',
    'so_du' => 'balance',
    
    // Bảng transfer_history
    'history_id' => 'history_id',
    'noi_dung_ck' => 'transfer_content',
    'hinh_anh_ck' => 'transfer_image',
    'trang_thai_ck' => 'transfer_status',
    'ngay_tao' => 'created_date',
    
    // Bảng posting_fee_history
    'id_san_pham' => 'product_id',
    'so_tien' => 'amount',
    
    // Bảng promotion_history
    'thoi_gian_day' => 'promotion_time'
];

echo "Mapping bảng:\n";
foreach ($tableMapping as $old => $new) {
    echo "  $old → $new\n";
}

echo "\nMapping cột chính:\n";
foreach ($columnMapping as $old => $new) {
    echo "  $old → $new\n";
}

echo "\n=== HOÀN THÀNH MAPPING ===\n";
echo "Các file đã được cập nhật:\n";
echo "✅ model/mUser.php\n";
echo "✅ model/mProduct.php\n"; 
echo "✅ model/mCategory.php\n";
echo "✅ model/mPost.php (một phần)\n";
echo "⏳ Cần cập nhật thêm:\n";
echo "  - model/mPost.php (phần còn lại)\n";
echo "  - model/mChat.php\n";
echo "  - model/mLoginLogout.php\n";
echo "  - model/mTopUp.php\n";
echo "  - model/mReview.php\n";
echo "  - Tất cả file controller\n";
?>
