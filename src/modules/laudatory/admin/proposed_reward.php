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

$id = $nv_Request->get_int('id', 'post,get', 0);
$id_employed = $nv_Request->get_int('id_employed', 'post,get', 0);
$page_title = $nv_Lang->getModule('proposed_reward');
$id_depart = $nv_Request->get_int('id_department', 'post,get', 0);
$request = $nv_Request->get_int('request', 'post,get', 0);
$id_category = $nv_Request->get_int('id_category', 'post,get', 0);
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=proposed_reward&id_category=' . $id_category . '&id_department=' . $id_depart  ;

if ($nv_Request->get_title('delete_all', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_typed_array('idcheck', 'post', 'int', []);
    if (empty($id)) {
        $res = [
            'res' => 'error',
            'mess' => $nv_Lang->getModule('error_required_id')
        ];
        nv_jsonOutput($res);
    } else {
        $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_proposed_reward WHERE id IN (' . implode(',', $id) . ')';
        $db->query($sql);
        $res = [
            'res' => 'success',
            'mess' => $nv_Lang->getModule('delete_success')
        ];
        nv_jsonOutput($res);
    }
}

if ($id) {
    $query = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_proposed_reward WHERE id=' . $id;
    $result = $db->query($query);
    $data = $result->fetch();
    $is_edit = true;
    $caption = $nv_Lang->getModule('proposed_reward_edit');
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=proposed_reward&id=' . $id;
} else {
    $data = [
        'id' => 0,
        'id_category' => -1,
        'id_employed' => -1,
        'reason' => '',
    ];
    $is_edit = false;
    $caption = $nv_Lang->getModule('proposed_reward_add');
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=proposed_reward';
}

$db->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_category_laudatory')
    ->where('id = ' . $id_category);
$result = $db->query($db->sql());
$arr_category = [];
while ($row = $result->fetch()) {
    $arr_category[$row['id']] = [
        'id' => $row['id'],
        'time_awards' => $row['time_awards'],
        'description' => $row['description'],
    ];
}

$db->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_employed_department')
    ->where('id_department = ' . $id_depart);
$result = $db->query($db->sql());
$id_user = [];
$id_departments = [];
while ($row = $result->fetch()) {
    $id_user[$row['id_employed']] = [
        'id_user' => $row['id_employed'],
        'id_department' => $row['id_department'],
    ];
    $id_departments[$row['id_department']] = $row['id_department'];
}

if (!empty($id_departments)) {
    $db->sqlreset()
        ->select('id, name_department')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_department')
        ->where('id IN (' . implode(',', $id_departments) . ')');
    $result = $db->query($db->sql());
    $departments = [];
    while ($row = $result->fetch()) {
        $departments[$row['id']] = $row['name_department'];
    }
}

if (!empty($id_user)) {
    $db->sqlreset()
        ->select('userid, username, last_name, first_name')
        ->from(NV_USERS_GLOBALTABLE)
        ->where('userid IN (' . implode(',', array_keys($id_user)) . ')');
    $result = $db->query($db->sql());
    $list_user = [];
    while ($row = $result->fetch()) {
        $user_id = $row['userid'];
        $department_name = isset($id_user[$user_id]['id_department']) ? $departments[$id_user[$user_id]['id_department']] : '';
        $list_user[$user_id] = array_merge($row, ['name_department' => $department_name]);
    }
}

$db->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_employed_department');
$result = $db->query($db->sql());
$em = [];
while ($row = $result->fetch()) {
    $em[] = $row;
}

$data['id'] = $nv_Request->get_int('id', 'post,get', 0);
if ($nv_Request->isset_request('submit', 'post')) {
    $is_submit_form = true;
    $data['id_category'] = $nv_Request->get_int('id_category', 'post', 0);
    $data['id_employed'] = $nv_Request->get_typed_array('id_employed', 'post', 'int', []);
    $data['reason'] = $nv_Request->get_textarea('reason', '', NV_ALLOWED_HTML_TAGS);
    if (empty($data['id_employed'])) {
        $error[] = $nv_Lang->getModule('error_required_id_employed');
    }

    if (empty($data['reason'])) {
        $error[] = $nv_Lang->getModule('error_required_reason');
    }

    if (!$id) {
        $existing_ids = [];
        foreach ($data['id_employed'] as $id_employed) {
            $stmt_check = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_proposed_reward WHERE id_employed = :id_employed AND id_category = :id_category');
            $stmt_check->bindParam(':id_employed', $id_employed, PDO::PARAM_INT);
            $stmt_check->bindParam(':id_category', $data['id_category'], PDO::PARAM_INT);
            $stmt_check->execute();
            if ($stmt_check->fetchColumn() > 0) {
                $existing_ids[] = $id_employed;
                $error[] = sprintf($nv_Lang->getModule('error_duplicate_id_employed'), $id_employed);
            }
        }
    }

    if (empty($error)) {
        try {
            if ($id > 0) {
                foreach ($data['id_employed'] as $id_employed) {
                    $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_proposed_reward SET
                    id_category = :id_category,
                    id_employed = :id_employed,
                    reason = :reason,
                    time_proposed = :time_proposed
                    WHERE id = :id');
                    $stmt->bindParam(':id_category', $data['id_category'], PDO::PARAM_INT);
                    $stmt->bindParam(':id_employed', $id_employed, PDO::PARAM_INT);
                    $stmt->bindParam(':reason', $data['reason'], PDO::PARAM_STR);
                    $stmt->bindValue(':time_proposed', NV_CURRENTTIME);
                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit', 'ID: ' . $id, $admin_info['userid']);
                }
            } else {
                $existing_ids = [];
                foreach ($data['id_employed'] as $id_employed) {
                    $stmt_check = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_proposed_reward WHERE id_employed = :id_employed AND id_category = :id_category');
                    $stmt_check->bindParam(':id_employed', $id_employed, PDO::PARAM_INT);
                    $stmt_check->bindParam(':id_category', $data['id_category'], PDO::PARAM_INT);
                    $stmt_check->execute();
                    if ($stmt_check->fetchColumn() > 0) {
                        $existing_ids[] = $id_employed;
                        $error[] = sprintf($nv_Lang->getModule('error_duplicate_id_employed'), $id_employed);
                    }
                }

                if (empty($existing_ids)) {
                    foreach ($data['id_employed'] as $id_employed) {
                        $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_proposed_reward (id_category, id_employed, reason, time_proposed, admin_id) VALUES (:id_category, :id_employed, :reason, :time_proposed, :admin_id)');
                        $stmt->bindParam(':id_category', $data['id_category'], PDO::PARAM_INT);
                        $stmt->bindParam(':id_employed', $id_employed, PDO::PARAM_INT);
                        $stmt->bindParam(':reason', $data['reason'], PDO::PARAM_STR);
                        $stmt->bindValue(':time_proposed', NV_CURRENTTIME);
                        $stmt->bindParam(':admin_id', $admin_info['userid'], PDO::PARAM_INT);
                        $stmt->execute();
                        nv_insert_logs(NV_LANG_DATA, $module_name, 'Add', 'ID: ' . $id_employed, $admin_info['userid']);
                    }
                }
            }
            $nv_Cache->delMod($module_name);
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=proposed_reward&id_category=' . $id_category . '&id_department=' . $id_depart );
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            $error[] = $nv_Lang->getModule('error_save');
        }
    }
}

if ($nv_Request->isset_request('action', 'get') and $nv_Request->isset_request('id', 'get')) {
    if ($data['id'] > 0) {
        $id_category = $nv_Request->get_int('id_category', 'get', 0);
        $id_depart = $nv_Request->get_int('id_department', 'get', 0);
        if ($nv_Request->get_title('checksess', 'post,get', '') === md5($data['id'] . NV_CHECK_SESSION)) {
            $stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_proposed_reward WHERE id = ' . $data['id']);
            $stmt->execute();
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete ', 'ID: ' . $data['id'], $admin_info['userid']);
            $nv_Cache->delMod($module_name);
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=proposed_reward&id_category=' . $id_category . '&id_department=' . $id_depart );
        }
    }
}

$sql = 'SELECT s.id AS id_pose,s.id_category,s.id_employed,s.reason,s.status,s.time_proposed,s.addtime,sr.id_employed,sr.id_department, sx.id ' .
    'FROM ' . NV_PREFIXLANG . '_' . $module_data . '_proposed_reward AS s ' .
    'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_employed_department AS sr ' .
    'ON s.id_employed = sr.id_employed ' .
    'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_department AS sx ' .
    'ON sr.id_department = sx.id ' .
    'WHERE s.id_category = ' . $id_category . ' ' .
    'AND sr.id_department = ' . $id_depart . ' ' .
    'ORDER BY s.id DESC';
$result = $db->query($sql);
$array_detail = [];
while ($row = $result->fetch()) {
    $array_detail[] = $row;
}

$db->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_category_laudatory')
    ->where('id = ' . $id_category);
$result = $db->query($db->sql());
$name_category = $result->fetch();

$db->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_department')
    ->where('id = ' . $id_depart);
$result = $db->query($db->sql());
$array_department = $result->fetch();

$xtpl = new XTemplate('proposed_reward.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', Language::$lang_module);
$xtpl->assign('GLANG', Language::$lang_global);
$xtpl->assign('CATEGORY', $name_category);
$xtpl->assign('TIME_AWARD', nv_date('d/m/Y', $name_category['time_awards']));
$xtpl->assign('NAME_DEPART' , $array_department);
$xtpl->assign('URL_EMPLOYEE', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=employed_department&id_department=' . $id_depart . '&rewrite=proposed&id_category=' . $id_category);

if (defined('NV_IS_SPADMIN')) {
    $xtpl->assign('URL_APPOVE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=president&amp;id_category=' . $id_category . '&amp;id_department=' . $id_depart );
}

$url_back = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail_cat&id_cat=' . $id_category;
$xtpl->assign('URL_BACK', $url_back);

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_proposed_reward WHERE id_category = " . $id_category . " ORDER BY id DESC";
$result = $db->query($sql);
$array_cats = [];
while ($row = $result->fetch()) {
    $array_cats[] = $row;
    $cats_id[$row['id_category']] = $row['id_category'];
    $employed_id[$row['id_employed']] = $row['id_employed'];
}

if (!empty($cats_id)) {
    $db->sqlreset()->select('*')->from(NV_PREFIXLANG . '_' . $module_data . '_category_laudatory')->where('id IN (' . implode(',', $cats_id) . ')');
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        $name_cats[$row['id']] = $row;
    }
}

if (!empty($employed_id)) {
    $db->sqlreset()->select('userid, username, last_name, first_name')->from(NV_USERS_GLOBALTABLE)->where('userid IN (' . implode(',', $employed_id) . ')');
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        $name_employed[$row['userid']] = nv_show_name_user($row['first_name'], $row['last_name']);
    }
}

