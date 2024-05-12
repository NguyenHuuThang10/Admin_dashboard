<?php
$userId = isLogin()['user_id'];
if (isPost()) {
    $body = getBody();
    $error = [];

    if (empty(trim($body['name']))) {
        $error['name']['require'] = 'Tên danh mục bắt buộc phải nhập';
    } else {
        $name = trim($body['name']);
        if (mb_strlen($name) < 4) {
            $error['name']['min'] = 'Tên danh mục phải >= 4 ký tự';
        }
    }

    if(empty(trim($body['slug']))){
        $error['slug']['require'] = 'Đường dẫn tĩnh bắt buộc phải nhập';
    }

    if (empty($error)) {
        $dataInsert = [
            'name' => $name,
            'user_id' => $userId,
            'slug' => trim($body['slug']),
            'create_at' => date('Y-m-d H:i:s')
        ];
        $status = insert('blog_categories', $dataInsert);
        if ($status) {
            setFlashData('msg', 'Thêm danh mục blog thành công.');
            setFlashData('msg_type', 'success');
            redirect('admin/?module=blog_categories');
        } else {
            setFlashData('msg', 'Thêm danh mục blog thất bại. Vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào.');
        setFlashData('msg_type', 'danger');
        setFlashData('old', $body);
        setFlashData('error', $error);
    }
    redirect('admin?module=blog_categories');
}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$error = getFlashData('error');
$old = getFlashData('old');
?>
<h4>Thêm danh mục</h4>
<form action="" method="post">
    <div class="form-group">
        <input type="text" class="form-control slug" name='name' , placeholder="Nhập tiêu đề danh mục..."
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
    <button type="submit" class="btn btn-primary">Thêm</button>
</form>