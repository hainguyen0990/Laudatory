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

$id_depart = $nv_Request->get_int('id_department', 'get', 0);
$sql_proposed = $sql_approved = $sql_rejected = $sql_president ;
$page_title = $nv_Lang->getModule('president_approve');

if ($nv_Request->get_title('save', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $reason_approved = $nv_Request->get_title('reason_approved', 'post', '');
    $bonus = $nv_Request->get_title('bonus', 'post', '');
    $image = $nv_Request->get_title('image', 'post', '');

    if (empty($reason_approved)) {
        $res = [
            'res' => 'error',
            'mess' => $nv_Lang->getModule('please_enter_reason')
        ];
        nv_jsonOutput($res);
    }

    $status = 0;
    if ($nv_Request->isset_request('approve', 'post')) {
        $status = 1;

        // Xóa phần kiểm tra giá trị 'bonus' để không bắt buộc nhập phần thưởng
    } elseif ($nv_Request->isset_request('reject', 'post')) {
        $status = 2;
    }

    if (!$id) {
        $res = [
            'res' => 'error',
            'mess' => $nv_Lang->getModule('error_save')
        ];
        nv_jsonOutput($res);
    }

    if ($status !== 0) {
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_proposed_reward SET
            reason_approved = :reason_approved,
            bonus = :bonus, 
            image = :image,
            time_approved = :time_approved,
            status = :status
            WHERE id = :id');
        $stmt->bindParam(':reason_approved', $reason_approved, PDO::PARAM_STR);
        $stmt->bindParam(':bonus', $bonus, PDO::PARAM_STR);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->bindValue(':time_approved', NV_CURRENTTIME);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $res = [
            'res' => 'success',
            'mess' => $nv_Lang->getModule('save')
        ];
        nv_jsonOutput($res);
    }

    $res = [
        'res' => 'error',
        'mess' => $nv_Lang->getModule('invalid_action')
    ];
    nv_jsonOutput($res);
}

if ($nv_Request->get_title('save_edit', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $reason_approved = $nv_Request->get_title('reason_approved', 'post', '');
    $bonus = $nv_Request->get_title('bonus', 'post', '');
    $image = $nv_Request->get_title('image', 'post', '');
    if (empty($reason_approved)) {
        $res = [
            'res' => 'error',
            'mess' => $nv_Lang->getModule('please_enter_reason')
        ];
        nv_jsonOutput($res);
    }
    if (!$id) {
        $res = [
            'res' => 'error',
            'mess' => $nv_Lang->getModule('error_save')
        ];
        nv_jsonOutput($res);
    }

    $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_proposed_reward SET
            reason_approved = :reason_approved,
            bonus = :bonus, 
            image = :image,
            time_approved = :time_approved     
            WHERE id = :id');
    $stmt->bindParam(':reason_approved', $reason_approved, PDO::PARAM_STR);
    $stmt->bindParam(':bonus', $bonus, PDO::PARAM_STR);
    $stmt->bindParam(':image', $image, PDO::PARAM_STR);
    $stmt->bindValue(':time_approved', NV_CURRENTTIME);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $res = [
        'res' => 'success',
        'mess' => $nv_Lang->getModule('save')
    ];
    nv_jsonOutput($res);
}

// Yêu cầu được đề xuất
$sql_proposed  .= ' AND s.status = 0';
$result = $db->query($sql_proposed);
$proposed = [];
while ($row = $result->fetch()) {
    $proposed[] = $row;
    $cats_id[$row['id_category']] = $row['id_category'];
    $employed_id[$row['id_employed']] = $row['id_employed'];
    $id_proposer[$row['admin_id']] = $row['admin_id'];
}
$total_proposed = count($proposed);

// Yêu cầu đề xuất được duyệt
$sql_approved .= ' AND s.status = 1';
$result = $db->query($sql_approved);
$approved = [];
while ($row = $result->fetch()) {
    $approved[] = $row;
    $cats_id[$row['id_category']] = $row['id_category'];
    $employed_id[$row['id_employed']] = $row['id_employed'];
    $id_proposer[$row['admin_id']] = $row['admin_id'];
}
$total_approved = count($approved);

// Yêu cầu đề xuất bị từ chối
$sql_rejected .= ' AND s.status = 2';
$result = $db->query($sql_rejected);
$rejected = [];
while ($row = $result->fetch()) {
    $rejected[] = $row;
    $cats_id[$row['id_category']] = $row['id_category'];
    $employed_id[$row['id_employed']] = $row['id_employed'];
    $id_proposer[$row['admin_id']] = $row['admin_id'];
}
$total_rejected = count($rejected);

if (!empty($employed_id)) {
    $db->sqlreset()
        ->select('userid, first_name, last_name')
        ->from(NV_USERS_GLOBALTABLE)
        ->where('userid IN (' . implode(',', $employed_id) . ')');
    $result = $db->query($db->sql());
    $array_employee = [];
    while ($row = $result->fetch()) {
        $array_employee[$row['userid']] = [
            'userid' => $row['userid'],
            'name' => nv_show_name_user($row['first_name'], $row['last_name']),
        ];
    }
}

if (!empty($cats_id)) {
    $db->sqlreset()
        ->select('id, time_awards, description')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_category_laudatory')
        ->where('id IN (' . implode(',', $cats_id) . ')');
    $result = $db->query($db->sql());
    $array_cats = [];
    while ($row = $result->fetch()) {
        $array_cats[$row['id']] = [
            'id' => $row['id'],
            'name_cats' => nv_date('d/m/Y', $row['time_awards']) . ' - ' . $row['description'],
        ];
    }
}

if (!empty($id_proposer)) {
    $db->sqlreset()
        ->select('userid, username, first_name, last_name')
        ->from(NV_USERS_GLOBALTABLE)
        ->where('userid IN (' . implode(',', $id_proposer) . ')');
    $result = $db->query($db->sql());
    $array_proposer = [];
    while ($row = $result->fetch()) {
        $array_proposer[$row['userid']] = [
            'userid' => $row['userid'],
            'name' => nv_show_name_user($row['first_name'], $row['last_name']),
        ];
    }
}

if ($nv_Request->isset_request('change_status','post,get')) {
    $id = $nv_Request->get_int('id', 'post,get', 0);
    $status = $nv_Request->get_int('status', 'post,get', 0);
    $result_status = $db->query('SELECT id, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_proposed_reward WHERE id = ' . $id);
    if ($row = $result_status->fetch()) {
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_proposed_reward SET status = ' . $status . ' WHERE id = ' . $id);
        $res = [
            'res' => 'success',
            'mess' => $nv_Lang->getModule('save')
        ];
        nv_jsonOutput($res);
    } else {
        $res = [
            'res' => 'error',
            'mess' => $nv_Lang->getModule('invalid_action')
        ];
        nv_jsonOutput($res);
    }
}

$db->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_category_laudatory')
    ->where('id = ' . $id_category);
$result = $db->query($db->sql());
$arr_category = $result->fetch();

$db->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_department')
    ->where('id = ' . $id_depart);
$result = $db->query($db->sql());
$array_department = $result->fetch();

$xtpl = new XTemplate('president.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', Language::$lang_module);
$xtpl->assign('GLANG', Language::$lang_global);
$xtpl->assign('TOTAL_PROPOSED', $total_proposed);
$xtpl->assign('TOTAL_APPROVED', $total_approved);
$xtpl->assign('TOTAL_REJECTED', $total_rejected);
$xtpl->assign('NV_UPLOADS_DIR', NV_UPLOADS_DIR);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('CATEGORY', $arr_category);
$xtpl->assign('TIME_AWARD', nv_date('d/m/Y', $arr_category['time_awards']));
$xtpl->assign('BACK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=proposed_reward&id_category=' . $id_category . '&id_department=' . $id_depart);
$xtpl->assign('DEPART' , $array_department);

// Trạng thái đề xuất
$status_proposed = [
    1 => $nv_Lang->getModule('approved'),
    2 => $nv_Lang->getModule('rejected'),
];

if (defined('NV_IS_SPADMIN')) {
    $xtpl->parse('main.admin_status');
}

if (defined('NV_IS_SPADMIN')) {
    if (!empty($proposed)) {
        $i = 0;
        foreach ($proposed as $row) {
            $row['stt'] = ++$i;
            $row['category_laudatory'] = $array_cats[$row['id_category']]['name_cats'];
            $row['employed'] = $array_employee[$row['id_employed']]['name'];
            $row['name_proposer'] = $array_proposer[$row['admin_id']]['name'];
            $row['time_proposed'] = nv_date('H:i:s d/m/Y', $row['time_proposed']);
            $xtpl->assign('ROW', $row);
            $xtpl->parse('main.loop_proposed');
        }
    }
    $xtpl->parse('main.admin');
}

if (!empty($approved)) {
    $i = 0;
    foreach ($approved as $row) {
        if (defined('NV_IS_SPADMIN')) {
            foreach ($status_proposed as $key => $value) {
                $xtpl->assign('STATUS', [
                    'key' => $key,
                    'value' => $value,
                    'selected' => $key == $row['status'] ? ' selected="selected"' : '',
                ]);
                $xtpl->parse('main.loop_approved.admin.status_proposed');
            }
            $xtpl->assign('ROW', $row);
            $xtpl->parse('main.loop_approved.admin');
        }
        $row['stt'] = ++$i;
        $row['category_laudatory'] = isset($array_cats[$row['id_category']]['name_cats']) ? $array_cats[$row['id_category']]['name_cats'] : '';
        $row['employed'] = isset($array_employee[$row['id_employed']]['name']) ? $array_employee[$row['id_employed']]['name'] : '';
        $row['name_proposer'] = isset($array_proposer[$row['admin_id']]['name']) ? $array_proposer[$row['admin_id']]['name'] : '';
        $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . basename($row['image']);
        $row['time_approved'] = nv_date('H:i:s d/m/Y', $row['time_approved']);
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop_approved');
    }
}

if (!empty($rejected)) {
    $i = 0;
    foreach ($rejected as $row) {
        if (defined('NV_IS_SPADMIN')) {
            $xtpl->parse('main.admin_status_rejected');
            foreach ($status_proposed as $key => $value) {
                $xtpl->assign('STATUS', [
                    'key' => $key,
                    'value' => $value,
                    'selected' => $key == $row['status'] ? ' selected="selected"' : '',
                ]);
                $xtpl->parse('main.loop_rejected.admin.status_proposed');
            }
            $xtpl->assign('ROW', $row);
            $xtpl->parse('main.loop_rejected.admin');
        }
        $row['stt'] = ++$i;
        $row['category_laudatory'] = $array_cats[$row['id_category']]['name_cats'];
        $row['employed'] = $array_employee[$row['id_employed']]['name'];
        $row['name_proposer'] = $array_proposer[$row['admin_id']]['name'];
        $row['time_approved'] = nv_date('H:i:s d/m/Y', $row['time_approved']);
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop_rejected');
    }
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
