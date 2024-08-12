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

if ($nv_Request->get_title('changeweight' ,'post' , '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_int('order_weight_id' , 'post,get' , 0);
    $new_weight = $nv_Request->get_int('order_weight_new' , 'post' , 0);

    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_category_laudatory WHERE id!=' . $id . ' ORDER BY weight ASC';
    $array = $db->query($sql)->fetch();
    if (empty($array) || empty($new_weight)) {
        $res = [
            'res' => 'error',
            'mess' => $nv_Lang->getModule('error_required_id')
        ];
        nv_jsonOutput($res);
    }

    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_category_laudatory WHERE id!=' . $id . ' ORDER BY weight ASC';
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_weight) {
            ++$weight;
        }
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_category_laudatory SET weight=' . $weight . ' WHERE id=' . $row['id'];
        $db->query($sql);
    }

    $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_category_laudatory SET weight=' . $new_weight . ' WHERE id=' . $id;
    $db->query($sql);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_CHANGE_WEIGHT_CAT', json_encode($array), $admin_info['userid']);
    $nv_Cache->delMod($module_name);
    $res = [
        'res' => 'success',
        'mess' => $nv_Lang->getModule('change_weight_success')
    ];
    nv_jsonOutput($res);
}

if ($nv_Request->get_title('delete_all' , 'post' , '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_typed_array('idcheck' , 'post' , 'int' ,[]);
    if (empty($id)) {
        $res = [
            'res' => 'error',
            'mess' => $nv_Lang->getModule('error_required_id')
        ];
        nv_jsonOutput($res);
    }

    // kiếm tra xem danh mục có tồn tại trong bảng proposed_reward không
    $id_list = implode(',' , $id);
    $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_proposed_reward WHERE id_category IN (' . $id_list . ')';
    $result = $db->query($sql)->fetchColumn();

    if ($result == 0) {
        $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_category_laudatory WHERE id IN (' . $id_list . ')';
        $db->query($sql);
        // xóa rồi cập nhật lại weith
        $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_category_laudatory ORDER BY weight ASC';
        $result = $db->query($sql);
        $weight = 0;
        while ($row = $result->fetch()) {
            ++$weight;
            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_category_laudatory SET weight=' . $weight . ' WHERE id=' . $row['id'];
            $db->query($sql);
        }
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete', 'ID: ' . $id_list, $admin_info['admin_id']);
        $nv_Cache->delMod($module_name);
        $res = [
            'res' => 'success',
            'mess' => $nv_Lang->getModule('delete_success')
        ];
        nv_jsonOutput($res);
    } else {
        $res = [
            'res' => 'error',
            'mess' => $nv_Lang->getModule('error_delete_category')
        ];
        nv_jsonOutput($res);
    }
}

if ($nv_Request->get_title('changeweight', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_absint('id', 'post', 0);
    $new_weight = $nv_Request->get_absint('new_weight', 'post', 0);

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_category_laudatory WHERE id=" . $id;
    $array = $db->query($sql)->fetch();
    if (empty($array) || empty($new_weight)) {
        nv_htmlOutput('NO_' . $id);
    }

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_category_laudatory WHERE id!=" . $id . " ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_weight) {
            ++$weight;
        }
        $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_category_laudatory SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query($sql);
    }

    $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_category_laudatory SET weight=" . $new_weight . " WHERE id=" . $id;
    $db->query($sql);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_CHANGE_WEIGHT_CAT', json_encode($array), $admin_info['admin_id']);
    $nv_Cache->delMod($module_name);
    nv_htmlOutput('OK_' . $id);
}

