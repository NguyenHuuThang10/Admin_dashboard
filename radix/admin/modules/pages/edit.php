<?php
if (!defined('_INCODE'))
    die('Access Denied...');

$data = [
    'pageTitle' => 'Cập nhật tiêu đề'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

$body = getBody('get'); 
if(!empty($body['id'])){
    $pageId = $body['id'];
    $pagesDetail = firstRaw("SELECT * FROM `pages` WHERE id = $pageId");
    if(empty($pagesDetail)){
        redirect('admin/?module=pages');
    }
}else{
    redirect('admin/?module=pages');
}

if(isPost()){
    $body = getBody();
    $error = [];

    if(empty(trim($body['title']))){
        $error['title']['require'] = 'Tiêu đè bắt buộc phải nhập';
    }

    if(empty(trim($body['slug']))){
        $error['slug']['require'] = 'Đường dẫn tĩnh bắt buộc phải nhập';
    }

    if(empty(trim($body['content']))){
        $error['content']['require'] = 'Nội dung bắt buộc phải nhập';
    }

    if(empty($error)){
        $dataUpdate = [
            'title' => trim($body['title']),
            'slug' => trim($body['slug']),
            'content' => trim($body['content']),
            'update_at' => date('Y-m-d H:i:s')
        ];
        $status = update('pages', $dataUpdate, "id=$pageId");
        if($status){
            setFlashData('msg', 'Cập nhật tiêu đề thành công.');
            setFlashData('msg_type', 'success');
            redirect('admin/?module=pages');
        }else{
            setFlashData('msg', 'Cập nhật tiêu đề thất bại. Vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }
    }else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào.');
        setFlashData('msg_type', 'danger');
        setFlashData('old', $body);
        setFlashData('error', $error);
    }
    redirect('admin/?module=pages&action=edit&id='.$pageIdId);
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$error = getFlashData('error');
$old = getFlashData('old');
if(!empty($pagesDetail) && empty($old)){
    $old = $pagesDetail;
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
                        <label for="">Tên tiêu đề</label>
                        <input type="text" class="form-control slug" name="title" placeholder="Thêm tiêu đề..." value="<?php echo getOld('title', $old) ?>">
                        <?php echo getErrors('title', $error, '<span class="error">', '</span>'); ?>
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
                        <label for="">Nội dung</label>
                        <textarea name="content" class="form-control editor" id="" cols="30" rows="3"><?php echo getOld('content', $old) ?></textarea>
                        <?php echo getErrors('content', $error, '<span class="error">', '</span>'); ?>
                    </div>

                </div>
            </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="<?php echo getLinkAdmin('pages'); ?>" class="btn btn-success">Quay lại</a>
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin');