<?php
if (!defined('_INCODE'))
    die('Access Denied...');

$body = getBody('get');
if (!empty($body['id'])) {
    $cateId = $body['id'];
    $query = firstRaw("SELECT * FROM blog_categories WHERE id = $cateId");
    if (!empty($query)) {
        unset($query['update_at']);
        unset($query['id']);
        $query['create_at'] = date('Y-m-d H:i:s');

        $duplicate = $query['duplicate'];
        $duplicate++;

        $name = $query['name'].' '.'('.$duplicate.')';
        $query['name'] = $name;

        $status = insert('blog_categories', $query);
        if ($status) {
            setFlashData('msg', 'Nhân bản danh mục thành công');
            setFlashData('msg_type', 'success');
            update('blog_categories', ['duplicate' => $duplicate], "id=$cateId");
        } else {
            setFlashData('msg', 'Nhân bản danh mục thất bại');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Danh mục không tồn tại');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?module=blog_categories');