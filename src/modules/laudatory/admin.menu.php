<?php

/**
 * Dùng để khai bảo menu trong admin của module
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

$menu_setting = [];
$menu_setting['department'] = $nv_Lang->getModule('add_department');
$menu_setting['position'] = $nv_Lang->getModule('add_position');
$submenu['department'] = [
    'title' => $nv_Lang->getModule('department_admin'),
    'submenu' => $menu_setting
];
$submenu['category_laudatory'] = $nv_Lang->getModule('category_laudatory');
