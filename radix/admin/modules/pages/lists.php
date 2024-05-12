<?php
if (!defined('_INCODE'))
    die('Access Denied...');

$data = [
    'pageTitle' => 'Danh sách trang'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data); 
$filter = '';
if(isGet()){
    $body = getBody();
    if(!empty($body['keyWord'])){
        $keyWord = $body['keyWord'];

        if(!empty($filter) && strpos($filter, 'WHERE')>0){
            $operator = 'AND';
        }else{
            $operator = 'WHERE';
        }
        $filter .= " $operator title LIKE '%$keyWord%'";
    }

    if (!empty($body['user_id'])) {
        $user_id = $body['user_id'];

        if (!empty($filter) && strpos($filter, 'WHERE') > 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator user_id = $user_id";
    }
}



$allPagesNum = getRows("SELECT id FROM pages $filter");
$perPage = _PER_PAGE;
$maxPage = ceil($allPagesNum/$perPage);
if(!empty(getBody()['page'])){
    $page = getBody()['page'];
    if($page < 1 || $page > $maxPage){
        $page = 1;
    }
}else{
    $page = 1;
}
$offset = ($page-1)*$perPage;

$listAllPages = getRaw("SELECT pages.id, title, pages.create_at, users.id as user_id, fullname FROM `pages` INNER JOIN users ON pages.user_id = users.id $filter ORDER BY pages.create_at DESC LIMIT $offset, $perPage");

$allUsers = getRaw("SELECT id, fullname, email FROM users ORDER BY fullname DESC");


$queryString = null;
if (!empty($_SERVER['QUERY_STRING'])){
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = str_replace('module=pages', '', $queryString);
    $queryString = str_replace('&page='.$page, '', $queryString);
    $queryString = trim($queryString, '&');
    $queryString = '&'.$queryString;
}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');

?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
    <?php
        getMsg($msg, $msg_type);
    ?>
    <p>
        <a href="<?php echo getLinkAdmin('pages', 'add'); ?>" class="btn btn-success btn-lg">Thêm trang <i class="fa fa-plus"></i></a>
    </p>
    <hr>
    <form action="">
        <div class="row mb-3">
            <div class="col-3">
                <select name="user_id" class="form-control" id="">
                    <option value="0">Chọn người đăng</option>
                    <?php
                                if(!empty($allUsers)):
                                    foreach($allUsers as $item):
                            ?>
                            <option value="<?php echo $item['id'] ?>" <?php echo (!empty($user_id) && $user_id == $item['id']) ? 'selected' : false; ?>><?php echo $item['fullname'].' ('.$item['email'].')'; ?></option>
                            <?php
                                    endforeach;endif;
                            ?>
                </select>
            </div>
            <div class="col-6">
                <input type="search" class="form-control" name="keyWord" placeholder="Tìm kiếm..." value="<?php echo (!empty($keyWord))?$keyWord:false; ?>">
            </div>
            <div class="col-3 d-grid">
                <button type="submit" class="btn btn-success btn-block">Tìm kiếm</button>
            </div>
        </div>
        <input type="hidden" name="module" value="pages">
    </form>
        <table class="table table-bordered">
        <thead>
            <tr class="text-center">
                <th width="5%">STT</th>
                <th>Tiêu đề</th>
                <th width="15%">Đăng bởi</th>
                <th width="10%">Thời gian</th>
                <th width="10%">Xem</th>
                <th width="5%">Sửa</th>
                <th width="5%">Xóa</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($listAllPages)):
                $count = 0;
                foreach ($listAllPages as $item):
                    ?>
                    <tr>
                        <td>
                            <?php echo ++$count; ?>
                        </td>
                        <td>
                            <a href="<?php echo getLinkAdmin('pages', 'edit', ['id' => $item['id']]); ?>"><?php echo $item['title']; ?></a>
                            <a href="<?php echo getLinkAdmin('pages', 'duplicate', ['id' => $item['id']]); ?>" style="padding: 0 5px;" class="btn btn-danger">Nhân bản</a>
                        </td>
                        <td>
                        <a href="<?php echo getLinkAdmin('pages', '', ['user_id' => $item['user_id']]); ?>"><?php echo $item['fullname']; ?></a>
                    </td>
                        <td>
                            <?php echo getDateFormat($item['create_at'], 'H:i:s d-m:Y'); ?>
                        </td>
                        <td class="text-center">
                            <a href="#" class="btn btn-primary" target="_blank">Xem</a>
                        </td>
                        <td class="text-center"><a href="<?php echo getLinkAdmin('pages', 'edit', ['id' => $item['id']]); ?>" class="btn btn-warning">Sửa</a></td>
                        <td class="text-center"><a href="<?php echo getLinkAdmin('pages', 'delete', ['id' => $item['id']]); ?>" class="btn btn-danger">Xóa</a></td>
                    </tr>
                    <?php
                endforeach;
            else:
                ?>
                <tr class="text-center">
                    <td colspan="7">Không có dữ liệu</td>

                </tr>
                <?php
            endif;
            ?>
        </tbody>
    </table>
    <nav aria-label="Page navigation example" class="d-flex justify-content-end">
        <ul class="pagination">
            <?php
                if($page > 1){
            ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo getLinkAdmin('pages').$queryString.'&page='.$page-1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
            <?php      
                }
            ?>
            <?php
                $begin = $page - 2;
                if($begin < 1){
                    $begin = 1;
                }
                $end = $page + 2;
                if($end > $maxPage){
                    $end = $maxPage;
                }
                for($i=$begin; $i<=$end; ++$i):
            ?>
            <li class="page-item <?php echo ($i == $page)?'active':false; ?>"><a class="page-link" href="<?php echo getLinkAdmin('pages').$queryString.'&page='.$i; ?>"><?php echo $i; ?></a></li>
            <?php
                endfor;
                if($page < $maxPage){
            ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo getLinkAdmin('pages').$queryString.'&page='.$page+1; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            <?php
                }
            ?>
        </ul>
    </nav>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

<?php
layout('footer', 'admin');