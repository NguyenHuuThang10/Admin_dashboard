<?php
if (!defined('_INCODE'))
    die('Access Denied...');

$body = getBody('get');
if (!empty($body['id'])) {
    $serviceId = $body['id'];
    $query = firstRaw("SELECT * FROM services WHERE id = $serviceId");
    if (!empty($query)) {
        unset($query['update_at']);
        unset($query['id']);
        $query['create_at'] = date('Y-m-d H:i:s');

        $duplicate = $query['duplicate'];
        $duplicate++;

        $name = $query['name'].' '.'('.$duplicate.')';
        $query['name'] = $name;

        $status = insert('services', $query);
        if ($status) {
            setFlashData('msg', 'Nhân bản dịch vụ thành công');
            setFlashData('msg_type', 'success');
            update('services', ['duplicate' => $duplicate], "id=$serviceId");
        } else {
            setFlashData('msg', 'Nhân bản dịch vụ thất bại');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Dịch vụ không tồn tại');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?module=services');