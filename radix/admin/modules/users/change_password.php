<?php
if (!defined('_INCODE'))
    die('Access Denied...');

    $userId = isLogin()['user_id'];
    $userDetail = firstRaw("SELECT * FROM users WHERE id= $userId");

$data = [
    'pageTitle' => 'Đổi mật khẩu'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

if(isPost()){
    $body = getBody();
    $errors = [];

    if(empty(trim($body['old_password']))){
        $errors['old_password']['require'] = 'Mật khẩu cũ bắt buộc phải nhập';
    }else{
        $old_password = trim($body['old_password']);
        if(!password_verify($old_password, $userDetail['password'])){
            $errors['old_password']['unique'] = 'Mật khẩu cũ không chính xác';
        }
    }

    if(empty(trim($body['new_password']))){
        $errors['new_password']['require'] = 'Mật khẩu mới bắt buộc phải nhập';
    }else{
        $pass = trim($body['new_password']);
        if (strlen($pass) < 6){
            $errors['new_password']['min'] = 'Mật khẩu mới phải lớn hơn 6 ký tự.';
        }
    }

    if(empty(trim($body['confirm_new_password']))){
        $errors['confirm_new_password']['require'] = 'Nhập lại mật khẩu bắt buộc phải nhập';
    }else{
        $confirm_pass = trim($body['confirm_new_password']);
        if($confirm_pass !== $pass){
            $errors['confirm_new_password']['math'] = 'Nhập lại mật khẩu không trùng khớp';
        }
    }

    if(empty($errors)){
        $dataUpdate = [
            'password' => password_hash($pass, PASSWORD_DEFAULT),
            'update_at' => date('Y-m-d H:i:s')
        ];
        $updateStatus = update('users', $dataUpdate, 'id='.$userId);
        if($updateStatus){
            setFlashData('msg', 'Đổi mật khẩu thành công. Vui lòng đăng nhập lại bằng mật khẩu mới.');
            setFlashData('msg_type', 'success');
            redirect('admin?module=auth&action=logout');
        }else{
            setFlashData('msg', 'Hệ thống bị lỗi, vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }
    }else{
        setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
    }
    redirect('admin?module=users&action=change_password');
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('errors');
?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <?php
            getMsg($msg, $msg_type);
        ?>
        <form action="" method="post">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="">Mật khẩu cũ</label>
                        <input type="password" name="old_password" class="form-control"
                            placeholder="Nhập mật khẩu cũ...">
                            <?php echo getErrors('old_password', $errors, '<span class="error">', '</span>') ?>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label for="">Mật khẩu mới</label>
                        <input type="password" name="new_password" class="form-control"
                            placeholder="Nhập mật khẩu mới...">
                            <?php echo getErrors('new_password', $errors, '<span class="error">', '</span>') ?>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                    <label for="">Nhập lại mật khẩu mới</label>
                        <input type="password" name="confirm_new_password" class="form-control"
                            placeholder="Nhập lại mật khẩu mới...">
                            <?php echo getErrors('confirm_new_password', $errors, '<span class="error">', '</span>') ?>
                    </div>
                </div>
            </div>
            <p>
                <button type="submit" class="btn btn-primary">Xác nhận</button>
            </p>
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin');