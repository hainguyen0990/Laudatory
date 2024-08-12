<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

use NukeViet\Core\Language;

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

// Xóa nhiều
if ($nv_Request->get_title('delete_all', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_typed_array('idcheck', 'post', 'int', []);
    if (empty($id)) {
        $res = [
            'res' => 'error',
            'mess' => $nv_Lang->getModule('error_required_id')
        ];
        nv_jsonOutput($res);
    }

    // Kiểm tra xem có phòng ban nào trong danh sách ID có nhân viên không
    $id_list = implode(',', $id);
    $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_employed_department WHERE id_department IN (' . $id_list . ')';
    $result = $db->query($sql)->fetchColumn();

    if ($result == 0) {
        $stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE id IN (' . $id_list . ')');
        $stmt->execute();
        $res = [
            'res' => 'success',
            'mess' => $nv_Lang->getModule('delete_success')
        ];
        nv_jsonOutput($res);
    } else {
        $res = [
            'res' => 'error',
            'mess' => $nv_Lang->getModule('error_do_not_delete_department')
        ];
        nv_jsonOutput($res);
    }
}

// base_url chuyển về trang đầu
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$request = $nv_Request->get_int('request', 'post,get', 0);

// base_url_employed_department chuyển đến trang employed_department
$base_url_employed_department = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=employed_department';
$page_title = $nv_Lang->getModule('department');
$error = [];
$data = [
    'id' => 0,
    'name_department' => '',
    'id_department_head' => -1,
    'id_position' => -1,
    'description' => '',
    'phone' => '',
    'email' => '',
];

// truy vấn chức vụ
$db->sqlreset()
    ->select('id,name_position')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_position');
$sth = $db->query($db->sql());
$arr_position = [];
while ($row = $sth->fetch()) {
    $arr_position[$row['id']] = $row;
}

// lấy tên trưởng phòng
$db->sqlreset()
    ->select('admin_id')
    ->from(NV_AUTHORS_GLOBALTABLE);
$sth = $db->query($db->sql());
$admin = [];
while ($row = $sth->fetch()) {
    $admin[] = $row['admin_id'];
}

// lấy thông tin trưởng phòng
$arr_admin = [];
if (!empty($admin)) {
    $db->sqlreset()
        ->select('userid, username, last_name, first_name, email')
        ->from(NV_USERS_GLOBALTABLE)
        ->where('userid IN (' . implode(',', $admin) . ')'); // implode: chuyển mảng thành chuỗi
    if (!defined('NV_IS_SPADMIN') and defined('NV_IS_MODADMIN')) {
        $db->where('userid = ' . $admin_info['userid']);
    }
    $sth = $db->query($db->sql());
    while ($row = $sth->fetch()) {
        $arr_admin[$row['userid']] = $row;
    }
}

// lấy số điện thoại trưởng phòng
if (!empty($arr_admin)) {
    $user_ids = array_keys($arr_admin);
    $db->sqlreset()
        ->select('userid, phone')
        ->from(NV_USERS_GLOBALTABLE . '_info')
        ->where('userid IN (' . implode(',', $user_ids) . ')');
    $sth = $db->query($db->sql());
    while ($row = $sth->fetch()) {
        $arr_admin[$row['userid']]['phone'] = $row['phone'];
    }
}

// lấy thông tin phòng ban
$arr_department = [];
$db->sqlreset()
    ->select('id , name_department , description')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_department');
$sth = $db->query($db->sql());
while ($row = $sth->fetch()) {
    $arr_department[] = $row['name_department'];
}

$data['id'] = $nv_Request->get_int('id', 'post,get', 0);
if ($nv_Request->isset_request('submit', 'get,post')) {
    $data['name_department'] = $nv_Request->get_title('name_department', 'post,get', '');
    $data['id_department_head'] = implode(',', $nv_Request->get_typed_array('id_department_head', 'post', 'int', [])); // implode: chuyển mảng thành chuỗi
    $data['id_position'] = $nv_Request->get_int('id_position', 'post,get', 0);
    $data['description'] = $nv_Request->get_textarea('description', '', NV_ALLOWED_HTML_TAGS);
    $data['phone'] = $nv_Request->get_title('phone', 'post,get', '');
    $data['email'] = $nv_Request->get_title('email', 'post,get', '');

    if (preg_match('/^0[1-9][0-9]{8}$/', $data['phone'], $m)) {
        $data['phone'] = $m[0];
    } else {
        $error[] = $nv_Lang->getModule('error_required_phone');
    }

    if (empty($data['name_department'])) {
        $error[] = $nv_Lang->getModule('error_required_name');
    }

    if ($data['id_department_head'] < 0) {
        $error[] = $nv_Lang->getModule('error_required_name_department_head');
    }

    if ($data['id_position'] < 0) {
        $error[] = $nv_Lang->getModule('error_required_position');
    }

    if (empty($data['phone'])) {
        $error[] = $nv_Lang->getModule('error_required_name_phone');
    }

    if (empty($data['email'])) {
        $error[] = $nv_Lang->getModule('error_required_name_email');
    }

    $sql_check_admin = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE id_department_head = ' . $data['id_department_head'];
    $result_check_admin = $db->query($sql_check_admin)->fetchColumn();
    if ($result_check_admin > 0) {
        $error[] = $nv_Lang->getModule('error_required_name_department_head');
    }

    // Thêm và sửa dữ liệu
    if (empty($error)) {
        try {
            if ($data['id'] > 0) {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_department SET 
                        name_department = :name_department,  
                        id_department_head = :id_department_head,            
                        id_position = :id_position,
                        description = :description, 
                        phone = :phone,
                        email = :email,
                        update_time = :update_time
                        WHERE id = :id ');
                $stmt->bindValue(':update_time', NV_CURRENTTIME);
                $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
                $stmt->bindParam(':name_department', $data['name_department'], PDO::PARAM_STR);
                $stmt->bindParam(':id_department_head', $data['id_department_head'], PDO::PARAM_STR);
                $stmt->bindParam(':id_position', $data['id_position'], PDO::PARAM_INT);
                $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR, strlen($data['description']));
                $stmt->bindParam(':phone', $data['phone'], PDO::PARAM_STR);
                $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
                $stmt->execute();
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit Department', 'ID: ' . $data['id'], $admin_info['userid']);
            } else {
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_department (name_department, id_department_head, id_position, description, phone, email, addtime) VALUES (:name_department, :id_department_head, :id_position, :description, :phone, :email, :addtime)');
                $stmt->bindValue(':addtime', NV_CURRENTTIME);
                $stmt->bindParam(':name_department', $data['name_department'], PDO::PARAM_STR);
                $stmt->bindParam(':id_department_head', $data['id_department_head'], PDO::PARAM_STR);
                $stmt->bindParam(':id_position', $data['id_position'], PDO::PARAM_INT);
                $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR, strlen($data['description']));
                $stmt->bindParam(':phone', $data['phone'], PDO::PARAM_STR);
                $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
                $stmt->execute();
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Add Department', ' ', $admin_info['userid']);
            }
            $nv_Cache->delMod($module_name);
            if ($request) {
                nv_redirect_location($base_url . '&request=1');
            }
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
        }
    }
}

