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

if ($nv_Request->get_title('changeweight' ,'post' , '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_int('order_weight_id' , 'post,get' , 0);
    $new_weight = $nv_Request->get_int('order_weight_new' , 'post' , 0);

    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_position WHERE id!=' . $id . ' ORDER BY weight ASC';
    $array = $db->query($sql)->fetch();
    if (empty($array) || empty($new_weight)) {
        $res = [
            'res' => 'error',
            'mess' => $nv_Lang->getModule('error_required_id')
        ];
        nv_jsonOutput($res);
    }

    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_position WHERE id!=' . $id . ' ORDER BY weight ASC';
    $result = $db->query($sql);

    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_weight) {
            ++$weight;
        }
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_position SET weight=' . $weight . ' WHERE id=' . $row['id'];
        $db->query($sql);
    }

    $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_position SET weight=' . $new_weight . ' WHERE id=' . $id;
    $db->query($sql);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_CHANGE_WEIGHT_CAT', json_encode($array), $admin_info['userid']);
    $nv_Cache->delMod($module_name);
    $res = [
        'res' => 'success',
        'mess' => $nv_Lang->getModule('change_weight_success')
    ];
    nv_jsonOutput($res);
}

if ($nv_Request->get_title('delete_all1', 'post,get' , '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_typed_array('idcheck', 'post', 'int', []);
    $sql_check_all = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_employed_department WHERE id_position IN (' . implode(',', $id) . ')';
    $num = $db->query($sql_check_all)->fetchColumn();
    if ($num > 0) {
        $res = [
            'res' => 'error',
            'mess' => $nv_Lang->getModule('error_position_exit')
        ];
        nv_jsonOutput($res);
    }

    $sql_check_all2 = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE id_position IN (' . implode(',', $id) . ')';
    $num2 = $db->query($sql_check_all2)->fetchColumn();
    if ($num2 > 0) {
        $res = [
            'res' => 'error',
            'mess' => $nv_Lang->getModule('error_position_exit')
        ];
        nv_jsonOutput($res);
    }

    if (!empty($id)) {
        $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_position WHERE id IN (' . implode(',', $id) . ')';
        $db->query($sql);

        // update lai weight
        $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_position ORDER BY id DESC';
        $result = $db->query($sql);
        $weight = 0;
        while (list($id) = $result->fetch(3)) {
            ++$weight;
            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_position SET weight=' . $weight . ' WHERE id=' . $id;
            $db->query($sql);
        }
        $nv_Cache->delMod($module_name);
        $res = [
            'res' => 'success',
            'mess' => $nv_Lang->getModule('delete_success')
        ];
        nv_jsonOutput($res);
    } else {
        $res = [
            'res' => 'error',
            'mess' => $nv_Lang->getModule('error_required_id')
        ];
        nv_jsonOutput($res);
    }
}

$page_title = $nv_Lang->getModule('position');
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$data = [
    'id' => 0,
    'name_position' => '',
    'description' => '',
];

$data['id'] = $nv_Request->get_int('id', 'post,get', 0);
if ($nv_Request->isset_request('submit', 'post')) {
    $data['name_position'] = $nv_Request->get_title('name_position', 'post', '');
    $data['description'] = $nv_Request->get_textarea('description', '', NV_ALLOWED_HTML_TAGS);

    if (empty($data['name_position'])) {
        $error[] = $nv_Lang->getModule('error_required_name_position');
    }

    // Thêm và sửa dữ liệu
    if (empty($error)) {
        try {
            if ($data['id'] > 0) {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_position SET 
                        name_position = :name_position,
                        description = :description,
                        update_time = :update_time
                        WHERE id = :id ');
                $stmt->bindValue(':update_time', NV_CURRENTTIME);
                $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
                $stmt->bindParam(':name_position', $data['name_position'], PDO::PARAM_STR);
                $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR, strlen($data['description']));
                $stmt->execute();
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit POSITION', 'ID: ' . $data['id'], $admin_info['userid']);
            } else {
                $sql = 'SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_position';
                $data['weight'] = $db->query($sql)->fetchColumn() + 1;
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_position (name_position, description,weight, addtime) VALUES (:name_position, :description,:weight, :addtime)');
                $stmt->bindValue(':addtime', NV_CURRENTTIME);
                $stmt->bindParam(':name_position', $data['name_position'], PDO::PARAM_STR);
                $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR, strlen($data['description']));
                $stmt->bindParam(':weight', $data['weight'], PDO::PARAM_INT);
                $stmt->execute();
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Add POSITION', ' ', $admin_info['userid']);
            }
            $nv_Cache->delMod($module_name);
            nv_redirect_location($base_url);
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
        }
    }
}

if ($nv_Request->isset_request('action', 'get') and $nv_Request->isset_request('id', 'get')) {
    if ($data['id'] > 0) {
        if ($nv_Request->get_title('checksess', 'post,get', '') === md5($data['id'] . NV_CHECK_SESSION)) {
            $can_delete = true;
            $sql_checked = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_employed_department WHERE id_position=' . $data['id'];
            $result_check = $db->query($sql_checked)->fetchColumn();
            if ($result_check > 0) {
                $can_delete = false;
                $error[] = $nv_Lang->getModule('error_position_exit');
            }
            $sql_checked2 = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE id_position=' . $data['id'];
            $result_check2 = $db->query($sql_checked2)->fetchColumn();
            if ($result_check2 > 0) {
                $can_delete = false;
                $error[] = $nv_Lang->getModule('error_position_exit');
            }
            if ($can_delete) {
                $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_position  WHERE id = ' . $data['id']);
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete POSITION', 'ID: ' . $data['id'], $admin_info['userid']);
                $nv_Cache->delMod($module_name);
                nv_redirect_location($base_url);
            }
        }
    }
}

if ($data['id'] > 0) {
    $stmt = $db->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_position')
        ->where('id=' . $data['id']);
    $result = $db->query($db->sql());
    $data = $result->fetch();
    if (empty($data)) {
        nv_redirect_location($base_url);
    }
}

$page = $nv_Request->get_int('page', 'get', 1);
$perpage = 20;
$db ->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_position' );
$sql = $db->sql();
$total = $db->query($sql)->fetchColumn();

$db->select('*')
    ->order('weight ASC')
    ->limit($perpage)
    ->offset(($page - 1) * $perpage);
$result = $db->query($db->sql());
$arr_position = [];
while ($row = $result->fetch()) {
    $arr_position[$row['id']] = $row;
}

$xtpl = new XTemplate('position.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('DATA', $data);
$xtpl->assign('TOTAL', $total);
$xtpl->assign('URL_BACK', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=department');

if (!empty($arr_position)) {
    $i = 0;
    foreach ($arr_position as $row) {
        $row['stt'] = ++$i;
        $row['url_edit'] = $base_url . '&id=' . $row['id'];
        $row['url_delete'] = $base_url . '&id=' . $row['id'] . '&action=delete&checksess=' . md5($row['id'] . NV_CHECK_SESSION);
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop');
    }
}

$generate_page = nv_generate_page($base_url, $total, $perpage, $page);
if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