// Xóa
if ($nv_Request->get_title('delete', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_absint('id', 'post', 0);

    // Kiểm tra tồn tại
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_category_laudatory WHERE id=" . $id;
    $array = $db->query($sql)->fetch();
    if (empty($array)) {
        nv_htmlOutput('NO_' . $id);
    }

    $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_proposed_reward WHERE id_category=' . $id;
    $result = $db->query($sql)->fetchColumn();
    if ($result == 0 ) {
        $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_category_laudatory WHERE id=" . $id;
        $db->query($sql);

        // xóa rồi cập nhật lại weith
        $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_category_laudatory ORDER BY weight ASC';
        $result = $db->query($sql);
        $weight = 0;
        while ($row = $result->fetch()) {
            ++$weight;
            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_category_laudatory SET weight=' . $weight . ' WHERE id=' . $row['id'];
            $db->query($sql);
        }
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete', 'ID: ' . $id, $admin_info['admin_id']);
        $nv_Cache->delMod($module_name);
        nv_htmlOutput('OK_' . $id);
    } else {
        nv_htmlOutput('NO_' . $id);
    }
}

$data = $error = [];
$is_submit_form = $is_edit = false;
$id = $nv_Request->get_int('id', 'get', 0);

if (!empty($id)) {
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_category_laudatory WHERE id = " . $id;
    $result = $db->query($sql);
    $data = $result->fetch();
    $data['time_awards'] = nv_date('m/d/Y', $data['time_awards']);
    if (empty($data)) {
        nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'));
    }
    $is_edit = true;
    $caption = $nv_Lang->getModule('category_laudatory_edit');
    $page_title = $nv_Lang->getModule('category_laudatory_edit');
    $base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=category_laudatory&id=" . $id;
} else {
    $data = [
        'id' => 0,
        'time_awards' => '',
        'description' => '',
    ];
    $caption = $nv_Lang->getModule('category_laudatory_add');
    $page_title = $nv_Lang->getModule('category_laudatory_add');
    $base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=category_laudatory";
}

if ($nv_Request->isset_request('submit', 'post')) {
    $is_submit_form = true;
    $rewrite = $nv_Request->get_title('rewrite', 'post,get', '');
    $data['time_awards'] = $nv_Request->get_title('time_awards', 'post', '');
    $time_awards = strtotime($data['time_awards']);
    $data['description'] = $nv_Request->get_textarea('description', '', NV_ALLOWED_HTML_TAGS);
    $time_current = mktime(0, 0, 0, nv_date('m'), 1, date('Y'));

    if (strtotime($data['time_awards']) <  $time_current) {
        $error[] = $nv_Lang->getModule('error_required_time_awards_future');
    }

    if (empty($data['time_awards'])) {
        $error[] = $nv_Lang->getModule('error_required_time_awards');
    }

    if (empty($data['description'])) {
        $error[] = $nv_Lang->getModule('error_required_description');
    }

    // Thêm và sửa dữ liệu
    if (empty($error)) {
        try {
            if ($id > 0) {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_category_laudatory SET 
                    time_awards = :time_awards,                   
                    description = :description,
                    update_time = :update_time
                    WHERE id = :id ');
                $stmt->bindParam(':time_awards', $time_awards, PDO::PARAM_STR);
                $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR, strlen($data['description']));
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindValue(':update_time', NV_CURRENTTIME);
                $stmt->execute();
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit ', 'ID: ' . $data['id'], $admin_info['userid']);
            } else {
                $weight = $db->query("SELECT max(weight) FROM " . NV_PREFIXLANG . "_" . $module_data . "_category_laudatory")->fetchColumn();
                $weight = intval($weight) + 1;
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_category_laudatory (time_awards, description, weight , addtime) VALUES (:time_awards, :description, :weight, :addtime)');
                $stmt->bindParam(':time_awards', $time_awards, PDO::PARAM_STR);
                $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR, strlen($data['description']));
                $stmt->bindParam(':weight', $weight, PDO::PARAM_INT);
                $stmt->bindValue(':addtime', NV_CURRENTTIME);
                $stmt->execute();
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Add ', ' ', $admin_info['userid']);
            }
            $nv_Cache->delMod($module_name);
            nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
        }
    }
}

$page = $nv_Request->get_int('page' , 'get' , '1');
$perpage = 20;

$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_category_laudatory');
$total = $db->query($db->sql())->fetchColumn();

$db->select('*')
    ->order('weight ASC')
    ->limit($perpage)
    ->offset(($page - 1) * $perpage);
$sth = $db->query($db->sql());
$array_cats = [];
while ($row = $sth->fetch()) {
    $array_cats[] = $row;
}

$db->sqlreset()
    ->select('COUNT(id_employed) as tong, id_category')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_proposed_reward')
    ->group('id_category');
$result = $db->query($db->sql());
$num = [];
while ($row = $result->fetch()) {
    $num[$row['id_category']] = $row['tong'];
}

// Đếm phòng ban
$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_department');
$department_total = $db->query($db->sql())->fetchColumn();

$xtpl = new XTemplate('category_laudatory.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', Language::$lang_module);
$xtpl->assign('GLANG', Language::$lang_global);
$xtpl->assign('CAPTION', $caption);
$xtpl->assign('BASE_URL', $base_url);
$xtpl->assign('DATA', $data);

foreach ($array_cats as $row) {
    $row['total_proposed'] = $num[$row['id']] ?? 0;
    if ($row['total_proposed'] > 0) {
        $row['url_total_proposed'] =  NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail_cat&amp;id_cat=' . $row['id'];
    } else {
        $row['url_total_proposed'] = '';
    }
    $row['total_department'] = $department_total;
    $row['url_department'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=department&request=1';
    $row['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $row['id'];
    $row['time_awards'] = nv_date('d/m/Y', $row['time_awards']);
    if ($row['total_department'] > 0) {
        $row['url_propose'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail_cat&amp;id_cat=' . $row['id'];
    } else {
        $row['url_propose'] = '';
    }
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.loop');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

if (!$is_edit) {
    $xtpl->parse('main.add_btn');
}

if ($is_submit_form or $is_edit) {
    $xtpl->parse('main.scroll');
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
