<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

define('NV_IS_FILE_ADMIN', true);

// Quy định những file nào được phép xử lý
$allow_func = [
    'main',
    'department',
    'employed_department',
    'category_laudatory',
    'proposed_reward',
    'president',
    'position',
    'detail_cat',
];

// Tất cả quản trị của site
global $array_user_id_users;
$_sql = 'SELECT tb1.userid, tb1.first_name, tb1.last_name, tb1.username, tb1.email FROM ' . NV_USERS_GLOBALTABLE . ' tb1 INNER JOIN ' . $db_config['prefix'] . '_authors tb2 ON tb1.userid = tb2.admin_id WHERE tb1.userid IN (SELECT `admin_id` FROM ' . NV_AUTHORS_GLOBALTABLE . ' ORDER BY lev ASC) AND tb1.active = 1 AND tb2.is_suspend = 0';
$array_user_id_users = $nv_Cache->db($_sql, 'userid', 'users');

// Lấy ra những admin trong hệ thống
$db->sqlreset()
    ->select('admin_id')
    ->from(NV_AUTHORS_GLOBALTABLE);
$sth = $db->prepare($db->sql());
$sth->execute();
$arr_authour = [];
while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    $arr_authour[$row['admin_id']] = $row['admin_id'];
}

$id_category = $nv_Request->get_int('id_category', 'get', 0);
$id_depart = $nv_Request->get_int('id_department', 'get', 0);

$sql_president = 'SELECT s.id AS id_pose, s.id_category, s.id_employed, s.reason, s.status, s.time_proposed, s.addtime, s.admin_id, s.time_approved, s.reason_approved, s.bonus, s.image, ' .
    'sr.id_employed AS sr_id_employed, sr.id_department, sx.id AS sx_id ' .
    'FROM ' . NV_PREFIXLANG . '_' . $module_data . '_proposed_reward AS s ' .
    'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_employed_department AS sr ' .
    'ON s.id_employed = sr.id_employed ' .
    'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_department AS sx ' .
    'ON sr.id_department = sx.id ' .
    'WHERE s.id_category = ' . $id_category . ' ' .
    'AND sr.id_department = ' . $id_depart;
