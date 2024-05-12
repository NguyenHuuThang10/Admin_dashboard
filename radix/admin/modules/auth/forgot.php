<?php
if(!defined('_INCODE')) die('Access Denied...');

$data = [
    'pageTitle' => 'Quên mật khẩu'
];
layout('header-login', 'admin', $data);

if(isPost()){
    $body = getBody();

    if(!empty(trim($body['email']))){
        $email = trim($body['email']);
        $query = firstRaw("SELECT id, email, fullname FROM users WHERE email='$email'");
        if($query){
            $userId = $query['id'];
            $fullname = $query['fullname'];
            $email = $query['email'];
            $forgotToken = sha1(uniqid().time());
            $dataUpdate = [
                'forget_token' => $forgotToken  
            ];
            $status = update('users', $dataUpdate, 'id='.$userId);
            if($status){
                $linkForgot = _WEB_HOST_ROOT_ADMIN.'?module=auth&action=reset&token='.$forgotToken;
                $subject = 'Yêu cầu đổi mật khẩu';
                $content = 'Chào bạn: '. $fullname. '<br>';
                $content .= 'Yêu cầu thay đổi mật khẩu của bạn được chấp nhận, vui lòng nhấp vào link sau để đặt lại mật khẩu: <br>';
                $content .= $linkForgot. '<br>';
                $content .= 'Trân trọng!';
                $sendStatus = sendMail($email, $subject, $content);
                if($sendStatus){
                    setFlashData('msg', 'Vui lòng kiểm tra email để thực hiện bước tiếp theo.');
                    setFlashData('msg_type', 'success');
                }else{
                    setFlashData('msg', 'Hệ thống gặp sự cố, Vui lòng thử lại.');
                    setFlashData('msg_type', 'danger');
                }
            }else{
                setFlashData('msg', 'Hệ thống gặp sự cố, Vui lòng thử lại.');
                setFlashData('msg_type', 'danger');
            }
        }else{
            setFlashData('msg', 'Email không tồn tại trong hệ thống.');
            setFlashData('msg_type', 'danger');
        }
    }else{
        setFlashData('msg', 'Vui lòng nhập đầy đủ thông tin.');
        setFlashData('msg_type', 'danger');
    }
    redirect('admin/?module=auth&action=forgot');
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');   
?>
<div class="row mt-3">
    <div class="col-6" style="margin: 0 auto;">
        <h2 class="text-center"><?php echo (!empty($data['pageTitle']))?$data['pageTitle']:false; ?></h2>
        <?php
            getMsg($msg, $msg_type);
        ?>
        <form action="" method="post">
            <div class="form-group mb-3">
                <label for="">Email</label>
                <input type="text" class="form-control" name="email", placeholder="Email....">
            </div>
            <p class="d-grid">
                <button type="submit" class="btn btn-primary btn-block">Xác nhận</button>
            </p>
        </form>
        <hr>
        <p class="text-center"><a href="<?php echo _WEB_HOST_ROOT_ADMIN.'?module=auth&action=login'; ?>">Đăng nhập hệ thống</a></p>
    </div>
</div>


<?php
layout('footer-login', 'admin');
?>