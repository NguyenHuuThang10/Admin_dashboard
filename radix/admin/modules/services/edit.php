<?php
if (!defined('_INCODE'))
    die('Access Denied...');

$data = [
    'pageTitle' => 'Cập nhật dịch vụ'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

$body = getBody('get'); 
if(!empty($body['id'])){
    $serviceId = $body['id'];
    $serviceDetail = firstRaw("SELECT * FROM `services` WHERE id = $serviceId");
    if(empty($serviceDetail)){
        redirect('admin/?module=services');
    }
}else{
    redirect('admin/?module=services');
}

if(isPost()){
    $body = getBody();
    $error = [];

    if(empty(trim($body['name']))){
        $error['name']['require'] = 'Tên dịch vụ bắt buộc phải nhập';
    }

    if(empty(trim($body['slug']))){
        $error['slug']['require'] = 'Đường dẫn tĩnh bắt buộc phải nhập';
    }

    if(empty(trim($body['icon']))){
        $error['icon']['require'] = 'Icon bắt buộc phải nhập';
    }

    if(empty(trim($body['content']))){
        $error['content']['require'] = 'Nội dung bắt buộc phải nhập';
    }

    if(empty($error)){
        $dataUpdate = [
            'name' => trim($body['name']),
            'slug' => trim($body['slug']),
            'icon' => trim($body['icon']),
            'description' => trim($body['description']),
            'content' => trim($body['content']),
            'update_at' => date('Y-m-d H:i:s')
        ];
        $status = update('services', $dataUpdate, "id=$serviceId");
        if($status){
            setFlashData('msg', 'Cập nhật dịch vụ thành công.');
            setFlashData('msg_type', 'success');
            redirect('admin/?module=services');
        }else{
            setFlashData('msg', 'Cập nhật dịch vụ thất bại. Vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }
    }else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào.');
        setFlashData('msg_type', 'danger');
        setFlashData('old', $body);
        setFlashData('error', $error);
    }
    redirect('admin/?module=services&action=edit&id='.$serviceIdId);
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$error = getFlashData('error');
$old = getFlashData('old');
if(!empty($serviceDetail) && empty($old)){
    $old = $serviceDetail;
}
?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <?php
            getMsg($msg, $msg_type);
        ?>
        <form action="" method="post">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="">Tên dịch vụ</label>
                        <input type="text" class="form-control slug" name="name" placeholder="Thêm dịch vụ..." value="<?php echo getOld('name', $old) ?>">
                        <?php echo getErrors('name', $error, '<span class="error">', '</span>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="">Đường dẫn tĩnh</label>
                        <input type="text" class="form-control render-slug" name="slug" placeholder="Đường dẫn tĩnh..." value="<?php echo getOld('slug', $old) ?>">
                        <?php echo getErrors('slug', $error, '<span class="error">', '</span>'); ?>
                        <p class="render_link">
                            <b>Link: </b><span></span>
                        </p>
                    </div>

                    <div class="form-group">
                        <label for="">Icon</label>
                        <div class="row ckfinder-group">
                            <div class="col-9">
                                <input type="text" class="form-control image-render" name="icon" placeholder="Đường dẫn ảnh hoặc mã icon..." value="<?php echo getOld('icon', $old) ?>">
                            </div>
                            <div class="col-3">
                                <button type="button" class="btn btn-primary btn-block choose-img">Upload</button>
                            </div>
                        </div>
                        <?php echo getErrors('icon', $error, '<span class="error">', '</span>'); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="">Mô tả ngắn</label>
                        <textarea name="description" placeholder="Mô tả ngắn..." class="form-control editor" id="" cols="30" rows="3"><?php echo getOld('description', $old) ?></textarea>
                        <?php echo getErrors('description', $error, '<span class="error">', '</span>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="">Nội dung</label>
                        <textarea name="content" class="form-control editor" id="" cols="30" rows="3"><?php echo getOld('content', $old) ?></textarea>
                        <?php echo getErrors('content', $error, '<span class="error">', '</span>'); ?>
                    </div>

                </div>
            </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="<?php echo getLinkAdmin('services'); ?>" class="btn btn-success">Quay lại</a>
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin');