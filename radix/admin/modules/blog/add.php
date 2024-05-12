<?php
if (!defined('_INCODE'))
    die('Access Denied...');

    $userId = isLogin()['user_id'];

$data = [
    'pageTitle' => 'Thêm blog'
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

    if(empty(trim($body['category_id']))){
        $error['category_id']['require'] = 'Danh mục bắt buộc phải chọn';
    }else{
        $category_id = $body['category_id'];
    }

    if(empty(trim($body['thumbnail']))){
        $error['thumbnail']['require'] = 'Ảnh đại diện bắt buộc phải chọn';
    }

    

    if(empty($error)){
        $dataInsert = [
            'title' => trim($body['title']),
            'slug' => trim($body['slug']),
            'description' => trim($body['description']),
            'user_id' => $userId,
            'content' => trim($body['content']),
            'category_id' => $category_id,
            'thumbnail' => trim($body['thumbnail']),
            'create_at' => date('Y-m-d H:i:s')
        ];
        $status = insert('blog', $dataInsert);
        if($status){
            setFlashData('msg', 'Thêm blog thành công.');
            setFlashData('msg_type', 'success');
            redirect('admin/?module=blog');
        }else{
            setFlashData('msg', 'Thêm blog thất bại. Vui lòng thử lại sau.');
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
                        <input type="text" class="form-control slug" name="title" placeholder="Thêm blog..." value="<?php echo getOld('title', $old) ?>">
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
                        <select name="category_id" class="form-control" id="">
                            <option value="">Chọn danh mục</option>
                            <?php 
                            if(!empty($allCate)):
                                foreach($allCate as $item):
                            ?>
                            <option value="<?php echo $item['id'] ?>" <?php echo (getOld('category_id', $old) == $item['id'])?'selected':false; ?>><?php echo $item['name'] ?></option>
                            <?php
                                endforeach;endif;
                            ?>
                        </select>
                        <?php echo getErrors('category_id', $error, '<span class="error">', '</span>') ?>

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
                <button type="submit" class="btn btn-primary">Thêm</button>
                <a href="<?php echo getLinkAdmin('blog'); ?>" class="btn btn-success">Quay lại</a>
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin');