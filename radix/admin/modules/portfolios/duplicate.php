<?php
if (!defined('_INCODE'))
    die('Access Denied...');

$body = getBody('get');
if (!empty($body['id'])) {
    $portfoliosId = $body['id'];
    $query = firstRaw("SELECT * FROM portfolios WHERE id = $portfoliosId");
    if (!empty($query)) {
        unset($query['update_at']);
        unset($query['id']);
        $query['create_at'] = date('Y-m-d H:i:s');

        $duplicate = $query['duplicate'];
        $duplicate++;

        $name = $query['name'].' '.'('.$duplicate.')';
        $query['name'] = $name;

        $status = insert('portfolios', $query);
        if ($status) {
            setFlashData('msg', 'Nhân bản dự án thành công');
            setFlashData('msg_type', 'success');
            update('portfolios', ['duplicate' => $duplicate], "id=$portfoliosId");
        } else {
            setFlashData('msg', 'Nhân bản dự án thất bại');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Dự án không tồn tại');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?module=portfolios');