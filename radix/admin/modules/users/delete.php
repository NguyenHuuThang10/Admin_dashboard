<?php
if(!defined('_INCODE')) die('Access Denied...');

$body = getBody();
if(!empty($body['id'])){
    $userId = $body['id'];
    $query = getRows("SELECT * FROM users WHERE id = $userId");
    if($query > 0){
        $deleteToken = deleted('login_token', 'user_id='.$userId);
        if($deleteToken){
            $deleteUser = deleted('users', 'id='.$userId);
            if($deleteUser){
                setFlashData('msg', 'Xóa người dùng thành công');
                setFlashData('msg_type', 'success');
            }else{
                setFlashData('msg', 'Lỗi hệ thống');
                setFlashData('msg_type', 'danger');
            }
        }else{
            setFlashData('msg', 'Lỗi hệ thống');
            setFlashData('msg_type', 'danger');
        }
    }else{
        setFlashData('msg', 'Người dùng không tồn tại');
        setFlashData('msg_type', 'danger');
    }
}else{
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?module=users');