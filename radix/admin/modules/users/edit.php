<?php
if (!defined('_INCODE'))
    die('Access Denied...');
$data = [
    'pageTitle' => 'Sửa người dùng'
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

$body = getBody('get');
if(!empty($body['id'])){
    $userId = $body['id'];
    $userDetail = firstRaw("SELECT * FROM users WHERE id = $userId");
    if(empty($userDetail)){
        redirect('admin?module=users');
    }
}else{
    redirect('admin?module=users');
}

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

    if (empty(trim($body['group_id']))) {
        $errors['group_id']['require'] = 'Nhóm bắt buộc phải chọn.';
    }else{
        $group_id = $body['group_id'];
    }

    if (!empty(trim($body['password']))) {
            if (empty(trim($body['confirm_password']))) {
                $errors['confirm_password']['require'] = 'Nhập lại mật khẩu bắt buộc phải nhập';
            } else {
                $confirm_pass = trim($body['confirm_password']);
                if ($confirm_pass !== $pass) {
                    $errors['confirm_password']['math'] = 'Nhập lại mật khẩu không trùng khớp';
                }
            }

    }

    if (empty($errors)) {
        $dataUpdate = [
            'fullname' => $fullname,
            'email' => $email,
            'group_id' => $group_id,
            'status' => $body['status'],
            'update_at' => date('Y-m-d H:i:s')
        ];

        if(!empty($body['password'])){
            $dataUpdate['password'] = password_hash($body['password'], PASSWORD_DEFAULT);
        }
        $updateStatus = update('users', $dataUpdate, 'id='.$userId);
        if ($updateStatus) {
            setFlashData('msg', 'Cập nhật người dùng thành công.');
            setFlashData('msg_type', 'success');
            redirect('admin?module=users');
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
    redirect('admin?module=users&action=edit&id='.$userId);
}

$allGroup = getRaw("SELECT id, name FROM `groups`");

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
if(!empty($userDetail) && empty($old)){
    $old = $userDetail;
}
?>
<section class="content">
    <div class="container-fluid">
    <?php
    getMsg($msg, $msg_type);
    ?>
    <form action="" method="post">
        <div class="row">
            <div class="col-6">
                <div class="form-group mb-3">
                    <label for="">Họ tên</label>
                    <input type="text" class="form-control" name="fullname" , placeholder="Họ tên...."
                        value="<?php echo getOld('fullname', $old) ?>">
                    <?php echo getErrors('fullname', $errors, '<span class="error">', '</span>') ?>
                </div>

                <div class="form-group mb-3">
                    <label for="">Email</label>
                    <input type="text" class="form-control" name="email" , placeholder="Email...."
                        value="<?php echo getOld('email', $old) ?>">
                    <?php echo getErrors('email', $errors, '<span class="error">', '</span>') ?>

                </div>

                <div class="form-group mb-3">
                    <label for="">Nhóm</label>
                    <select name="group_id" class="form-control" id="">
                        <option value="">Chọn Nhóm</option>
                        <?php 
                        if(!empty($allGroup)):
                            foreach($allGroup as $item):
                        ?>
                        <option value="<?php echo $item['id'] ?>" <?php echo (getOld('group_id', $old) == $item['id'])?'selected':false; ?>><?php echo $item['name'] ?></option>
                        <?php
                            endforeach;endif;
                        ?>
                    </select>
                    <?php echo getErrors('phone', $errors, '<span class="error">', '</span>') ?>

                </div>
            </div>

            <div class="col-6">
                <div class="form-group mb-3">
                    <label for="">Mật khẩu</label>
                    <input type="password" class="form-control" name="password" , placeholder="Mật khẩu....">
                    <?php echo getErrors('password', $errors, '<span class="error">', '</span>') ?>

                </div>

                <div class="form-group mb-3">
                    <label for="">Nhập lại mật khẩu</label>
                    <input type="password" class="form-control" name="confirm_password" ,
                        placeholder="Nhập lại mật khẩu....">
                    <?php echo getErrors('confirm_password', $errors, '<span class="error">', '</span>') ?>
                </div>

                <div class="form-group mb-3">
                    <label for="">Trạng thái</label>
                    <select name="status" class="form-control" id="">
                        <option value="1" <?php echo (getOld('status', $old) == 1)?'selected':false; ?>>Kích hoạt</option>
                        <option value="0" <?php echo (getOld('status', $old) == 0)?'selected':false; ?>>Chưa kích hoạt</option>
                    </select>
                    <?php echo getErrors('status', $errors, '<span class="error">', '</span>') ?>
                </div>
            </div>
        </div>
        <p>
            <button type="submit" class="btn btn-primary">Sửa</button>
            <a href="<?php echo getLinkAdmin('users'); ?>" class="btn btn-success">Quay lại</a>
        </p>
        <input type="hidden" name="id" value="<?php echo $userId; ?>">
    </form>
</div><!-- /.container-fluid -->
</section>
<?php
layout('footer', 'admin');