if ($nv_Request->isset_request('action', 'get') and $nv_Request->isset_request('id', 'get')) {
    if ($data['id'] > 0) {
        if ($nv_Request->get_title('checksess', 'post,get', '') === md5($data['id'] . NV_CHECK_SESSION)) {
            $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_employed_department WHERE id_department=' . $data['id'];
            $result = $db->query($sql)->fetchColumn();
            if ($result == 0) {
                $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE id = ' . $data['id']);
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete Department', 'ID: ' . $data['id'], $admin_info['userid']);
                $nv_Cache->delMod($module_name);
                if ($request) {
                    nv_redirect_location($base_url . '&request=1');
                }
            } else {
                $error[] = $nv_Lang->getModule('error_do_not_delete_department');
            }
        }
    }
}

if ($nv_Request->get_title('add_position','post,get') === NV_CHECK_SESSION) {
    $data1['name_position'] = $nv_Request->get_title('name_position', 'post', '');
    $data1['description'] = $nv_Request->get_title('description_position', 'post', '');

    if (empty($data1['name_position'])) {
        $res = [
            'res' => 'error',
            'mess' => $nv_Lang->getModule('error_required_name_position')
        ];
        nv_jsonOutput($res);
    }

    // Thêm dữ liệu
    $sql = 'SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_position';
    $data1['weight'] = $db->query($sql)->fetchColumn() + 1;
    $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_position (name_position, description,weight, addtime) VALUES (:name_position, :description,:weight, :addtime)');
    $stmt->bindValue(':addtime', NV_CURRENTTIME);
    $stmt->bindParam(':name_position', $data1['name_position'], PDO::PARAM_STR);
    $stmt->bindParam(':description', $data1['description'], PDO::PARAM_STR, strlen($data['description']));
    $stmt->bindParam(':weight', $data1['weight'], PDO::PARAM_INT);
    $stmt->execute();
    $lastInsertId = $db->lastInsertId();
    nv_insert_logs(NV_LANG_DATA, $module_name, 'Add POSITION', ' ', $admin_info['userid']);
    $nv_Cache->delMod($module_name);
    $res = [
        'res' => 'success',
        'mess' => $nv_Lang->getModule('add_success'),
        'position' => [
            'id' => $lastInsertId,
            'name_position' => $data1['name_position'],
        ]
    ];
    nv_jsonOutput($res);
}

if ($data['id'] > 0) {
    $data = $db->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_department')
        ->where('id=' . $data['id']);
    $data = $db->query($db->sql())->fetch();
    if (empty($data)) {
        nv_redirect_location($base_url);
    }
}

// Truy vấn hiển thị trong site
$page = $nv_Request->get_int('page', 'get', 1);
$perpage = 20;
$where = [];
$arr_search = [
    'search' => 0,
    'q' => '',
    't' => 0,
];

