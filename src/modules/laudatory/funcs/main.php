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

$page_title = $nv_Lang->getModule('main_admin');
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;

$array_mod_title[] = [
    'title' => $page_title,
    'link' => $base_url,
];

$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_proposed_reward');
$total = $db->query($db->sql())->fetchColumn();

// Tổng số đề xuất đã duyệt
$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_proposed_reward')
    ->where('status = 1');
$total_approved = $db->query($db->sql())->fetchColumn();

// Tổng số đề xuất từ chối
$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_proposed_reward')
    ->where('status = 2');
$total_rejected = $db->query($db->sql())->fetchColumn();

// Tổng số đề xuất khen thưởng trong tháng hiện tại
$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_proposed_reward')
    ->where('MONTH(FROM_UNIXTIME(time_proposed)) = ' . nv_date('m') . ' AND YEAR(FROM_UNIXTIME(time_proposed)) = ' . nv_date('Y'));
$total_proposed_current_month = $db->query($db->sql())->fetchColumn();

// Tổng số đề xuất khen thưởng trong năm hiện tại
$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_proposed_reward')
    ->where('YEAR(FROM_UNIXTIME(time_proposed)) = ' . nv_date('Y'));
$total_proposed_current_year = $db->query($db->sql())->fetchColumn();

// Top 10 nhân viên được khen thưởng nhiều nhất
$db->sqlreset()
    ->select('id_employed, COUNT(*) as reward_count')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_proposed_reward')
    ->where('status = 1')
    ->group('id_employed')
    ->order('reward_count DESC')
    ->limit(10);
$result = $db->query($db->sql());
$top_employee = [];
$id_employee = [];
while ($row = $result->fetch()) {
    $top_employee[] = $row;
    $id_employee[$row['id_employed']] = $row['id_employed'];
}

// Lấy tên nhân viên
$name_employee = [];
if (!empty($id_employee)) {
    $db->sqlreset()
        ->select('userid, first_name, last_name, username')
        ->from(NV_USERS_GLOBALTABLE)
        ->where('userid IN (' . implode(',', array_map('intval', $id_employee)) . ')');
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        $name_employee[$row['userid']] = [
            'userid' => $row['userid'],
            'full_name' => nv_show_name_user($row['first_name'], $row['last_name']),
        ];
    }
}

// lấy tên phòng ban
$db->sqlreset()
    ->select('id, name_department')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_department');
$sth = $db->query($db->sql());
$array_department = [];
while ($row = $sth->fetch()) {
    $array_department[$row['id']] = $row['name_department'];
}

// lấy nhân viên thuộc phòng ban nào
$employed_department = [];
if (!empty($array_department) && is_array($array_department)) {
    $db->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_employed_department')
        ->where('id_department IN (' . implode(',', array_map('intval', array_keys($array_department))) . ')');
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        if (isset($array_department[$row['id_department']])) {
            $employed_department[$row['id_employed']] = $array_department[$row['id_department']];
        } else {
            $employed_department[$row['id_employed']] = 'N/A';
        }
    }
}

$contents = nv_laudatory_statistic($total_approved, $total_rejected, $total_proposed_current_month, $total_proposed_current_year, $top_employee, $name_employee, $employed_department, $array_department, $total);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
