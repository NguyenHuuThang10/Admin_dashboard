<?php
if(!defined('_INCODE')) die('Access Denied...');
layout('header-login');
?>
<div class="row mt-3">
    <div class="col" style="margin: 0 auto;">
        <h2 class="text-center">Lỗi liên quan đến cơ sở dữ liệu</h2>
        <hr>
        <p class="text-center"> <?php echo $e->getMessage() ?></p>
        <p class="text-center"><?php echo 'File: '. $e->getFile().' - Line: '. $e->getLine() ?></p>
    </div>
</div>

<?php
layout('footer-login');