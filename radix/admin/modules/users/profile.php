<?php
if (!defined('_INCODE'))
    die('Access Denied...');

    $userId = isLogin()['user_id'];
    $userDetail = firstRaw("SELECT * FROM users WHERE id= $userId");

$data = [
    'pageTitle' => 'Cập nhật thông tin'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data); 


if (isPost()) {
    $body = getBody();
    $errors = [];

    if (empty(trim($body['fullname']))) {
        $errors['fullname']['require'] = 'Họ tên bắt buộc phải nhập.';
    } else {
        $fullname = trim($body['fullname']);
        if (mb_strlen($fullname, 'UTF-8') < 6) {
            $errors['fullname']['min'] = 'Họ tên phải >= 6 ký tự';
        }
    }

    if (empty(trim($body['email']))) {
        $errors['email']['require'] = 'Email bắt buộc phải nhập.';
    } else {
        $email = trim($body['email']);
        if (!isEmail($email)) {
            $errors['email']['isMail'] = 'Email không đúng định dạng.';
        } else {
            $sql = "SELECT email FROM users WHERE email = '$email' AND id<>$userId";
            $checkUnique = getRows($sql);
            if ($checkUnique > 0) {
                $errors['email']['unique'] = 'Email đã tồn tại trong hệ thống.';
            }
        }
    }

    if (empty($errors)) {
        $dataUpdate = [
            'fullname' => $fullname,
            'email' => $email,
            'contact_facebook' => $body['contact_facebook'],
            'contact_twitter' => $body['contact_twitter'],
            'contact_linkedin' => $body['contact_linkedin'],
            'contact_pinterest' => $body['contact_pinterest'],
            'about_content' => $body['about_content'],
            'update_at' => date('Y-m-d H:i:s')
        ];
        $updateStatus = update('users', $dataUpdate, 'id='.$userId);
        if ($updateStatus) {
            setFlashData('msg', 'Cập nhật người dùng thành công.');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Hệ thống bị lỗi, vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $body);
    }
    redirect('admin/?module=users&action=profile');
}


$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
if(!empty($userDetail) && empty($old)){
    $old = $userDetail;
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
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Họ tên</label>
                            <input type="text" class="form-control" name="fullname" placeholder="Họ tên..." 
                            value="<?php echo getOld('fullname', $old) ?>">
                            <?php echo getErrors('fullname', $errors, '<span class="error">', '</span>'); ?>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" class="form-control" name="email" placeholder="Email..." 
                            value="<?php echo getOld('email', $old) ?>">
                            <?php echo getErrors('email', $errors, '<span class="error">', '</span>'); ?>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Facebook</label>
                            <input type="text" class="form-control" name="contact_facebook" placeholder="Facebook..." 
                            value="<?php echo getOld('contact_facebook', $old) ?>">
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Twitter</label>
                            <input type="text" class="form-control" name="contact_twitter" placeholder="Twitter..." 
                            value="<?php echo getOld('contact_twitter', $old) ?>">
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Linkedin</label>
                            <input type="text" class="form-control" name="contact_linkedin" placeholder="Linkedin..." 
                            value="<?php echo getOld('contact_linkedin', $old) ?>">
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Pinterest</label>
                            <input type="text" class="form-control" name="contact_pinterest" placeholder="Pinterest..." 
                            value="<?php echo getOld('contact_pinterest', $old) ?>">
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="">Nội dung giới thiệu</label>
                        <textarea name="about_content" class="form-control" id="" cols="30" rows="10" placeholder="Nội dung giới thiệu...">
                        <?php echo getOld('about_content', $old) ?>
                        </textarea>
                    </div>
                </div>
                <p class="mt-3">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </p>
            </form>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

<?php
layout('footer', 'admin');