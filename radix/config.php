<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
const _MODULE_DEFAULT = 'home';
const _ACTION_DEFAULT = 'lists';
const _INCODE = true;

const _PER_PAGE = 2;

const _MODULE_DEFAULT_ADMIN = 'dashboard';


define('_WEB_HOST_ROOT', 'http://'.$_SERVER['HTTP_HOST'].'/Admin_dashboard/radix');
define('_WEB_HOST_TEMPLACE', _WEB_HOST_ROOT.'/templaces/client');

define('_WEB_PATH_ROOT', __DIR__);
define('_WEB_PATH_TEMPLACE', _WEB_PATH_ROOT.'/templaces');

define('_WEB_HOST_ROOT_ADMIN', _WEB_HOST_ROOT.'/admin');
define('_WEB_HOST_ADMIN_TEMPLACE', _WEB_HOST_ROOT.'/templaces/admin');

const _HOST = 'localhost';
const _USER = 'root';
const _PASS = '';
const _DB = 'phponline_radix';
const _DRIVER = 'mysql';