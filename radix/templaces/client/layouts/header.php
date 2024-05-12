<?php
if (!defined('_INCODE'))
    die('Access Denied...');

    
    if(!isLogin()){
        redirect('?module=auth&action=login');
    }else{
        $userId = isLogin()['userId'];
        $userDetail = firstRaw("SELECT * FROM users WHERE id= $userId");
    }

    
    saveActivity();
    autoRemoveTokenLogin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo (!empty($data['pageTitle'])) ? $data['pageTitle'] : false; ?>
    </title>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLACE ?>/css/style.css?ver=<?php echo rand(); ?>">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLACE ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLACE ?>/css/fontawesome.min.css">
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">PiTi</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                    </ul>
                    <form class="d-flex">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="position: relative;">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Hi, <?php echo (!empty($userDetail['fullname']))?$userDetail['fullname']:'PiTi'; ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown" style="position: absolute; right: 0px;">
                                    <li><a class="dropdown-item" href="#">Thông tin cá nhân</a></li>
                                    <li><a class="dropdown-item" href="#">Đổi mật khẩu</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="<?php echo _WEB_HOST_ROOT.'?module=auth&action=logout'; ?>">Đăng xuất</a></li>
                                </ul>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </nav>