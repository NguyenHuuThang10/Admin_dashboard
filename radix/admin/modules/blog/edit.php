<?php
if (!defined('_INCODE'))
    die('Access Denied...');


$body = getBody('get');
if(!empty($body['id'])){
    $blog_id = $body['id'];
    $blog_detail = firstRaw("SELECT * FROM blog WHERE id = $blog_id");
    if(empty($blog_detail)){
        redirect('admin?module=blog');
    }
}else{
    redirect('admin?module=blog');
}

$data = [
    'pageTitle' => 'Cập nhật blog'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

if(isPost()){
    $body = getBody();
    $error = [];

    if(empty(trim($body['title']))){
        $error['title']['require'] = 'Tiêu đề bắt buộc phải nhập';
    }

    if(empty(trim($body['slug']))){
        $error['slug']['require'] = 'Đường dẫn tĩnh bắt buộc phải nhập';
    }

    if(empty(trim($body['content']))){
        $error['content']['require'] = 'Nội dung bắt buộc phải nhập';
    }

    if(empty(trim($body['cate_id']))){
        $error['cate_id']['require'] = 'Danh mục bắt buộc phải chọn';
    }else{
        $cate_id = $body['cate_id'];
    }

    if(empty(trim($body['thumbnail']))){
        $error['thumbnail']['require'] = 'Ảnh đại diện bắt buộc phải chọn';
    }

    

    if(empty($error)){
        $dataUpdate = [
            'title' => trim($body['title']),
            'slug' => trim($body['slug']),
            'description' => trim($body['description']),
            'content' => trim($body['content']),
            'category_id' => $cate_id,
            'thumbnail' => trim($body['thumbnail']),
            'update_at' => date('Y-m-d H:i:s')
        ];
        $status = update('blog', $dataUpdate, "id=$blog_id");
        if($status){
            setFlashData('msg', 'Cập nhật blog thành công.');
            setFlashData('msg_type', 'success');
            redirect('admin/?module=blog');
        }else{
            setFlashData('msg', 'Cập nhật blog thất bại. Vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }
    }else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào.');
        setFlashData('msg_type', 'danger');
        setFlashData('old', $body);
        setFlashData('error', $error);
    }
    redirect('admin?module=blog&action=add');
}

$allCate = getRaw("SELECT id, name FROM blog_categories");

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$error = getFlashData('error');
$old = getFlashData('old');
if(!empty($blog_detail) && empty($old)){
    $old = $blog_detail;
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
                        <label for="">Tiêu đề</label>
                        <input type="text" class="form-control slug" name="title" placeholder="Cập nhật blog..." value="<?php echo getOld('title', $old) ?>">
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
                        <label for="">Mô tả ngắn</label>
                        <input type="text" class="form-control" name="description" placeholder="Mô tả ngắn..." value="<?php echo getOld('description', $old) ?>">

                    </div>

                    <div class="form-group">
                        <label for="">Nội dung</label>
                        <textarea name="content" class="form-control editor" id="" cols="30" rows="3"><?php echo getOld('content', $old) ?></textarea>
                        <?php echo getErrors('content', $error, '<span class="error">', '</span>'); ?>
                    </div>

                    <div class="form-group mb-3">
                        <label for="">Danh mục</label>
                        <select name="cate_id" class="form-control" id="">
                            <option value="">Chọn danh mục</option>
                            <?php 
                            if(!empty($allCate)):
                                foreach($allCate as $item):
                            ?>
                            <option value="<?php echo $item['id'] ?>" <?php echo ($item['id'] == getOld('category_id', $old))?'selected':false; ?>>
                                <?php echo $item['name'] ?>
                            </option>
                            <?php
                                endforeach;endif;
                            ?>
                        </select>
                        <?php echo getErrors('cate_id', $error, '<span class="error">', '</span>') ?>

                    </div>

                    <div class="form-group">
                        <label for="">Ảnh đại diện</label>
                        <div class="row ckfinder-group">
                            <div class="col-9">
                                <input type="text" class="form-control image-render" name="thumbnail" placeholder="Ảnh đại diện..." value="<?php echo getOld('thumbnail', $old) ?>">
                            </div>
                            <div class="col-3">
                                <button type="button" class="btn btn-primary btn-block choose-img">Upload</button>
                            </div>
                        </div>
                        <?php echo getErrors('thumbnail', $error, '<span class="error">', '</span>'); ?>
                    </div>
                </div>
            </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="<?php echo getLinkAdmin('blog'); ?>" class="btn btn-success">Quay lại</a>
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin');