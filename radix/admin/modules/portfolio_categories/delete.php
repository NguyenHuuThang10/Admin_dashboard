<?php
if (!defined('_INCODE'))
    die('Access Denied...');

$body = getBody('get');
if (!empty($body['id'])) {
    $cateId = $body['id'];
    $query = getRows("SELECT * FROM portfolio_categories WHERE id = $cateId");
    if ($query > 0) {
        $userNum = getRows("SELECT * FROM portfolios WHERE portfolio_category_id = $cateId");
        if($userNum > 0){
            setFlashData('msg', "Trong danh muc vẫn còn $userNum dự án");
            setFlashData('msg_type', 'danger');
        }else{
            $deleteCate = deleted('portfolio_categories', 'id=' . $cateId);
            if ($deleteCate) {
                setFlashData('msg', 'Xóa danh mục dự án thành công');
                setFlashData('msg_type', 'success');
            } else {
                setFlashData('msg', 'Xóa danh mục dự án thất bại');
                setFlashData('msg_type', 'danger');
            }
        }

    } else {
        setFlashData('msg', 'Danh mục dự án không tồn tại');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?module=portfolio_categories');