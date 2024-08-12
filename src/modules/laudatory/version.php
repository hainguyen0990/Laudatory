<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$module_version = [
    'name' => 'Quản lý khen thưởng', // Tieu de module
    'modfuncs' => 'main, list-lau-year,list-laudatory-month,department_category',
    'change_alias' => 'main, list-lau-year,list-laudatory-month,department_category',
    'submenu' => 'main, list-lau-year,list-laudatory-month,department_category',  // Các fun hỗ trợ tạo menu con
    'is_sysmod' => 0,  // 1:0 => Co phai la module he thong hay khong
    'virtual' => 0, // 1:0 => Co cho phep ao hao module hay khong
    'version' => '4.6.00',
    'date' => 'Monday, February 19, 2024 21:00:00 PM GMT+07:00', // Ngay phat hanh phien ban
    'author' => 'Nguyễn Đức Hoàng Hải <haiha0095@gmail.com',
    'note' => 'Module quản lí khen thưởng ', // Ghi chu
    'uploads_dir' => [  
        $module_upload
    ]
];
