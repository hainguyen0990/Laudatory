<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

// Thêm bảng phòng ban
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_department (`id`, `name_department`, `id_department_head`, `id_position`, `description`, `phone`, `email`, `addtime`, `update_time`) VALUES
(1, 'Phòng Giám Đốc', '1', 1, '', '0987461923', 'hungnguyen@gmail.com', '" . NV_CURRENTTIME . "', 0),
(2, 'Phòng Kỹ Thuật', '1', 2, '', '0987461923', 'hungnguyen@gmail.com', '" . NV_CURRENTTIME . "', 0),
(3, 'Phòng Marketing', '1', 3, '', '0987461923', 'hungnguyen@gmail.com', '" . NV_CURRENTTIME . "', 0);");

// Thêm bảng chức vụ
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_position (`id`, `name_position`, `description`, `addtime`, `update_time`, `weight`) VALUES
(1, 'Ban giám đốc Vinades', '', '" . NV_CURRENTTIME . "', 0, 1),
(2, 'Trưởng Phòng Kỹ thuật', '', '" . NV_CURRENTTIME . "', 0, 2),
(3, 'Trưởng Phòng Marketing', '', '" . NV_CURRENTTIME . "', 0, 3);");
