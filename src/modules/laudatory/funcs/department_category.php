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

$page_title = $nv_Lang->getModule('list_department');
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
$array_mod_title[] = [
    'title' => $nv_Lang->getModule('list_department'),
    'link' => $base_url,
];

$db->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_department');
$result = $db->query($db->sql());
$array_data = [];
$id_department = [];
while ($row = $result->fetch()) {
    $array_data[] = $row;
    $id_department[$row['id']] = $row['id'];
}

if (!empty($id_department)) {
    $db->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_employed_department')
        ->where('id_department IN (' . implode(',', $id_department) . ')');
    $result = $db->query($db->sql());
    $id_employee = [];
    $department_employee = [];
    while ($row = $result->fetch()) {
        $id_employee[$row['id_employed']] = $row['id_employed'];
        $department_employee[$row['id_department']][] = $row['id_employed'];
    }
}

$num = [];
if (!empty($id_employee)) {
    foreach ($department_employee as $id_department => $employees) {
        $db->sqlreset()
            ->select('COUNT(id_employed) as tong')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_proposed_reward')
            ->where('id_employed IN (' . implode(',', $employees) . ') AND status=1');
        $result = $db->query($db->sql());
        while ($row = $result->fetch()) {
            $num[$id_department] = $row['tong'];
        }
    }
}

$contents = nv_laudatory_department_category($array_data, $num, $base_url);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
