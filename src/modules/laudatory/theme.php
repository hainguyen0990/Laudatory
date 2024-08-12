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

function nv_laudatory_department_category($array_data, $num, $base_url) {
    global $nv_Lang, $module_info, $module_name, $module_file, $global_config, $module_upload, $nv_Request;
    $xtpl = new XTemplate('department_category.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('PAGE_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
    $xtpl->assign('NV_ASSETS_DIR', NV_BASE_SITEURL . NV_ASSETS_DIR);

    if (!empty($array_data)) {
        foreach ($array_data as $row) {
            $row['num'] = isset($num[$row['id']]) ? $num[$row['id']] : 0;
            $row['total'] = sprintf($nv_Lang->getModule('number_employee_appove'), $row['num']);
            if ($row['num'] > 0) {
                $row['url_detail'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;op=list-laudatory-month&amp;id_department=' . $row['id'];
                $row['detail'] = '<a href="' . $row['url_detail'] . '" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i> ' . $nv_Lang->getModule('view_detail') . '</a>';
            } else {
                $row['detail'] = '<a href="javascript:void(0)" class="btn btn-primary disabled"><i class="fa fa-eye" aria-hidden="true"></i> ' . $nv_Lang->getModule('view_detail') . '</a>';
            }
            $xtpl->assign('ROW', $row);
            $xtpl->parse('main.loop');
        }
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}

function nv_laudatory_statistic($total_approved, $total_rejected, $total_proposed_current_month, $total_proposed_current_year, $top_employee, $name_employee, $employed_department, $array_department, $total) {
    global $nv_Lang, $module_info, $module_name, $module_file, $global_config, $module_upload, $nv_Request;
    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('PAGE_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
    $xtpl->assign('NV_ASSETS_DIR', NV_BASE_SITEURL . NV_ASSETS_DIR);
    $xtpl->assign('TOTAL_APPROVED', $total_approved);
    $xtpl->assign('TOTAL_REJECTED', $total_rejected);
    $xtpl->assign('TOTAL_PROPOSED_CURRENT_MONTH', $total_proposed_current_month);
    $xtpl->assign('TOTAL_PROPOSED_CURRENT_YEAR', $total_proposed_current_year);
    $xtpl->assign('CURRENT_MONTH', nv_date('m'));
    $xtpl->assign('CURRENT_YEAR', nv_date('Y'));
    $xtpl->assign('TOTAL', $total);
    $xtpl->assign('LINK_CHART', '/themes/' . $module_info['template'] . '/');

    if (!empty($top_employee)) {
        $i = 0;
        foreach ($top_employee as $row) {
            $row['stt'] = ++$i;
            $row['employee_name'] = isset($name_employee[$row['id_employed']]) ? $name_employee[$row['id_employed']]['full_name'] : 'N/A';
            $row['employed_department'] = isset($employed_department[$row['id_employed']]) ? $employed_department[$row['id_employed']] : '';
            $xtpl->assign('ROW', $row);
            $xtpl->parse('main.loop');
        }
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}

function nv_laudatory_month($approved_employee, $name_employee, $employed_department, $array_category, $cat_array_current, $cat_array_other_months, $id_department) {
    global $nv_Lang, $module_info, $module_name, $module_file, $global_config, $nv_Request, $module_upload;
    $xtpl = new XTemplate('list-laudatory-month.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('PAGE_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
    $xtpl->assign('NV_ASSETS_DIR', NV_BASE_SITEURL . NV_ASSETS_DIR);

    // Lấy danh sách các khen thưởng theo phòng ban và tháng hiện tại
    if (!empty($cat_array_current)) {
        foreach ($cat_array_current as $row) {
            $row['time_awards'] = nv_date('d/m/Y', strtotime($row['time_awards']));
            if (isset($row['time_awards']) && !empty($row['time_awards'])) {
                $row['url_detail1'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;op=list-laudatory-month&amp;id_cat=' . $row['id'];
                if ($id_department) {
                    $row['url_detail1'] .= '&amp;id_department=' . $id_department;
                }
                $xtpl->assign('CAT_LOOP', $row);
                $xtpl->parse('main.cat_current_loop');
            }
        }
    }

    // Các tháng khác
    if (!empty($cat_array_other_months)) {
        foreach ($cat_array_other_months as $row) {
            if (isset($row['time_awards']) && !empty($row['time_awards'])) {
                $row['time_awards'] = nv_date('d/m/Y', strtotime($row['time_awards']));
                $row['url_detail'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;op=list-laudatory-month&amp;id_cat=' . $row['id'];
                if ($id_department) {
                    $row['url_detail'] .= '&amp;id_department=' . $id_department;
                }
                $xtpl->assign('CAT_OTHER_MONTHS_LOOP', $row);
                $xtpl->parse('main.cat_other_months_loop');
            }
        }
    }

    // Hiển thị danh sách khen thưởng đã được phê duyệt
    if (!empty($approved_employee)) {
        foreach ($approved_employee as $row) {
            $month_awarded = date('m', $row['time_proposed']);
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
            $row['name_employee'] = isset($name_employee[$row['id_employed']]['full_name']) ? $name_employee[$row['id_employed']]['full_name'] : '';
            $row['employed_department'] = isset($employed_department[$row['id_employed']]) ? $employed_department[$row['id_employed']] : '';
            $row['name_cat'] = isset($array_category[$row['id_category']]['description']) && isset($array_category[$row['id_category']]['time_awards']) ? $array_category[$row['id_category']]['description'] . ' - ' . $array_category[$row['id_category']]['time_awards'] : '';
            $xtpl->assign('ROW', $row);
            $xtpl->parse('main.info.loop');
        }
        $xtpl->parse('main.info');
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}

function nv_laudatory_list_employees_laudatory_year($id_cat, $array_cat, $array_year, $current_year, $base_url) {
    global $nv_Lang, $module_info, $module_name, $module_file, $global_config, $module_upload, $nv_Request;
    $xtpl = new XTemplate('list-lau-year.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('PAGE_URL', $base_url);
    $xtpl->assign('NV_ASSETS_DIR', NV_BASE_SITEURL . NV_ASSETS_DIR);

    if (!$id_cat) {
        if (!empty($array_year)) {
            foreach ($array_year as $year => $year_title) {
                $xtpl->assign('YEAR', [
                    'key' => $year,
                    'value' => $year_title,
                    'selected' => $year == $current_year ? 'selected' : ''
                ]);
                $xtpl->parse('main.search.year');
            }
        }

        if (!empty($array_cat)) {
            foreach ($array_cat as $cat) {
                $cat['url_detail'] = $base_url . '&id_cat=' . $cat['id'];
                $xtpl->assign('CAT', $cat);
                $xtpl->parse('main.cat_loop');
            }
        }
        $xtpl->parse('main.search');
    } else {
        $xtpl->assign('ID_CAT', $id_cat);
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}
