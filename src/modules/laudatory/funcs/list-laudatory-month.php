<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_LAUDATORY')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('list_employee_laudatory');
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
$array_mod_title[] = [
    'title' => $nv_Lang->getModule('list_employee_laudatory'),
    'link' => $base_url,
];

// Lấy thông tin phòng ban và tháng từ yêu cầu (nếu có)
$id_department = $nv_Request->get_int('id_department', 'get', 0);
$id_cat = $nv_Request->get_int('id_cat', 'get', 0);
$current_month = date('m');
$current_year = date('Y');

// Lấy danh sách ID từ bảng _proposed_reward
$proposed_reward_ids = [];
$db->sqlreset()
    ->select('DISTINCT id_category')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_proposed_reward');
$sth = $db->query($db->sql());
while ($row = $sth->fetch()) {
    $proposed_reward_ids[] = $row['id_category'];
}

// Nếu id_cat không được cung cấp, lấy id_cat của tháng hiện tại
if (empty($id_cat)) {
    $db->sqlreset()
        ->select('id')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_category_laudatory')
        ->where('MONTH(FROM_UNIXTIME(time_awards)) = ' . $current_month . ' AND YEAR(FROM_UNIXTIME(time_awards)) = ' . $current_year);
    $sth = $db->query($db->sql());
    $row = $sth->fetch();
    if ($row) {
        $id_cat = $row['id'];
    }
}

// Lấy danh sách các khen thưởng hiện tại theo phòng ban và tháng
$db->sqlreset()
    ->select('id, description, time_awards')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_category_laudatory')
    ->where('YEAR(FROM_UNIXTIME(time_awards)) = ' . $current_year);
$sth = $db->query($db->sql());
$cat_array_current = [];
$cat_array_other_months = [];
while ($row = $sth->fetch()) {
    $time_awards = $row['time_awards'];
    $row['time_awards_formatted'] = date('d/m/Y', $time_awards);
    if (in_array($row['id'], $proposed_reward_ids)) {
        if ($row['id'] == $id_cat) {
            $cat_array_current[] = $row;
        } else {
            $cat_array_other_months[] = $row;
        }
    }
}

// Kiểm tra nếu danh sách các khen thưởng hiện tại rỗng
if (empty($cat_array_current)) {
    $cat_array_current = null;
}

$array_department = [];
$employed_id = [];

// Kiểm tra xem có ID phòng ban hay không
if ($id_department) {
    $db->sqlreset()
        ->select('id, name_department')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_department')
        ->where('id = ' . $id_department);
    $sth = $db->query($db->sql());
    while ($row = $sth->fetch()) {
        $array_department[$row['id']] = $row['name_department'];
    }

    if (!empty($array_department)) {
        $db->sqlreset()
            ->select('*')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_employed_department')
            ->where('id_department = ' . $id_department);
        $result = $db->query($db->sql());
        while ($row = $result->fetch()) {
            $employed_id[$row['id_employed']] = $row['id_employed'];
        }
    }
}

// Lấy khen thưởng theo phòng ban và tháng hiện tại
$sql = $db->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_proposed_reward');
$where = 'status = 1 AND id_category = ' . $id_cat;
if ($id_department && !empty($employed_id)) {
    $where .= ' AND id_employed IN (' . implode(',', array_map('intval', $employed_id)) . ')';
}
$db->where($where);
$result = $db->query($db->sql());
$approved_employee = [];
while ($row = $result->fetch()) {
    $approved_employee[] = $row;
}

$db->sqlreset()
    ->select('id,description,time_awards')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_category_laudatory');
$sth = $db->query($db->sql());
$array_category = [];
while ($row = $sth->fetch()) {
    $array_category[$row['id']] = [
        'id' => $row['id'],
        'description' => $row['description'],
        'time_awards' => nv_date('d/m/Y', $row['time_awards']),
    ];
}

$name_employee = [];
if (!empty($employed_id) && is_array($employed_id)) {
    $db->sqlreset()
        ->select('userid, first_name, last_name, username')
        ->from(NV_USERS_GLOBALTABLE)
        ->where('userid IN (' . implode(',', array_map('intval', $employed_id)) . ')');
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        $name_employee[$row['userid']] = [
            'userid' => $row['userid'],
            'full_name' => nv_show_name_user($row['first_name'], $row['last_name']),
        ];
    }
}

$employed_department = [];
$db->sqlreset()
    ->select('id, name_department')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_department');
$sth = $db->query($db->sql());
$array_department = [];
while ($row = $sth->fetch()) {
    $array_department[$row['id']] = $row['name_department'];
}

// Lấy nhân viên thuộc phòng ban nào
if (!empty($array_department) && is_array($array_department)) {
    $db->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_employed_department')
        ->where('id_department IN (' . implode(',', array_map('intval', array_keys($array_department))) . ')');
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        $employed_department[$row['id_employed']] = $array_department[$row['id_department']];
    }
}

$contents = nv_laudatory_month($approved_employee, $name_employee, $employed_department, $array_category, $cat_array_current, $cat_array_other_months, $id_department);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
