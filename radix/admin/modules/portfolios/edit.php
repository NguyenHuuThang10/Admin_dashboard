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
    $portfoliosId = $body['id'];
    $portfoliosDetail = firstRaw("SELECT * FROM portfolios WHERE id = $portfoliosId");
    // Truy vấn lấy thư viện ảnh

    $gallerryDetailArr = getRaw("SELECT * FROM portfolio_images WHERE portfolio_id = $portfoliosId");

    $galleryData = [];
    $galleryIdArr = [];

    if(!empty($gallerryDetailArr)){
        foreach($gallerryDetailArr as $item){
            $galleryData[] = $item['image'];
            $galleryIdArr[] = $item['id'];
        }
    }

    if(empty($portfoliosDetail)){
        redirect('admin/?module=portfolios');
    }
}else{
    redirect('admin/?module=portfolios');
}

if(isPost()){
    $body = getBody();
    $error = [];

    if(empty(trim($body['name']))){
        $error['name']['require'] = 'Tiêu đề bắt buộc phải nhập';
    }

    if(empty(trim($body['slug']))){
        $error['slug']['require'] = 'Đường dẫn tĩnh bắt buộc phải nhập';
    }

    if(empty(trim($body['content']))){
        $error['content']['require'] = 'Nội dung bắt buộc phải nhập';
    }

    if(empty(trim($body['video']))){
        $error['video']['require'] = 'Link video bắt buộc phải nhập';
    }

    if(empty(trim($body['portfolio_category_id']))){
        $error['portfolio_category_id']['require'] = 'Danh mục bắt buộc phải chọn';
    }else{
        $cate_id = $body['portfolio_category_id'];
    }

    if(empty(trim($body['thumbnail']))){
        $error['thumbnail']['require'] = 'Ảnh đại diện bắt buộc phải chọn';
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

    if(is_null($galleryArr)){
        $galleryArr = [];
    }
    

    if(empty($error)){

        if(count($galleryArr) > count($galleryData)){

            if(!empty($galleryData)){
                foreach($galleryData as $key=>$item){
                    $dataImages = [
                        'image' => $galleryArr[$key],
                        'update_at' => date('Y-m-d H:i:s')
                    ];
                    //Update thu vien anh
                    $condition = 'id='. $galleryIdArr[$key];
                    update('portfolio_images', $dataImages, $condition);
                }
            }else{
                $key = -1;
            }

            for($index = $key+1; $index < count($galleryArr); $index++){
                $dataImages = [
                    'image' => $galleryArr[$index],
                    'portfolio_id' => $portfoliosId,
                    'create_at' => date('Y-m-d H:i:s')
                ];
                //Insert anh con thieu
                insert('portfolio_images', $dataImages);
            }
        }elseif(count($galleryArr) < count($galleryData)){

            foreach($galleryArr as $key=>$item){
                $dataImages = [
                    'image' => $item,
                    'update_at' => date('Y-m-d H:i:s')
                ];
                //Update thu vien anh
                $condition = 'id='. $galleryIdArr[$key];
                update('portfolio_images', $dataImages, $condition);
            }
            if(is_null($key)){
                $key = -1;
            }

            for($index = $key+1; $index < count($galleryData); $index++){
                //delete anh thua
                $condition = 'id='. $galleryIdArr[$index];
                deleted('portfolio_images', $condition);
            }
        }else{
            foreach($galleryData as $key=>$item){
                $dataImages = [
                    'image' => $galleryArr[$key],
                    'update_at' => date('Y-m-d H:i:s')
                ];
                //Update thu vien anh
                $condition = "image= '$item'";
                update('portfolio_images', $dataImages, $condition);
            }
        }

        $dataInsert = [
            'name' => trim($body['name']),
            'slug' => trim($body['slug']),
            'description' => trim($body['description']),
            'content' => trim($body['content']),
            'video' => trim($body['video']),
            'portfolio_category_id' => $cate_id,
            'thumbnail' => trim($body['thumbnail']),
            'update_at' => date('Y-m-d H:i:s')
        ];
        $status = update('portfolios', $dataInsert, "id=$portfoliosId");
        if($status){
            setFlashData('msg', 'Cập nhật dự án thành công.');
            setFlashData('msg_type', 'success');
            redirect('admin/?module=portfolios');
        }else{
            setFlashData('msg', 'Cập nhật dự án thất bại. Vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }
    }else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào.');
        setFlashData('msg_type', 'danger');
        setFlashData('old', $body);
        setFlashData('error', $error);
    }
    redirect('admin?module=portfolios&action=edit&id='.$portfoliosId);
}

$allCate = getRaw("SELECT id, name FROM portfolio_categories");

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$error = getFlashData('error');
$old = getFlashData('old');
if(!empty($portfoliosDetail) && empty($old)){
    $old = $portfoliosDetail;
  
    $old['gallery'] = $galleryData;
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
                        <input type="text" class="form-control slug" name="name" placeholder="Thêm dự án..." value="<?php echo getOld('name', $old) ?>">
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
                        <label for="">Link video</label>
                        <input type="url" class="form-control" name="video" placeholder="Link video..." value="<?php echo getOld('video', $old) ?>">
                        <?php echo getErrors('video', $error, '<span class="error">', '</span>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="">Nội dung</label>
                        <textarea name="content" class="form-control editor" id="" cols="30" rows="3"><?php echo getOld('content', $old) ?></textarea>
                        <?php echo getErrors('content', $error, '<span class="error">', '</span>'); ?>
                    </div>

                    <div class="form-group mb-3">
                        <label for="">Danh mục</label>
                        <select name="portfolio_category_id" class="form-control" id="">
                            <option value="">Chọn danh mục</option>
                            <?php 
                            if(!empty($allCate)):
                                foreach($allCate as $item):
                            ?>
                            <option value="<?php echo $item['id'] ?>" <?php echo (!empty(getOld('portfolio_category_id', $old)) && (getOld('portfolio_category_id', $old) == $item['id']))?'selected':false; ?>><?php echo $item['name'] ?></option>
                            <?php
                                endforeach;endif;
                            ?>
                        </select>
                        <?php echo getErrors('portfolio_category_id', $error, '<span class="error">', '</span>') ?>

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
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="<?php echo getLinkAdmin('portfolios'); ?>" class="btn btn-success">Quay lại</a>
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin');