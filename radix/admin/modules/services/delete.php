<?php
if (!defined('_INCODE'))
    die('Access Denied...');

$body = getBody('get');
if (!empty($body['id'])) {
    $serviceId = $body['id'];
    $query = getRows("SELECT * FROM services WHERE id = $serviceId");
    if ($query > 0) {
        $deleteService = deleted('services', 'id=' . $serviceId);
        if ($deleteService) {
            setFlashData('msg', 'Xóa dịch vụ thành công');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Xóa dịch vụ thất bại');
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