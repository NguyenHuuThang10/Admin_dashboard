<?php
if (!defined('_INCODE'))
    die('Access Denied...');

    $userId = isLogin()['user_id'];

$data = [
    'pageTitle' => 'Quản lý blog'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

$filter = '';
if (isGet()) {
    $body = getBody();
    if (!empty($body['keyWord'])) {
        $keyWord = $body['keyWord'];

        if (!empty($filter) && strpos($filter, 'WHERE') > 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator (blog.title LIKE '%$keyWord%' OR blog.content LIKE '%$keyWord%')";
    }

    if (!empty($body['cate_id'])) {
        $cate_id = $body['cate_id'];

        if (!empty($filter) && strpos($filter, 'WHERE') > 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator category_id = $cate_id";
    }

    if (!empty($body['user_id'])) {
        $user_id = $body['user_id'];

        if (!empty($filter) && strpos($filter, 'WHERE') > 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator blog.user_id = $user_id";
    }
}



$allUser = getRows("SELECT * FROM blog $filter");
$perPage = 2;
$maxPage = ceil($allUser / $perPage);
if (!empty(getBody()['page'])) {
    $page = getBody()['page'];
    if ($page < 1 || $page > $maxPage) {
        $page = 1;
    }
} else {
    $page = 1;
}
$offset = ($page - 1) * $perPage;

$listAllBlog = getRaw("SELECT blog.view_count, category_id, blog.user_id, users.email, users.fullname, blog.id, blog.title, blog.create_at, blog_categories.name as 'cate_name' FROM blog INNER JOIN `blog_categories` ON blog.category_id  = blog_categories.id INNER JOIN users ON users.id = blog.user_id  $filter ORDER BY blog.create_at DESC LIMIT $offset, $perPage");
$allCate = getRaw("SELECT id, name FROM blog_categories");
$allUser = getRaw("SELECT * from users");
$queryString = null;
if (!empty($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = str_replace('module=blog', '', $queryString);
    $queryString = str_replace('&page=' . $page, '', $queryString);
    $queryString = trim($queryString, '&');
    $queryString = '&' . $queryString;
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>
<section class="content">
    <div class="container-fluid">
        <div class="mt-3">
            <?php
            getMsg($msg, $msg_type);
            ?>
            <p>
                <a href="<?php echo getLinkAdmin('blog', 'add'); ?>" class="btn btn-success btn-sm">Thêm
                    Blog <i class="fa fa-plus fa-sm"></i></a>
            </p>
            <hr>
            <form action="">
                <div class="row mb-3">
                    <div class="col-3">
                    <select name="user_id" class="form-control" id="">
                            <option value="0">Chọn người đăng</option>
                            <?php
                                if(!empty($allUser)):
                                    foreach($allUser as $item):
                            ?>
                            <option value="<?php echo $item['id'] ?>" <?php echo (!empty($user_id) && $user_id == $item['id']) ? 'selected' : false; ?>><?php echo $item['fullname'] . ' ('.$item['email'].')'; ?></option>
                            <?php
                                    endforeach;endif;
                            ?>
                        </select>
                    </div>

                    <div class="col-3">
                        <select name="cate_id" class="form-control" id="">
                            <option value="0">Chọn danh mục dự án</option>
                            <?php
                                if(!empty($allCate)):
                                    foreach($allCate as $item):
                            ?>
                            <option value="<?php echo $item['id'] ?>" <?php echo (!empty($cate_id) && $cate_id == $item['id']) ? 'selected' : false; ?>><?php echo $item['name']; ?></option>
                            <?php
                                    endforeach;endif;
                            ?>
                        </select>
                    </div>

                    <div class="col-4">
                        <input type="search" class="form-control" name="keyWord" placeholder="Tìm kiếm..."
                            value="<?php echo (!empty($keyWord)) ? $keyWord : false; ?>">
                    </div>

                    <div class="col-2">
                        <button type="submit" class="btn btn-success btn-block">Tìm kiếm</button>
                    </div>
                </div>
                <input type="hidden" name="module" value="blog">
            </form>
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th>STT</th>
                        <th>Tên</th>
                        <th>Đăng bởi</th>
                        <th width="15%">Danh mục</th>
                        <th>Thời gian</th>
                        <th width="5%">Sửa</th>
                        <th width="5%">Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($listAllBlog)):
                        $count = 0;
                        foreach ($listAllBlog as $item):
                            ?>
                            <tr>
                                <td>
                                    <?php echo ++$count; ?>
                                </td>
                                <td>
                                    <a href="<?php echo getLinkAdmin('blog', 'edit', ['id' => $item['id']]); ?>"><?php echo $item['title']; ?></a> <br>
                                    <a href="<?php echo getLinkAdmin('blog', 'duplicate', ['id' => $item['id']]) ?>" class="btn btn-danger btn-sm" style="padding: 0 5px;">Nhân bản</a>  
                                    <a href="#" style="padding: 0 5px;" class="btn btn-success btn-sm"><?php echo $item['view_count'] ?> lượt xem</a>
                                    <a href="#" style="padding: 0 5px;" class="btn btn-primary btn-sm" target="_blank">Xem</a>
                    
                                </td>
                                <td>
                                    <a href="<?php echo getLinkAdmin('blog', '', ['user_id' => $item['user_id']]) ?>"><?php echo $item['fullname'] ?></a>
                                </td>
                                <td>
                                    <a href="<?php echo getLinkAdmin('blog', '', ['cate_id' => $item['category_id']]) ?>"><?php echo $item['cate_name']; ?></a>
                                </td>
                                <td>
                                    <?php echo (!empty($item['create_at']))?getDateFormat($item['create_at'], 'H:i:s d-m-Y'):false; ?>
                                </td>
                                <td class="text-center"><a href="<?php echo getLinkAdmin('blog', 'edit', ['id' => $item['id']]) ?>"
                                        class="btn btn-warning">Sửa</a></td>
                                <td class="text-center">
                                    <?php if($item['id'] != $userId): ?>
                                    <a href="<?php echo getLinkAdmin('blog', 'delete', ['id' => $item['id']]) ?>"
                                        class="btn btn-danger">Xóa</a>
                                    <?php endif; ?>
                                    </td>
                            </tr>
                            <?php
                        endforeach;else:
                        ?>
                        <tr class="text-center">
                            <td colspan="7">Không có dữ liệu</td>

                        </tr>
                        <?php
                    endif;
                    ?>
                </tbody>
            </table>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <?php
                    if ($page > 1) {
                        ?>
                        <li class="page-item">
                            <a class="page-link"
                                href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=blog' . $queryString . '&page=' . $page - 1; ?>"
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
                                href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=blog' . $queryString . '&page=' . $i; ?>"><?php echo $i; ?></a></li>
                        <?php
                    endfor;
                    if ($page < $maxPage) {
                        ?>
                        <li class="page-item">
                            <a class="page-link"
                                href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=blog' . $queryString . '&page=' . $page + 1; ?>"
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

    </div><!-- /.container-fluid -->
</section>
<?php
layout('footer', 'admin', $data);
?>