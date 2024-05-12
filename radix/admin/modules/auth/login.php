<?php
if(!defined('_INCODE')) die('Access Denied...');

$data = [
    'pageTitle' => 'Đăng nhập hệ thống'
];
layout('header-login', 'admin', $data);

if(isPost()){
    $body = getBody();

    if(!empty(trim($body['email'])) && !empty(trim($body['password']))){
        $email = trim($body['email']);
        $password = trim($body['password']);
        $checkMail = firstRaw("SELECT id, password, email FROM users WHERE email = '$email' AND status = 1");
        if($checkMail){
            $userId = $checkMail['id'];
            $passwordHash = $checkMail['password'];
            $checkPass = password_verify($password, $passwordHash);
            if($checkPass){
                $loginToken = sha1(uniqid().time());
                $dataInsert = [
                    'user_id' => $userId,
                    'token' => $loginToken,
                    'create_at' => date('Y-m-d H:i:s')
                ];
                $status = insert('login_token', $dataInsert);
                if($status){
                    setSession('login_token', $loginToken);
                    redirect('admin');
                }else{
                    setFlashData('msg', 'Hệ thống bị lỗi, vui lòng thử lại sau.');
                    setFlashData('msg_type', 'danger');
                }
            }else{
                setFlashData('msg', 'Mật khẩu không chính xác.');
                setFlashData('msg_type', 'danger');
            }
        }else{
            setFlashData('msg', 'Email không tồn tại trong hệ thống hoặc chưa được kích hoạt.');
            setFlashData('msg_type', 'danger');
        }
    }else{
        setFlashData('msg', 'Vui lòng nhập đầy đủ thông tin.');
        setFlashData('msg_type', 'danger');
    }
    redirect('admin/?module=auth&action=login');
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

            <div class="form-group mb-3">
                <label for="">Password</label>
                <input type="password" class="form-control" name="password", placeholder="Password....">
            </div>
            <p class="d-grid">
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </p>
        </form>
        <hr>
        <p class="text-center"><a href="<?php echo _WEB_HOST_ROOT_ADMIN.'?module=auth&action=forgot'; ?>">Quên mật khẩu</a></p>
    </div>
</div>


<?php
layout('footer-login', 'admin');
?>