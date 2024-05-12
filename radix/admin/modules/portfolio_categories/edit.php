<?php
$body = getBody('get'); 
if(!empty($body['id'])){
    $cateId = $body['id'];
    $cateDetail = firstRaw("SELECT * FROM portfolio_categories WHERE id = $cateId");
    if(empty($cateDetail)){
        redirect('admin/?module=portfolio_categories');
    }
}else{
    redirect('admin/?module=portfolio_categories');
}

if(isPost()){
    $body = getBody();
    $error = [];

    if(empty(trim($body['name']))){
        $error['name']['require'] = 'Tiêu đề danh mục bắt buộc phải nhập';
    }else{
        $name = trim($body['name']);
        if(mb_strlen($name) < 4){
            $error['name']['min'] = 'Tiêu đề danh mục phải >= 4 ký tự';
        }
    }

    if(empty($error)){
        $dataUpdate = [
            'name' => $name,
            'update_at' => date('Y-m-d H:i:s')
        ];
        $status = update('portfolio_categories', $dataUpdate, "id=$cateId");
        if($status){
            setFlashData('msg', 'Cập nhật danh mục thành công.');
            setFlashData('msg_type', 'success');
            redirect('admin/?module=portfolio_categories');
        }else{
            setFlashData('msg', 'Cập nhật danh mục thất bại. Vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }
    }else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào.');
        setFlashData('msg_type', 'danger');
        setFlashData('old', $body);
        setFlashData('error', $error);
    }
    redirect('admin/?module=portfolio_categories&view=edit&id='.$cateId);
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$error = getFlashData('error');
$old = getFlashData('old');
if(!empty($cateDetail) && empty($old)){
    $old = $cateDetail;
}
?>
<h4>Cập nhật danh mục</h4>
<form action="" method="post">
    <div class="form-group">
        <input type="text" class="form-control" name='name' , placeholder="Nhập tiêu đề danh mục..." value="<?php echo getOld('name', $old) ?>">
        <?php echo getErrors('name', $error, '<span class="error">', '</span>'); ?>
    </div>
    <button type="submit" class="btn btn-primary">Cập nhật</button>
    <a href="<?php echo getLinkAdmin('portfolio_categories'); ?>" class="btn btn-success">Quay lại</a>
</form>