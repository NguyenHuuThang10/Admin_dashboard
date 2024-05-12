<?php
if (!defined('_INCODE'))
    die('Access Denied...');

    $userId = isLogin()['user_id'];

$data = [
    'pageTitle' => 'Quản lý người dùng'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

$filter = '';
if (isGet()) {
    $body = getBody();
    if (!empty($body['status'])) {
        $status = $body['status'];
        if ($status == 2) {
            $statusSql = 0;
        } else {
            $statusSql = $status;
        }

        if (!empty($filter) && strpos($filter, 'WHERE') > 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator status=$statusSql";
    }

    if (!empty($body['keyWord'])) {
        $keyWord = $body['keyWord'];

        if (!empty($filter) && strpos($filter, 'WHERE') > 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator fullname LIKE '%$keyWord%'";
    }

    if (!empty($body['group_id'])) {
        $group_id = $body['group_id'];

        if (!empty($filter) && strpos($filter, 'WHERE') > 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator group_id = $group_id";
    }
}



$allUser = getRows("SELECT * FROM users $filter");
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

$listAllUser = getRaw("SELECT users.id, fullname, email, status, users.create_at, groups.name as 'group_name' FROM users INNER JOIN `groups` ON users.group_id = groups.id $filter ORDER BY users.create_at DESC LIMIT $offset, $perPage");
$allGroup = getRaw("SELECT id, name FROM `groups`");
$queryString = null;
if (!empty($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = str_replace('module=users', '', $queryString);
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
                <a href="<?php echo getLinkAdmin('users', 'add'); ?>" class="btn btn-success btn-sm">Thêm
                    người dùng <i class="fa fa-plus fa-sm"></i></a>
            </p>
            <hr>
            <form action="">
                <div class="row mb-3">
                    <div class="col-3">
                        <select name="status" class="form-control" id="">
                            <option value="0">Chọn trạng thái</option>
                            <option value="1" <?php echo (!empty($status) && $status == 1) ? 'selected' : false; ?>>Kích hoạt
                            </option>
                            <option value="2" <?php echo (!empty($status) && $status == 2) ? 'selected' : false; ?>>Chưa kích
                                hoạt</option>
                        </select>
                    </div>

                    <div class="col-3">
                        <select name="group_id" class="form-control" id="">
                            <option value="0">Chọn nhóm</option>
                            <?php
                                if(!empty($allGroup)):
                                    foreach($allGroup as $item):
                            ?>
                            <option value="<?php echo $item['id'] ?>" <?php echo (!empty($group_id) && $group_id == $item['id']) ? 'selected' : false; ?>><?php echo $item['name']; ?></option>
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
                <input type="hidden" name="module" value="users">
            </form>
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th>STT</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th width="10%">Nhóm</th>
                        <th>Thời gian</th>
                        <th width="15%">Trạng thái</th>
                        <th width="5%">Sửa</th>
                        <th width="5%">Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($listAllUser)):
                        $count = 0;
                        foreach ($listAllUser as $item):
                            ?>
                            <tr>
                                <td>
                                    <?php echo ++$count; ?>
                                </td>
                                <td>
                                    <a href="<?php echo getLinkAdmin('users', 'edit', ['id' => $item['id']]); ?>"><?php echo $item['fullname']; ?></a>   
                                </td>
                                <td>
                                    <?php echo $item['email']; ?>
                                </td>
                                <td>
                                    <?php echo $item['group_name']; ?>
                                </td>
                                <td>
                                    <?php echo (!empty($item['create_at']))?getDateFormat($item['create_at'], 'H:i:s d-m-Y'):false; ?>
                                </td>
                                <td class="text-center">
                                    <?php echo ($item['status'] == 1) ? '<a href="#" class="btn btn-success">Kích hoạt</a>' : '<a href="#" class="btn btn-danger">Chưa kích hoạt</a>' ?>
                                </td>
                                <td class="text-center"><a href="<?php echo getLinkAdmin('users', 'edit', ['id' => $item['id']]) ?>"
                                        class="btn btn-warning">Sửa</a></td>
                                <td class="text-center">
                                    <?php if($item['id'] != $userId): ?>
                                    <a href="<?php echo getLinkAdmin('users', 'delete', ['id' => $item['id']]) ?>"
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
                                href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=users' . $queryString . '&page=' . $page - 1; ?>"
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
                                href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=users' . $queryString . '&page=' . $i; ?>"><?php echo $i; ?></a></li>
                        <?php
                    endfor;
                    if ($page < $maxPage) {
                        ?>
                        <li class="page-item">
                            <a class="page-link"
                                href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=users' . $queryString . '&page=' . $page + 1; ?>"
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