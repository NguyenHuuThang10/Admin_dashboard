<?php
if (!defined('_INCODE'))
die('Access Denied...');
?>
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><?php echo (!empty($data['pageTitle']))?$data['pageTitle']:'PiTi'; ?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo _WEB_HOST_ROOT_ADMIN; ?>">Trang chủ</a></li>
                        <li class="breadcrumb-item active"><?php echo (!empty($data['pageTitle']))?$data['pageTitle']:'PiTi'; ?></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->