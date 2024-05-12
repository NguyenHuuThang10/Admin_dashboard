<?php
if(!defined('_INCODE')) die('Access Denied...');

if(isLogin()){
    $token = getSession('login_token');
    deleted('login_token', "token='$token'");
    removeSession('login_token');
    redirect('admin/?module=auth&action=login');
}