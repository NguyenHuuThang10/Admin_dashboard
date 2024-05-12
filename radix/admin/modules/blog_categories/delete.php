<?php
if (!defined('_INCODE'))
    die('Access Denied...');

$body = getBody('get');
if (!empty($body['id'])) {
    $cateId = $body['id'];
    $query = getRows("SELECT * FROM blog_categories WHERE id = $cateId");
    if ($query > 0) {
        $userNum = getRows("SELECT * FROM blog WHERE category_id = $cateId");
        if($userNum > 0){
            setFlashData('msg', "Trong danh muc vẫn còn $userNum blog");
            setFlashData('msg_type', 'danger');
        }else{
            $deleteCate = deleted('blog_categories', 'id=' . $cateId);
            if ($deleteCate) {
                setFlashData('msg', 'Xóa danh mục blog thành công');
                setFlashData('msg_type', 'success');
            } else {
                setFlashData('msg', 'Xóa danh mục blog thất bại');
                setFlashData('msg_type', 'danger');
            }
        }

    } else {
        setFlashData('msg', 'Danh mục blog không tồn tại');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?module=blog_categories');