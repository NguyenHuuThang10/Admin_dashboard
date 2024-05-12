<?php
if(!defined('_INCODE')) die('Access Denied...');

$body = getBody();
if(!empty($body['id'])){
    $portfoliosId = $body['id'];
    $query = getRows("SELECT * FROM portfolios WHERE id = $portfoliosId");
    if($query > 0){
        //Xóa thư viện ảnh dự án
        deleted('portfolio_images', 'portfolio_id='.$body['id']);
        //Xóa dự án
        $delete = deleted('portfolios', 'id='.$portfoliosId);
        if($delete){
            setFlashData('msg', 'Xóa dự án thành công');
            setFlashData('msg_type', 'success');
        }else{
            setFlashData('msg', 'Lỗi hệ thống');
            setFlashData('msg_type', 'danger');
        }
    }else{
        setFlashData('msg', 'Dự án không tồn tại');
        setFlashData('msg_type', 'danger');
    }
}else{
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?module=portfolios');