<?php
if(!defined('_INCODE')) die('Access Denied...');
if(isLogin()){
    redirect('admin');
}
autoRemoveTokenLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo (!empty($data['pageTitle']))?$data['pageTitle']:false; ?></title>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_ADMIN_TEMPLACE ?>/assets/css/style.css?ver=<?php echo rand(); ?>">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_ADMIN_TEMPLACE; ?>/assets/css/adminlte.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_ADMIN_TEMPLACE; ?>/assets/plugins/fontawesome-free/css/all.min.css">
</head>
<body>
<div class="container">


