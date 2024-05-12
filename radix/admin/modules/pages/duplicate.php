<?php
if (!defined('_INCODE'))
    die('Access Denied...');

$body = getBody('get');
if (!empty($body['id'])) {
    $pageId = $body['id'];
    $query = firstRaw("SELECT * FROM pages WHERE id = $pageId");
    if (!empty($query)) {
        unset($query['update_at']);
        unset($query['id']);
        $query['create_at'] = date('Y-m-d H:i:s');

        $duplicate = $query['duplicate'];
        $duplicate++;

        $name = $query['title'].' '.'('.$duplicate.')';
        $query['title'] = $name;

        $status = insert('pages', $query);
        if ($status) {
            setFlashData('msg', 'Nhân bản trang thành công');
            setFlashData('msg_type', 'success');
            update('pages', ['duplicate' => $duplicate], "id=$pageId");
        } else {
            setFlashData('msg', 'Nhân bản trang thất bại');
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