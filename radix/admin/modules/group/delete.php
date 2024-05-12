<?php
if (!defined('_INCODE'))
    die('Access Denied...');

$body = getBody('get');
if (!empty($body['id'])) {
    $groupId = $body['id'];
    $query = getRows("SELECT * FROM groups WHERE id = $groupId");
    if ($query > 0) {
        $userNum = getRows("SELECT * FROM users WHERE group_id = $groupId");
        if($userNum > 0){
            setFlashData('msg', "Trong nhóm vẫn còn $userNum người dùng");
            setFlashData('msg_type', 'danger');
        }else{
            $deleteGroup = deleted('groups', 'id=' . $groupId);
            if ($deleteGroup) {
                setFlashData('msg', 'Xóa nhóm người dùng thành công');
                setFlashData('msg_type', 'success');
            } else {
                setFlashData('msg', 'Xóa nhóm người dùng thất bại');
                setFlashData('msg_type', 'danger');
            }
        }

    } else {
        setFlashData('msg', 'Nhóm người dùng không tồn tại');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?module=group');