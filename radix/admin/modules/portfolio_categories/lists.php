<?php
if (!defined('_INCODE'))
    die('Access Denied...');

$data = [
    'pageTitle' => 'Quản lý danh mục dự án'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);
$filter = '';

$body = getBody('get');
if (!empty($body['keyWord'])) {
    $keyWord = $body['keyWord'];

    if (!empty($filter) && strpos($filter, 'WHERE') > 0) {
        $operator = 'AND';
    } else {
        $operator = 'WHERE';
    }
    $filter .= " $operator name LIKE '%$keyWord%'";
}

if(!empty($body['id'])){
    $id = $body['id'];
}

if(!empty($body['view'])){
    $view = $body['view'];
}




$allCate = getRows("SELECT id FROM portfolio_categories $filter");
$perPage = _PER_PAGE;
$maxPage = ceil($allCate / $perPage);
if (!empty(getBody()['page'])) {
    $page = getBody()['page'];
    if ($page < 1 || $page > $maxPage) {
        $page = 1;
    }
} else {
    $page = 1;
}
$offset = ($page - 1) * $perPage;

$listAllCate = getRaw("SELECT * FROM portfolio_categories $filter ORDER BY create_at DESC LIMIT $offset, $perPage");

$queryString = null;
if (!empty($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = str_replace('module=portfolio_categories', '', $queryString);
    $queryString = str_replace('&page=' . $page, '', $queryString);
    $queryString = trim($queryString, '&');
    $queryString = '&' . $queryString;
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
        <hr>
        <div class="row">
            <div class="col-6">
                <?php
                    if(!empty($view) && !empty($id)){
                        require_once 'edit.php';
                    }else{
                        require_once 'add.php';
                    }
                ?>
            </div>

            <div class="col-6">
                <h4>Danh sách danh mục dự án</h4>
                <form action="">
                    <div class="row mb-3">
                        <div class="col-9">
                            <input type="search" class="form-control" name="keyWord" placeholder="Tìm kiếm..."
                                value="<?php echo (!empty($keyWord)) ? $keyWord : false; ?>">
                        </div>
                        <div class="col-3 d-grid">
                            <button type="submit" class="btn btn-success btn-block">Tìm kiếm</button>
                        </div>
                    </div>
                    <input type="hidden" name="module" value="portfolio_categories">
                </form>
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th width="5%">STT</th>
                            <th>Tiêu đề</th>
                            <th>Thời gian</th>
                            <th width="5%">Sửa</th>
                            <th width="5%">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($listAllCate)):
                            $count = 0;
                            foreach ($listAllCate as $item):
                                ?>
                                <tr>
                                    <td>
                                        <?php echo ++$count; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo getLinkAdmin('portfolio_categories', '', ['id' => $item['id'], 'view' => 'delete']); ?>"><?php echo $item['name']; ?></a>
                                        <a href="<?php echo getLinkAdmin('portfolio_categories', 'duplicate', ['id' => $item['id']]); ?>" style="padding: 0 5px;" class="btn btn-danger">Nhân bản</a>
                                    </td>
                                    <td>
                                        <?php echo getDateFormat($item['create_at'], 'H:i:s d-m:Y'); ?>
                                    </td>
                                    <td class="text-center"><a
                                            href="<?php echo getLinkAdmin('portfolio_categories', '', ['id' => $item['id'], 'view' => 'edit']); ?>"
                                            class="btn btn-warning">Sửa</a></td>
                                    <td class="text-center"><a
                                            href="<?php echo getLinkAdmin('portfolio_categories', 'delete', ['id' => $item['id']]); ?>"
                                            class="btn btn-danger">Xóa</a></td>
                                </tr>
                                <?php
                            endforeach;
                        else:
                            ?>
                            <tr class="text-center">
                                <td colspan="6">Không có dữ liệu</td>

                            </tr>
                            <?php
                        endif;
                        ?>
                    </tbody>
                </table>
                <nav aria-label="Page navigation example" class="d-flex justify-content-end">
                    <ul class="pagination">
                        <?php
                        if ($page > 1) {
                            ?>
                            <li class="page-item">
                                <a class="page-link"
                                    href="<?php echo getLinkAdmin('portfolio_categories') . $queryString . '&page=' . $page - 1; ?>"
                                    aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                        $begin = $page - 2;
                        if ($begin < 1) {
                            $begin = 1;
                        }
                        $end = $page + 2;
                        if ($end > $maxPage) {
                            $end = $maxPage;
                        }
                        for ($i = $begin; $i <= $end; ++$i):
                            ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : false; ?>"><a class="page-link"
                                    href="<?php echo getLinkAdmin('portfolio_categories') . $queryString . '&page=' . $i; ?>"><?php echo $i; ?></a></li>
                            <?php
                        endfor;
                        if ($page < $maxPage) {
                            ?>
                            <li class="page-item">
                                <a class="page-link"
                                    href="<?php echo getLinkAdmin('portfolio_categories') . $queryString . '&page=' . $page + 1; ?>"
                                    aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>

    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin');