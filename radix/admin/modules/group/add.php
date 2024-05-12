<?php
if (!defined('_INCODE'))
    die('Access Denied...');

$data = [
    'pageTitle' => 'Thêm nhóm người dùng'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

if(isPost()){
    $body = getBody();
    $error = [];

    if(empty(trim($body['name']))){
        $error['name']['require'] = 'Tên nhóm bắt buộc phải nhập';
    }else{
        $name = trim($body['name']);
        if(mb_strlen($name) < 4){
            $error['name']['min'] = 'Tên nhóm phải >= 4 ký tự';
        }
    }

    if(empty($error)){
        $dataInsert = [
            'name' => $name,
            'create_at' => date('Y-m-d H:i:s')
        ];
        $status = insert('groups', $dataInsert);
        if($status){
            setFlashData('msg', 'Thêm nhóm người dùng thành công.');
            setFlashData('msg_type', 'success');
            redirect('admin/?module=group');
        }else{
            setFlashData('msg', 'Thêm nhóm người dùng thất bại. Vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }
    }else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào.');
        setFlashData('msg_type', 'danger');
        setFlashData('old', $body);
        setFlashData('error', $error);
    }
    redirect('admin?module=group&action=add');
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
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Tên nhóm</label>
                        <input type="text" class="form-control" name="name" placeholder="Thêm nhóm" value="<?php echo getOld('name', $old) ?>">
                        <?php echo getErrors('name', $error, '<span class="error">', '</span>'); ?>
                    </div>
                </div>
            </div>
                <button type="submit" class="btn btn-primary">Thêm</button>
                <a href="<?php echo getLinkAdmin('group'); ?>" class="btn btn-success">Quay lại</a>
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin');