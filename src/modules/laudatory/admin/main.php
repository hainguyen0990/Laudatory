<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('main_admin');

if (!defined('NV_IS_SPADMIN')) {
    nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 404);
}

$db->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_category_laudatory');
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
    ->from(NV_PREFIXLANG . '_' . $module_data . '_proposed_reward')
    ->where('status=0');
$arr_proposed = [];
$arr_admin_id = [];
$id_employed = [];
$id_department = [];
$result = $db->query($db->sql());
while ($row = $result->fetch()) {
    $row['time_proposed'] = nv_date('d/m/Y', $row['time_proposed']);
    $arr_admin_id[$row['admin_id']] = $row['admin_id'];
    $id_employed[$row['id_employed']] = $row['id_employed'];
    $arr_proposed[] = $row;
}

if (!empty($id_employed)) {
    $db->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_employed_department')
        ->where('id_employed IN (' . implode(',', $id_employed) . ')');
    $result = $db->query($db->sql());
    $employed_id = [];
    while ($row = $result->fetch()) {
        $employed_id[$row['id_employed']] = $row['id_employed'];
        $id_department[$row['id_employed']] = $row['id_department'];
    }
}

if (!empty($id_department)) {
    $db->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_department')
        ->where('id IN (' . implode(',', $id_department) . ')');
    $result = $db->query($db->sql());
    $array_department = [];
    while ($row = $result->fetch()) {
        $array_department[$row['id']] = $row['name_department'];
    }
}

if (!empty($arr_admin_id)) {
    $db->sqlreset()
        ->select('userid, username, first_name, last_name')
        ->from(NV_USERS_GLOBALTABLE)
        ->where('userid IN (' . implode(',', $arr_admin_id) . ')');
    $result = $db->query($db->sql());
    $array_proposer = [];
    while ($row = $result->fetch()) {
        $array_proposer[$row['userid']] = [
            'userid' => $row['userid'],
            'name' => nv_show_name_user($row['first_name'], $row['last_name']),
        ];
    }
}

$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_proposed_reward')
    ->where('status=0');
$result = $db->query($db->sql());
$total_employees = $result->fetchColumn();

$grouped_data = [];
foreach ($arr_proposed as $row) {
    $row['description'] = isset($arr_category[$row['id_category']]) ? $arr_category[$row['id_category']]['description'] : '';
    $row['name_header'] = isset($array_proposer[$row['admin_id']]) ? $array_proposer[$row['admin_id']]['name'] : '';
    $row['id_department'] = isset($id_department[$row['id_employed']]) ? $id_department[$row['id_employed']] : 0;
    $row['name_department'] = isset($array_department[$row['id_department']]) ? $array_department[$row['id_department']] : '';
    $grouped_data[$row['id_category']][$row['id_department']][] = $row;
}

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

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('TOTAL', sprintf($nv_Lang->getModule('total_main'), $total_employees));
$xtpl->assign('TOTAL_APPROVED', $total_approved);
$xtpl->assign('TOTAL_REJECTED', $total_rejected);
$xtpl->assign('TOTAL_PROPOSED_CURRENT_MONTH', $total_proposed_current_month);
$xtpl->assign('TOTAL_PROPOSED_CURRENT_YEAR', $total_proposed_current_year);
$xtpl->assign('CURRENT_MONTH', nv_date('m'));
$xtpl->assign('CURRENT_YEAR', nv_date('Y'));
$xtpl->assign('TOTAL_TOTAL', $total);
$xtpl->assign('LINK_CHART', '/themes/default');

foreach ($grouped_data as $category_id => $departments) {
    $xtpl->assign('CATEGORY_NAME', $arr_category[$category_id]['description']);
    foreach ($departments as $department_id => $employees) {
        $xtpl->assign('DEPARTMENT_NAME', isset($array_department[$department_id]) ? $array_department[$department_id] : 'N/A');
        $xtpl->assign('TOTAL_REWARDS', count($employees));
        $employee = $employees[0];
        if ($employee['status'] == 0) {
            $employee['url_appove'] = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=president&amp;id_category=' . $employee['id_category'] . '&amp;id_department=' . $employee['id_department'] . '&amp;id_employed=' . $employee['id_employed'];
        }
        $xtpl->assign('ROW', $employee);
        $xtpl->parse('main.category.department');
    }
    $xtpl->parse('main.category');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