$i = 0;
foreach ($array_detail as $row) {
    $row['stt'] = ++$i;
    $row['id_category_name'] = isset($name_cats[$row['id_category']]) ? $name_cats[$row['id_category']]['description'] : '';
    $row['departments'] = isset($list_user[$row['id_employed']]['name_department']) ? $list_user[$row['id_employed']]['name_department'] : '';
    $row['id_employed_name'] = isset($name_employed[$row['id_employed']]) ? $name_employed[$row['id_employed']] : '';
    $row['time_proposed'] = nv_date(' d/m/Y', $row['time_proposed']);
    if ($row['status'] == 1) {
        $row['success'] = '<span class="label label-success">' . $nv_Lang->getModule('approved') . '</span>';
    } elseif ($row['status'] == 2) {
        $row['success'] = '<span class="label label-danger">' . $nv_Lang->getModule('not_approved') . '</span>';
    }
    if ($row['status'] == 0) {
        $row['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=proposed_reward&id_category=' . $id_category . '&id_department=' . $id_depart . '&id_employed=' . $row['id_employed'] . '&id=' . $row['id_pose'];
        $row['url_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=proposed_reward&action=delete&id_category=' . $id_category . '&id_department=' . $id_depart . '&id_employed=' . $row['id_employed'] . '&id=' . $row['id_pose'] .'&checksess=' . md5($row['id_pose'] . NV_CHECK_SESSION);
        if ($request) {
            $row['url_delete'] .= '&request=1';
        }
        if ($request) {
            $row['url_edit'] = $base_url . '&request=1';
        }
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop.enable');
    }
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.loop');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

if (!empty($arr_category)) {
    foreach ($arr_category as $value) {
        $value['selected'] = ($value['id'] == ($data['id_category'] ?? -1)) ? 'selected="selected"' : '';
        $value['time_awards'] = $value['time_awards'] ? date('d/m/Y', $value['time_awards']) : '';
        $xtpl->assign('CATEGORY', $value);
        $xtpl->parse('main.category');
    }
}

if (!empty($list_user)) {
    $id_employed_array = is_array($data['id_employed']) ? $data['id_employed'] : [$data['id_employed']];
    foreach ($list_user as $value) {
        $value['selected'] = (in_array($value['userid'], $id_employed_array)) ? 'selected="selected"' : '';
        $xtpl->assign('EMPLOYED', $value);
        $xtpl->parse('main.employed');
    }
}

$xtpl->assign('CAPTION', $caption);
$xtpl->assign('DATA', $data);
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