if ($nv_Request->isset_request('search', 'get')) {
    $arr_search['search'] = 1;
    $arr_search['q'] = $nv_Request->get_title('q', 'get', '');
    $arr_search['t'] = $nv_Request->get_int('ceo', 'get', 0);
    if (!empty($arr_search['q'])) {
        $where[] = "name_department LIKE '%" . $db->dblikeescape($arr_search['q']) . "%' OR phone LIKE '%" . $db->dblikeescape($arr_search['q']) . "%' OR email LIKE '%" . $db->dblikeescape($arr_search['q']) . "%'";
        $base_url .= '&search=1&q=' . $arr_search['q'] ;
    }
    if ($arr_search['t'] > 0) {
        $where[] = 'id_department_head = ' . $arr_search['t'];
        $base_url .= '&t=' . $arr_search['t'];
    }
}

$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_department');
if (!empty($where)) {
    $db->where(implode(' AND ', $where));
}
$total = $db->query($db->sql())->fetchColumn();

$db->select('*')
    ->order('id ASC')
    ->limit($perpage)
    ->offset(($page - 1) * $perpage);

if(!defined('NV_IS_SPADMIN') and  defined('NV_IS_MODADMIN')) {
    $db->where('id_department_head = ' . $admin_info['userid']);
}

$sth = $db->query($db->sql());
$list = [];
$id_header = [];
$id_department = [];
while ($row = $sth->fetch()) {
    $list[] = $row;
    $id_header = array_merge($id_header, explode(',', $row['id_department_head']));
    $id_department[$row['id']] = $row['id'];
}

// tổng số nhân viên
$total_employee = [];
if (!empty($id_department)) {
    $db->sqlreset()
        ->select('id_department, COUNT(id_employed) as total_employee')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_employed_department')
        ->group('id_department');
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        $total_employee[$row['id_department']] = $row['total_employee'];
    }
}

// lấy tên trưởng phòng
$name_header = [];
if (!empty($id_header)) {
    $db->sqlreset()
        ->select('userid, username, last_name, first_name')
        ->from(NV_USERS_GLOBALTABLE)
        ->where('userid IN (' . implode(',', array_unique($id_header)) . ')');
    $sth = $db->query($db->sql());
    while ($row = $sth->fetch()) {
        $name_header[$row['userid']]['full_name'] = nv_show_name_user($row['first_name'], $row['last_name']);
    }
}

$xtpl = new XTemplate('department.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', Language::$lang_module);
$xtpl->assign('GLANG', Language::$lang_global);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('SEARCH', $arr_search);
$xtpl->assign('BASE_URL', $base_url);
$xtpl->assign('URL_POSITION', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=position');
$xtpl->assign('URL_BACK', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=category_laudatory');

if($request) {
    foreach ($arr_position as $position) {
        $position['selected'] = $position['id'] == $data['id_position'] ? 'selected="selected"' : '';
        $xtpl->assign('POSITION', $position);
        $xtpl->parse('main.request.select_position.loop');
    }
    $xtpl->parse('main.request.select_position');

    if (!empty($arr_admin)) {
        foreach ($arr_admin as $admin) {
            $admin['selected'] = in_array($admin['userid'], explode(',', $data['id_department_head'])) ? 'selected="selected"' : '';
            $xtpl->assign('ADMIN',$admin);
            $xtpl->parse('main.request.select_admin.loop');
        }
        $xtpl->parse('main.request.select_admin');
    }
    $xtpl->assign('DATA', $data);
    $xtpl->parse('main.request');
} else {
    if (!empty($arr_admin)) {
        foreach ($arr_admin as $admin) {
            $admin['selected'] = $arr_search['t'] == $admin['userid'] ? 'selected="selected"' : '';
            $xtpl->assign('ADMIN', $admin);
            $xtpl->parse('main.search.select_admin.loop');
        }
        $xtpl->parse('main.search.select_admin');
        $xtpl->parse('main.search');
    }
}

if (!empty($list)) {
    $i = ($page - 1) * $perpage;
    foreach ($list as $row) {
        $row['stt'] = ++$i;
        if (isset($row['id_department_head'])) {
            $id_department_heads = explode(',', $row['id_department_head']);
            $name_admin = [];
            foreach ($id_department_heads as $id) {
                if (isset($name_header[$id])) {
                    $name_admin[] = $name_header[$id]['full_name'];
                }
            }
            $row['name_department_head'] = implode(' - ', $name_admin);
        } else {
            $row['name_department_head'] = '';
        }
        $row['position'] = !empty($arr_position[$row['id_position']]) ? $arr_position[$row['id_position']]['name_position'] : '';
        $row['total_employee'] = !empty($total_employee[$row['id']]) ? $total_employee[$row['id']] : 0;
        $row['url_employed_department'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=employed_department&amp;id_department=' . $row['id'];
        $row['url_edit'] = $base_url . '&request=1' . '&id=' . $row['id'];
        $row['url_delete'] = $base_url . '&id=' . $row['id'] . '&action=delete&checksess=' . md5($row['id'] . NV_CHECK_SESSION);
        if ($request) {
            $row['url_delete'] .= '&request=1';
        }
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop');
    }
}

if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$generate_page = nv_generate_page($base_url, $total, $perpage, $page);
if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
