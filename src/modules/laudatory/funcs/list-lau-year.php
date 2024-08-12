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

$page_title = $nv_Lang->getModule('tong_ket_khen_thuong');
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=list-lau-year';
$array_mod_title[] = [
    'title' => $nv_Lang->getModule('tong_ket_khen_thuong'),
    'link' => $base_url,
];
$id_cat = $nv_Request->get_int('id_cat', 'get', 0);

// lấy ra danh sách năm
for ($i = nv_date('Y', NV_CURRENTTIME) + 5; $i >= 2010; $i--) {
    $array_year[$i] = $nv_Lang->getModule('year') . ' ' . $i;
}

// lấy ra năm hiện tại
$current_year = $nv_Request->get_int('year', 'get', nv_date('Y'));
$proposed_reward_ids = [];
$db->sqlreset()
    ->select('DISTINCT id_category')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_proposed_reward')
    ->where('id_employed > 0'); // chỉ lấy những id_category có id_employee
$sth = $db->query($db->sql());
while ($row = $sth->fetch()) {
    $proposed_reward_ids[] = $row['id_category'];
}

$where = [];
$array_cat = [];
if (!empty($proposed_reward_ids)) {
    $db->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_category_laudatory')
        ->where('YEAR(FROM_UNIXTIME(time_awards)) = ' . $current_year . ' AND id IN (' . implode(',', $proposed_reward_ids) . ')');
    $sth = $db->query($db->sql());
    while ($row = $sth->fetch()) {
        $array_cat[$row['id']] = $row;
    }
}

// Xử lý yêu cầu AJAX
if ($nv_Request->isset_request('ajax', 'get') && $id_cat > 0) {
    $db->sqlreset()
        ->select('image, id_employed, COUNT(*) as reward_count, reason')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_proposed_reward')
        ->where('id_category = ' . $id_cat . ' AND id_employed > 0 and status = 1')
        ->group('id_employed');
    $result = $db->query($db->sql());
    $top_employees = [];
    while ($row = $result->fetch()) {
        if (!empty($row['image'])) {
            $imagePath = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . basename($row['image']);
            $image = new NukeViet\Files\Image($imagePath, NV_MAX_WIDTH, NV_MAX_HEIGHT);
            $imageInfo = $image->create_Image_info;
            $width = $imageInfo['width'];
            $height = $imageInfo['height'];
            $size = min($width, $height);
            $size = (int) round($size);
            $image->cropFromCenter($size, $size);
            $newImageName = 'square_' . basename($row['image']);
            $image->save(NV_UPLOADS_REAL_DIR . '/' . $module_upload, $newImageName, 90);
            $image->close();
            $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $newImageName;
        } else {
            $row['image'] = NV_STATIC_URL . 'themes/default/images/users/no_avatar.png';
        }
        $top_employees[] = $row;
    }

    if (!empty($top_employees)) {
        $employed_id = array_column($top_employees, 'id_employed');

        // lấy tên nhân viên
        $name_employee = [];
        if (!empty($employed_id)) {
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
                $employed_department[$row['id_employed']] = $array_department[$row['id_department']] ?? 'N/A';
            }
        }

        nv_jsonOutput([
            'top_employees' => $top_employees,
            'name_employee' => $name_employee,
            'employed_department' => $employed_department
        ]);
    }
}

$contents = nv_laudatory_list_employees_laudatory_year($id_cat, $array_cat, $array_year, $current_year, $base_url);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
