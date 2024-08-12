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

$id_cat = $nv_Request->get_int('id_category', 'post,get', 0);
$id_depart = $nv_Request->get_int('id_depart', 'post,get', 0);
$page_title = $nv_Lang->getModule('detail_cat');
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail_cat';
$request = $nv_Request->get_int('request', 'post,get', 0);
$error = [];
$data = [];

$db->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_department');
$result = $db->query($db->sql());
$department = [];
while ($row = $result->fetch()) {
    $department[$row['id']] = $row;
}

$id_cat = $nv_Request->get_int('id_cat', 'post,get', 0);
$db->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_category_laudatory')
    ->where('id = ' . $id_cat);
$result = $db->query($db->sql());
$arr_category = [];
while ($row = $result->fetch()) {
    $arr_category[$row['id']] = [
        'id' => $row['id'],
        'time_awards' => $row['time_awards'],
        'description' => $row['description'],
    ];
}

$xtpl = new XTemplate('detail_cat.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('DATA', $data);

$url['back'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=category_laudatory";
$xtpl->assign('URL', $url);

if (!empty($department)) {
    foreach ($department as $row) {
        $sql = 'SELECT COUNT(*) as total_employees ' .
            'FROM ' . NV_PREFIXLANG . '_' . $module_data . '_proposed_reward AS s ' .
            'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_employed_department AS sr ' .
            'ON s.id_employed = sr.id_employed ' .
            'WHERE s.id_category = ' . $id_cat . ' ' .
            'AND sr.id_department = ' . $row['id'];
        $result = $db->query($sql);
        $total_employees = $result->fetchColumn();
        $row['proposed'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=proposed_reward&amp;id_category=' . $id_cat . '&amp;id_department=' . $row['id'];
        $row['total_employees'] = $total_employees;
        $row['detail_department'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=employed_department&amp;id_department=' . $row['id'];
        $xtpl->assign('DEPARTMENT', $row);
        $xtpl->parse('main.loop');
    }
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
