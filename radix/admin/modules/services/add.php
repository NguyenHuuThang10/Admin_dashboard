<?php
if (!defined('_INCODE'))
    die('Access Denied...');

    $userId = isLogin()['user_id'];

$data = [
    'pageTitle' => 'Thêm dịch vụ'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

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
        $dataInsert = [
            'name' => trim($body['name']),
            'slug' => trim($body['slug']),
            'icon' => trim($body['icon']),
            'description' => trim($body['description']),
            'user_id' => $userId,
            'content' => trim($body['content']),
            'create_at' => date('Y-m-d H:i:s')
        ];
        $status = insert('services', $dataInsert);
        if($status){
            setFlashData('msg', 'Thêm dịch vụ thành công.');
            setFlashData('msg_type', 'success');
            redirect('admin/?module=services');
        }else{
            setFlashData('msg', 'Thêm dịch vụ thất bại. Vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }
    }else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào.');
        setFlashData('msg_type', 'danger');
        setFlashData('old', $body);
        setFlashData('error', $error);
    }
    redirect('admin?module=services&action=add');
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$error = getFlashData('error');
$old = getFlashData('old');
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
                <button type="submit" class="btn btn-primary">Thêm</button>
                <a href="<?php echo getLinkAdmin('services'); ?>" class="btn btn-success">Quay lại</a>
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin');