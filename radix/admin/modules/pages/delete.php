<?php
if (!defined('_INCODE'))
    die('Access Denied...');

$body = getBody('get');
if (!empty($body['id'])) {
    $pageId = $body['id'];
    $query = getRows("SELECT * FROM pages WHERE id = $pageId");
    if ($query > 0) {
        $deletePage = deleted('pages', 'id=' . $pageId);
        if ($deletePage) {
            setFlashData('msg', 'Xóa trang thành công');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Xóa trang thất bại');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Trang không tồn tại');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?module=pages');