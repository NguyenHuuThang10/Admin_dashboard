<?php
if (!defined('_INCODE'))
    die('Access Denied...');

$userId = isLogin()['user_id'];

$data = [
    'pageTitle' => 'Thêm dự án'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

if (isPost()) {
    $body = getBody();
    $error = [];

    if (empty(trim($body['name']))) {
        $error['name']['required'] = 'Tiêu đề bắt buộc phải nhập';
    }

    if (empty(trim($body['slug']))) {
        $error['slug']['required'] = 'Đường dẫn tĩnh bắt buộc phải nhập';
    }

    if (empty(trim($body['content']))) {
        $error['content']['required'] = 'Nội dung bắt buộc phải nhập';
    }

    if (empty(trim($body['video']))) {
        $error['video']['required'] = 'Link video bắt buộc phải nhập';
    }

    if (empty(trim($body['cate_id']))) {
        $error['cate_id']['required'] = 'Danh mục bắt buộc phải chọn';
    } else {
        $cate_id = $body['cate_id'];
    }

    if (empty(trim($body['thumbnail']))) {
        $error['thumbnail']['required'] = 'Ảnh đại diện bắt buộc phải chọn';
    }

    //validate ảnh dự án
    $galleryArr = $body['gallery'];
    if (!empty($galleryArr)) {
        foreach ($galleryArr as $key => $item) {
            if (empty(trim($item))) {
                $error['gallery']['required'][$key] = 'Vui lòng chọn ảnh';
            }
        }
    }



    if (empty($error)) {
        $dataInsert = [
            'name' => trim($body['name']),
            'slug' => trim($body['slug']),
            'description' => trim($body['description']),
            'user_id' => $userId,
            'content' => trim($body['content']),
            'video' => trim($body['video']),
            'portfolio_category_id' => $cate_id,
            'thumbnail' => trim($body['thumbnail']),
            'create_at' => date('Y-m-d H:i:s')
        ];
        $status = insert('portfolios', $dataInsert);


        if ($status) {
            // Xử lý thêm ảnh dự án 
            $currentId = insertId(); // Lấy id vừa mới thêm vào
            if (!empty($galleryArr)) {
                foreach ($galleryArr as $key => $item) {
                    $data = [
                        'portfolio_id' => $currentId,
                        'image' => $item,
                        'create_at' => date('Y-m-d H:i:s')
                    ];
                    insert('portfolio_images', $data);
                }
            }


            setFlashData('msg', 'Thêm dự án thành công.');
            setFlashData('msg_type', 'success');
            redirect('admin/?module=portfolios');
        } else {
            setFlashData('msg', 'Thêm dự án thất bại. Vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào.');
        setFlashData('msg_type', 'danger');
        setFlashData('old', $body);
        setFlashData('error', $error);
    }
    redirect('admin?module=portfolios&action=add');
}

$allCate = getRaw("SELECT id, name FROM portfolio_categories");

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
                        <input type="text" class="form-control slug" name="name" placeholder="Thêm dự án..."
                            value="<?php echo getOld('name', $old) ?>">
                        <?php echo getErrors('name', $error, '<span class="error">', '</span>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="">Đường dẫn tĩnh</label>
                        <input type="text" class="form-control render-slug" name="slug" placeholder="Đường dẫn tĩnh..."
                            value="<?php echo getOld('slug', $old) ?>">
                        <?php echo getErrors('slug', $error, '<span class="error">', '</span>'); ?>
                        <p class="render_link">
                            <b>Link: </b><span></span>
                        </p>
                    </div>

                    <div class="form-group">
                        <label for="">Mô tả ngắn</label>
                        <input type="text" class="form-control" name="description" placeholder="Mô tả ngắn..."
                            value="<?php echo getOld('description', $old) ?>">

                    </div>

                    <div class="form-group">
                        <label for="">Link video</label>
                        <input type="url" class="form-control" name="video" placeholder="Link video..."
                            value="<?php echo getOld('video', $old) ?>">
                        <?php echo getErrors('video', $error, '<span class="error">', '</span>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="">Nội dung</label>
                        <textarea name="content" class="form-control editor" id="" cols="30"
                            rows="3"><?php echo getOld('content', $old) ?></textarea>
                        <?php echo getErrors('content', $error, '<span class="error">', '</span>'); ?>
                    </div>

                    <div class="form-group mb-3">
                        <label for="">Danh mục</label>
                        <select name="cate_id" class="form-control" id="">
                            <option value="">Chọn danh mục</option>
                            <?php
                            if (!empty($allCate)):
                                foreach ($allCate as $item):
                                    ?>
                                    <option value="<?php echo $item['id'] ?>" <?php echo (!empty(getOld('cate_id', $old)) && (getOld('cate_id', $old) == $item['id'])) ? 'selected' : false; ?>><?php echo $item['name'] ?>
                                    </option>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <?php echo getErrors('cate_id', $error, '<span class="error">', '</span>') ?>

                    </div>

                    <div class="form-group">
                        <label for="">Ảnh đại diện</label>
                        <div class="row ckfinder-group">
                            <div class="col-9">
                                <input type="text" class="form-control image-render" name="thumbnail"
                                    placeholder="Ảnh đại diện..." value="<?php echo getOld('thumbnail', $old) ?>">
                            </div>
                            <div class="col-3">
                                <button type="button" class="btn btn-primary btn-block choose-img">Upload</button>
                            </div>
                        </div>
                        <?php echo getErrors('thumbnail', $error, '<span class="error">', '</span>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="">Ảnh dự án</label>
                        <div class="gallery-images">
                            <?php
                            $oldGallery = getOld('gallery', $old);
                            if (!empty($oldGallery)) {
                                if(!empty($error['gallery'])){
                                    $galleryErrors = $error['gallery'];
                                }
                                foreach ($oldGallery as $key=>$item) {
                                    ?>
                                    <div class="gallery-item">
                                        <div class="row">
                                            <div class="col-11">
                                                <div class="row ckfinder-group">
                                                    <div class="col-10">
                                                        <input type="text" class="form-control image-render" name="gallery[]"
                                                            placeholder="Đường dẫn ảnh..." value="<?php echo (!empty($item))?$item:false; ?>" />
                                                    </div>
                                                    <div class="col-2">
                                                        <button type="button" class="btn btn-success btn-block choose-img">Chọn
                                                            ảnh</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <a href="#" class="remove btn btn-danger btn-block"><i class="fa fa-times"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <?php echo (!empty($galleryErrors['required'][$key]))?'<span class="error">'.$galleryErrors['required'][$key].'</span>':false; ?>
                                    </div>
                                    <!--End .gallery-item-->
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <a class="btn btn-warning btn-sm add-gallery">Thêm ảnh</a>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Thêm</button>
            <a href="<?php echo getLinkAdmin('portfolios'); ?>" class="btn btn-success">Quay lại</a>
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin');