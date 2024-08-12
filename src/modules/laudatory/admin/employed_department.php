<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$data['id'] = $nv_Request->get_int('id', 'post,get', 0);

// Xóa nhiều
if ($nv_Request->get_title('delete_all', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_typed_array('idcheck', 'post', 'int', []);
    if (empty($id)) {
        $res = [
            'res' => 'error',
            'mess' => $nv_Lang->getModule('error_required_id')
        ];
        nv_jsonOutput($res);
    } else {
        $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_employed_department WHERE id IN (' . implode(',', $id) . ')';
        $db->query($sql);
        $res = [
            'res' => 'success',
            'mess' => $nv_Lang->getModule('delete_success')
        ];
        nv_jsonOutput($res);
    }
}

$db->sqlreset()
    ->select('id,name_position')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_position');
$sth = $db->query($db->sql());
$arr_position = [];
while ($row = $sth->fetch()) {
    $arr_position[$row['id']] = $row;
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

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' .$op;
$page_title = $nv_Lang->getModule('employed_manager');
$error = [];
$data = [
    'id' => 0,
    'id_employed' => '',
    'id_position' => '',
];

$data['id_department'] = $nv_Request->get_int('id_department', 'post,get', 0);
$db->sqlreset()
    ->select('id, name_department')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_department')
    ->where('id = ' . $data['id_department']);
$sth = $db->query($db->sql());
$array_department = [];
while ($row = $sth->fetch()) {
    $array_department[] = $row;
}

$db->sqlreset()
    ->select('userid, username, last_name, first_name')
    ->from(NV_USERS_GLOBALTABLE)
    ->where("userid NOT IN(SELECT id_employed FROM " . NV_PREFIXLANG . '_' . $module_data . '_employed_department' . ")");
$result = $db->query($db->sql());
while ($row = $result->fetch()) {
    $user[$row['userid']] = $row;
}

foreach ($arr_authour as $value) {
    unset($user[$value]);
}

if ($nv_Request->isset_request('submit', 'get,post')) {
    $rewrite = $nv_Request->get_title('rewrite', 'post,get', '');
    $data['id_employed'] = $nv_Request->get_typed_array('id_employed', 'post', 'int', []);
    $data['id_position'] = $nv_Request->get_int('id_position', 'post', 0);
    $id_category = $nv_Request->get_int('id_category', 'post,get', 0);

    if (empty($data['id_employed'])) {
        $error[] = $nv_Lang->getModule('error_required_name');
    }

    // Thêm và sửa dữ liệu
    if (empty($error)) {
        try {
            $exit = [];
            foreach ($data['id_employed'] as $id_employed) {
                $stmt_check = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_employed_department WHERE id_department = :id_department AND id_employed = :id_employed');
                $stmt_check->bindParam(':id_department', $data['id_department'], PDO::PARAM_INT);
                $stmt_check->bindParam(':id_employed', $id_employed, PDO::PARAM_INT);
                $stmt_check->execute();
                if ($stmt_check->fetchColumn()) {
                    $exit[] = $id_employed;
                }
            }
            if (empty($exit)) {
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_employed_department (id_department, id_position, id_employed, addtime) VALUES (:id_department, :id_position, :id_employed, ' . NV_CURRENTTIME . ')');
                foreach ($data['id_employed'] as $id_employed) {
                    $stmt->bindParam(':id_department', $data['id_department'], PDO::PARAM_INT);
                    $stmt->bindParam(':id_position', $data['id_position'], PDO::PARAM_INT);
                    $stmt->bindParam(':id_employed', $id_employed, PDO::PARAM_INT);
                    $stmt->execute();
                }
            } else {
                $error[] = $nv_Lang->getModule('error_duplicate_id_employed');
            }
            $nv_Cache->delMod($module_name);
            if (!empty($rewrite)) {
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=proposed_reward&amp;id_department=' . $data['id_department'] . '&id_category=' . $id_category);
            } else {
                nv_redirect_location($base_url . '&id_department=' . $data['id_department']);
            }
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            $error[] = $nv_Lang->getModule('error_save');
        }
    }
}

if ($nv_Request->isset_request('action', 'get') and $nv_Request->isset_request('id', 'get')) {
    $data['id'] = $nv_Request->get_int('id', 'get', 0);
    if ($data['id'] > 0) {
        if ($nv_Request->get_title('checksess', 'post,get', '') === md5($data['id'] . NV_CHECK_SESSION)) {
            $stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_employed_department WHERE id = ' . $data['id']);
            $stmt->execute();
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete Department', 'ID: ' . $data['id'], $admin_info['userid']);
            $nv_Cache->delMod($module_name);
            nv_redirect_location($base_url . '&id_department=' . $data['id_department']);
        }
    }
}

if ($data['id'] > 0) {
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_employed_department WHERE id = " . $data['id'];
    $data = $db->query($sql)->fetch();
}

$db ->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_employed_department' )
    ->where('id_department = ' . $data['id_department']);
$sql = $db->sql();
$total = $db->query($sql)->fetchColumn();

$db ->select('*')
    ->where('id_department = ' . $data['id_department'])
    ->order('id ASC');
$sql = $db->sql();
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $array_row[$row['id']] = $row;
    $array_show_department[] = $row['id_department'];
    $array_show_employed[] = $row['id_employed'];
}

if (!empty($array_show_department)) {
    $db->sqlreset()
        ->select('id, name_department')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_department')
        ->where('id IN (' . implode(',', $array_show_department) . ')');
    $sth = $db->query($db->sql());
    while ($row = $sth->fetch()) {
        $name_department[$row['id']]['name_department'] = $row['name_department'];
    }
}

if (!empty($array_show_employed)) {
    $db->sqlreset()
        ->select('userid, username, last_name, first_name')
        ->from(NV_USERS_GLOBALTABLE)
        ->where('userid IN (' . implode(',', $array_show_employed) . ')');
    $sth = $db->query($db->sql());
    while ($row = $sth->fetch()) {
        $name_employed[$row['userid']]['full_name'] = nv_show_name_user($row['first_name'], $row['last_name']);
    }
}

$xtpl = new XTemplate('employed_department.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('TOTAL', $total);
$xtpl->assign('BACK', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=department');

if (!empty($array_row)) {
    $i = 0;
    foreach ($array_row as $row) {
        $row['stt'] = ++$i;
        $row['name_department'] = isset($name_department[$row['id_department']]) ? $name_department[$row['id_department']]['name_department'] : '';
        $row['name_employed'] = isset($name_employed[$row['id_employed']]) ? $name_employed[$row['id_employed']]['full_name'] : '';
        $row['name_position'] = isset($arr_position[$row['id_position']]) ? $arr_position[$row['id_position']]['name_position'] : '';
        $row['url_employed_department'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=employed_department&amp;id_department=' . $row['id'];
        $row['url_delete'] = $base_url . '&id=' . $row['id'] . '&id_department='.$row['id_department'].'&action=delete&checksess=' . md5($row['id'] . NV_CHECK_SESSION);
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop');
    }
}

if (!empty($array_department)) {
    foreach ($array_department as $row) {
        $row['selected'] = $row['id'] == $data['id_department'] ? 'selected="selected"' : '';
        $xtpl->assign('DEPARTMENT', $row);
        $xtpl->parse('main.department');
    }
}
foreach ($arr_position as $position) {
    $position['selected'] = $position['id'] == $data['id_position'] ? 'selected="selected"' : '';
    $xtpl->assign('POSITION', $position);
    $xtpl->parse('main.select_position.loop');
}
$xtpl->parse('main.select_position');

if (!empty($user)) {
    foreach ($user as $userid => $userDetails) {
        $userDetails['selected'] = ($userid == $data['id_employed'] ?? -1) ? 'selected="selected"' : '';
        $xtpl->assign('USER', $userDetails);
        $xtpl->parse('main.user');
    }

}

if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$xtpl->assign('DATA',$data);
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
