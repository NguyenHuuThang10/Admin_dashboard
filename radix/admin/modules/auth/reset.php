<?php
if(!defined('_INCODE')) die('Access Denied...');
$data = [
    'pageTitle' => 'Đặt lại mật khẩu'
];
layout('header-login', 'admin', $data);
echo '<div class="mt-3">';

$body = getBody();
if(!empty($body['token'])){
    $token = $body['token'];
    $queryForgot = firstRaw("SELECT id, email, fullname FROM users WHERE forget_token = '$token'");

    if($queryForgot){
        $userId = $queryForgot['id'];
        $email = $queryForgot['email'];
        $fullname = $queryForgot ['fullname'];

        if(isPost()){
            $body = getBody();
            $errors = [];
        
            if(empty(trim($body['password']))){
                $errors['password']['require'] = 'Mật khẩu bắt buộc phải nhập';
            }else{
                $pass = trim($body['password']);
                if (strlen($pass) < 6){
                    $errors['password']['min'] = 'Mật khẩu phải lớn hơn 6 ký tự.';
                }
            }
        
            if(empty(trim($body['confirm_password']))){
                $errors['confirm_password']['require'] = 'Nhập lại mật khẩu bắt buộc phải nhập';
            }else{
                $confirm_pass = trim($body['confirm_password']);
                if($confirm_pass !== $pass){
                    $errors['confirm_password']['math'] = 'Nhập lại mật khẩu không trùng khớp';
                }
            }
        
            if(empty($errors)){
                $dataUpdate = [
                    'password' => password_hash($pass, PASSWORD_DEFAULT),
                    'forget_token' => null,
                    'update_at' => date('Y-m-d H:i:s')
                ];
                $updateStatus = update('users', $dataUpdate, 'id='.$userId);
                if($updateStatus){
                    $linkLogin = _WEB_HOST_ROOT_ADMIN.'?module=auth&action=login';
                    $subject = 'Đặt lại mật khẩu thành công.';
                    $content = 'Chào bạn: ' . $fullname. '<br>';
                    $content .= 'Chúc mừng bạn đã đặt lại mật khẩu thành công, vui lòng bấm vào link dưới đây để đăng nhập: <br>';
                    $content .= $linkLogin . '<br>';
                    $content .= 'Trân trọng!';
                    $sendStatus = sendMail($email, $subject, $content);
                    if($sendStatus){
                        setFlashData('msg', 'Đặt lại mật khẩu thành công, bạn có thể đăng nhập ngay bây giờ.');
                        setFlashData('msg_type', 'success');
                        redirect('admin?module=auth&action=login');
                    }else{
                        setFlashData('msg', 'Hệ thống bị lỗi, vui lòng thử lại sau.');
                        setFlashData('msg_type', 'danger');
                    }
                }else{
                    setFlashData('msg', 'Hệ thống bị lỗi, vui lòng thử lại sau.');
                    setFlashData('msg_type', 'danger');
                }
            }else{
                setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
                setFlashData('msg_type', 'danger');
                setFlashData('errors', $errors);
            }
            redirect('admin/?module=auth&action=reset&token='.$token);
        }
    }


    $msg = getFlashData('msg');
    $msg_type = getFlashData('msg_type');
    $errors = getFlashData('errors');
?>
<div class="row mt-3">
    <div class="col-6" style="margin: 0 auto;">
        <h2 class="text-center"><?php echo (!empty($data['pageTitle']))?$data['pageTitle']:false; ?></h2>
        <?php
            getMsg($msg, $msg_type);
        ?>
        <form action="" method="post">
            <div class="form-group mb-3">
                <label for="">Mật khẩu</label>
                <input type="password" class="form-control" name="password", placeholder="Mật khẩu....">
                <?php echo getErrors('password', $errors, '<span class="error">', '</span>') ?>

            </div>

            <div class="form-group mb-3">
                <label for="">Nhập lại mật khẩu</label>
                <input type="password" class="form-control" name="confirm_password", placeholder="Nhập lại mật khẩu....">
                <?php echo getErrors('confirm_password', $errors, '<span class="error">', '</span>') ?>

            </div>

            <p class="d-grid">
                <button type="submit" class="btn btn-primary btn-block">Xác nhận</button>
            </p>
            <input type="hidden" name="token" value="<?php echo $token ?>">
        </form>
        <hr>
        <p class="text-center"><a href="<?php echo _WEB_HOST_ROOT_ADMIN.'?module=auth&action=login'; ?>">Đăng nhập hệ thống</a></p>
    </div>
</div>


<?php
}else{
    getMsg('Liên kết không tồn tại hoặc đã hết hạn!', 'danger');
}
?>


<?php
echo '</div>';
layout('footer-login', 'admin', $data);