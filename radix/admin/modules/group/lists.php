<?php
if (!defined('_INCODE'))
    die('Access Denied...');

$data = [
    'pageTitle' => 'Danh sách nhóm'
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
        $filter .= " $operator name LIKE '%$keyWord%'";
    }
}



$allGroup = getRows("SELECT id FROM `groups` $filter");
$perPage = 2;
$maxPage = ceil($allGroup/$perPage);
if(!empty(getBody()['page'])){
    $page = getBody()['page'];
    if($page < 1 || $page > $maxPage){
        $page = 1;
    }
}else{
    $page = 1;
}
$offset = ($page-1)*$perPage;

$listAllGroup = getRaw("SELECT * FROM `groups` $filter ORDER BY create_at DESC LIMIT $offset, $perPage");

$queryString = null;
if (!empty($_SERVER['QUERY_STRING'])){
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = str_replace('module=group', '', $queryString);
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
        <a href="<?php echo getLinkAdmin('group', 'add'); ?>" class="btn btn-success btn-lg">Thêm nhóm <i class="fa fa-plus"></i></a>
    </p>
    <hr>
    <form action="">
        <div class="row mb-3">
            <div class="col-9">
                <input type="search" class="form-control" name="keyWord" placeholder="Tìm kiếm..." value="<?php echo (!empty($keyWord))?$keyWord:false; ?>">
            </div>
            <div class="col-3 d-grid">
                <button type="submit" class="btn btn-success btn-block">Tìm kiếm</button>
            </div>
        </div>
        <input type="hidden" name="module" value="group">
    </form>
        <table class="table table-bordered">
        <thead>
            <tr class="text-center">
                <th width="5%">STT</th>
                <th>Họ tên</th>
                <th>Thời gian</th>
                <th width="15%">Phân quyền</th>
                <th width="5%">Sửa</th>
                <th width="5%">Xóa</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($listAllGroup)):
                $count = 0;
                foreach ($listAllGroup as $item):
                    ?>
                    <tr>
                        <td>
                            <?php echo ++$count; ?>
                        </td>
                        <td>
                            <a href="<?php echo getLinkAdmin('group', 'edit', ['id' => $item['id']]); ?>"><?php echo $item['name']; ?></a>
                        </td>
                        <td>
                            <?php echo getDateFormat($item['create_at'], 'H:i:s d-m:Y'); ?>
                        </td>
                        <td class="text-center">
                            <a href="" class="btn btn-primary">Phân quyền</a>
                        </td>
                        <td class="text-center"><a href="<?php echo getLinkAdmin('group', 'edit', ['id' => $item['id']]); ?>" class="btn btn-warning">Sửa</a></td>
                        <td class="text-center"><a href="<?php echo getLinkAdmin('group', 'delete', ['id' => $item['id']]); ?>" class="btn btn-danger">Xóa</a></td>
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
                        <a class="page-link" href="<?php echo getLinkAdmin('group').$queryString.'&page='.$page-1; ?>" aria-label="Previous">
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
            <li class="page-item <?php echo ($i == $page)?'active':false; ?>"><a class="page-link" href="<?php echo getLinkAdmin('group').$queryString.'&page='.$i; ?>"><?php echo $i; ?></a></li>
            <?php
                endfor;
                if($page < $maxPage){
            ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo getLinkAdmin('group').$queryString.'&page='.$page+1; ?>" aria-label="Next">
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