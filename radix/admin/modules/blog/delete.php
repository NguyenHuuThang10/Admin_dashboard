<?php
if(!defined('_INCODE')) die('Access Denied...');

$body = getBody();
if(!empty($body['id'])){
    $blog_id = $body['id'];
    $query = getRows("SELECT * FROM blog WHERE id = $blog_id");
    if($query > 0){
        $delete = deleted('blog', 'id='.$blog_id);
        if($delete){
            setFlashData('msg', 'Xóa blog thành công');
            setFlashData('msg_type', 'success');
        }else{
            setFlashData('msg', 'Lỗi hệ thống');
            setFlashData('msg_type', 'danger');
        }
    }else{
        setFlashData('msg', 'Blog không tồn tại');
        setFlashData('msg_type', 'danger');
    }
}else{
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?module=blog');