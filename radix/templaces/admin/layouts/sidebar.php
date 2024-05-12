<?php
if(!defined('_INCODE')) die('Access denied...');

$userId = isLogin()['user_id'];
$userDetail = firstRaw("SELECT * FROM users WHERE id= $userId");
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo _WEB_HOST_ROOT_ADMIN; ?>" class="brand-link">
        <span class="brand-text font-weight-light"><b>PiTi Admin</b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo _WEB_HOST_ADMIN_TEMPLACE; ?>/assets/img/user2-160x160.jpg"
                    class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="<?php echo getLinkAdmin('users', 'profile'); ?>" class="d-block"><?php echo $userDetail['fullname'] ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?php echo getLinkAdmin('dashboard'); ?>" class="nav-link <?php echo (activeMenuSideBar('dashboard'))?'active':false; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Tổng quan
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview <?php echo activeMenuSideBar('services')?'menu-open':false; ?>">
                    <a href="#" class="nav-link <?php echo activeMenuSideBar('services')?'active':false; ?>">
                        <i class="nav-icon fab fa-servicestack"></i>
                        <p>
                            Quản lý dịch vụ
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo getLinkAdmin('services'); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh sách</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo getLinkAdmin('services', 'add'); ?>" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thêm mới</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview <?php echo (activeMenuSideBar('portfolios') || activeMenuSideBar('portfolio_categories'))?'menu-open':false; ?>">
                    <a href="#" class="nav-link <?php echo (activeMenuSideBar('portfolios') || activeMenuSideBar('portfolio_categories'))?'active':false; ?>">
                        <i class="nav-icon fas fa-star"></i>
                        <p>
                            Quản lý dự án
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo getLinkAdmin('portfolios'); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh sách</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo getLinkAdmin('portfolios', 'add'); ?>" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thêm mới</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo getLinkAdmin('portfolio_categories'); ?>" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh mục dự án</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview <?php echo (activeMenuSideBar('blog') || activeMenuSideBar('blog_categories'))?'menu-open':false; ?>">
                    <a href="#" class="nav-link <?php echo (activeMenuSideBar('blog') || activeMenuSideBar('blog_categories'))?'active':false; ?>">
                    <i class="nav-icon fab fa-blogger"></i>
                        <p>
                            Quản lý Blog
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo getLinkAdmin('blog'); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh sách</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo getLinkAdmin('blog', 'add'); ?>" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thêm mới</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo getLinkAdmin('blog_categories'); ?>" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh mục blog</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview <?php echo activeMenuSideBar('pages')?'menu-open':false; ?>">
                    <a href="#" class="nav-link <?php echo activeMenuSideBar('pages')?'active':false; ?>">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Quản lý trang
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo getLinkAdmin('pages'); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh sách</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo getLinkAdmin('pages', 'add'); ?>" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thêm mới</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview <?php echo activeMenuSideBar('users')?'menu-open':false; ?>">
                    <a href="#" class="nav-link <?php echo activeMenuSideBar('users')?'active':false; ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Danh mục người dùng
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo getLinkAdmin('users'); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh sách</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo getLinkAdmin('users', 'add'); ?>" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thêm mới</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview <?php echo activeMenuSideBar('group')?'menu-open':false; ?>">
                    <a href="#" class="nav-link <?php echo activeMenuSideBar('group')?'active':false; ?>">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Danh mục nhóm
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo getLinkAdmin('group'); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh sách</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo getLinkAdmin('group', 'add'); ?>" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thêm mới</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<div class="content-wrapper">