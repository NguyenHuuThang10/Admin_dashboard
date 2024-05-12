<?php
if (!defined('_INCODE'))
    die('Access Denied...');

$body = getBody('get');
if (!empty($body['id'])) {
    $blog_id = $body['id'];
    $query = firstRaw("SELECT * FROM blog WHERE id = $blog_id");
    if (!empty($query)) {
        unset($query['update_at']);
        unset($query['id']);
        $query['create_at'] = date('Y-m-d H:i:s');

        $duplicate = $query['duplicate'];
        $duplicate++;

        $title = $query['title'].' '.'('.$duplicate.')';
        $query['title'] = $title;

        $status = insert('blog', $query);
        if ($status) {
            setFlashData('msg', 'Nhân bản blog thành công');
            setFlashData('msg_type', 'success');
            update('blog', ['duplicate' => $duplicate], "id=$blog_id");
        } else {
            setFlashData('msg', 'Nhân bản blog thất bại');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Blog không tồn tại');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?module=blog');