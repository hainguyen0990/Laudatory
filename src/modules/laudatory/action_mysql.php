<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

// Khởi tạo mảng lưu các câu lệnh SQL DROP
$sql_drop_module = [];
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_department;';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_employed_department;';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_category_laudatory;';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_proposed_reward;';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_position;';

$sql_create_module = $sql_drop_module;

// # 1. Bảng quản lý phòng ban
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_department (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    name_department varchar(255) NOT NULL DEFAULT '' COMMENT 'Tên phòng ban',
    id_department_head varchar(255) NOT NULL DEFAULT '0' COMMENT 'id trưởng phòng',
    id_position int NOT NULL DEFAULT '0' COMMENT 'id chức vụ',
    description text NOT NULL COMMENT 'Mô tả về phòng ban',
    phone varchar(20) NOT NULL DEFAULT '' COMMENT 'Số điện thoại phòng ban',
    email varchar(100) NOT NULL DEFAULT '' COMMENT 'Email phòng ban',
    addtime int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian thêm',
    update_time int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian cập nhật',
    PRIMARY KEY (id),
    INDEX name_department (name_department),
    INDEX id_position (id_position),
    INDEX addtime (addtime),
    INDEX id_department_head (id_department_head)
) ENGINE=InnoDB COMMENT 'Quản lý phòng ban'";

// # 2. Bảng quản lý nhân viên phòng ban
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_employed_department (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    id_department int(11) NOT NULL DEFAULT '0' COMMENT 'ID phòng ban',
    id_employed int(11) NOT NULL DEFAULT '0' COMMENT 'id nhân viên',
    id_position int(11) NOT NULL DEFAULT '0' COMMENT 'id chức vụ',
    addtime int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian thêm',
    update_time int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian cập nhật',
    PRIMARY KEY (id),
    INDEX id_department (id_department),
    INDEX id_employed (id_employed),
    INDEX id_position (id_position),
    INDEX addtime (addtime)
) ENGINE=InnoDB COMMENT 'Nhân viên phòng ban'";

// # 3. Bảng quản lý danh mục khen thưởng
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_category_laudatory (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    time_awards varchar(255) NOT NULL DEFAULT '' COMMENT 'Thời gian danh mục khen thưởng',
    description text NOT NULL COMMENT 'Mô tả danh mục khen thưởng',
    addtime int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian thêm',
    update_time int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian cập nhật',
    weight int(11) NOT NULL DEFAULT '0' COMMENT 'Thứ tự',   
    PRIMARY KEY (id),
    KEY weight (weight),
    INDEX addtime (addtime)
) ENGINE=InnoDB COMMENT 'Danh mục khen thưởng'";

// # 4. Bảng quản lý đề xuất khen thưởng
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_proposed_reward (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    id_category int(11) NOT NULL DEFAULT '0' COMMENT 'ID danh mục khen thưởng',
    id_employed int(11) NOT NULL DEFAULT '0' COMMENT 'ID nhân viên',    
    reason text NOT NULL COMMENT 'Lý do khen thưởng', 
    time_proposed int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian đề xuất',
    admin_id int(11) NOT NULL DEFAULT '0' COMMENT 'ID người đề xuất',
    status tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Trạng thái',
    admin_id_approved int(11) NOT NULL DEFAULT '0' COMMENT 'ID người duyệt',
    reason_approved text DEFAULT NULL COMMENT 'Lý do duyệt',
    time_approved int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian duyệt',
    bonus  varchar(255) DEFAULT NULL COMMENT 'Thưởng',
    image varchar(255) NOT NULL DEFAULT '' COMMENT 'Hình ảnh',
    addtime int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian thêm',
    update_time int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian cập nhật',
    PRIMARY KEY (id),
    INDEX id_category (id_category),
    INDEX id_employed (id_employed),
    INDEX addtime (addtime)
) ENGINE=InnoDB COMMENT 'Đề xuất khen thưởng'";

// # 5. Bảng quản lý chức vụ
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_position (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    name_position varchar(255) NOT NULL DEFAULT '' COMMENT 'Tên chức vụ',
    description text NOT NULL COMMENT 'Mô tả về chức vụ',
    addtime int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian thêm',
    update_time int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian cập nhật',
    weight int(11) NOT NULL DEFAULT '0' COMMENT 'Thứ tự',
    PRIMARY KEY (id),
    KEY weight (weight),
    INDEX name_position (name_position),
    INDEX addtime (addtime)
) ENGINE=InnoDB COMMENT 'Quản lý chức vụ'";

$fieldExists = $db->query('SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . '_field WHERE field = \'phone\'')->fetchColumn();

if (!$fieldExists) {
    $data['weight'] = $db->query('SELECT max(weight) FROM ' . NV_USERS_GLOBALTABLE . '_field')->fetchColumn();
    $data['weight'] = intval($data['weight']) + 1;
    $sql_create_module[] = 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_field (field, weight, field_type, field_choices, sql_choices, match_type, match_regex, func_callback, min_length, max_length, limited_values, for_admin, required, show_register, user_editable, show_profile, class, language, default_value, is_system) VALUES (\'phone\',' . $data['weight'] .', \'textbox\', \'\', \'\', \'none\', \'\', \'\', 0, 255, \'\', 0, 0, 1, 1, 1, \'\', \'\', 0, 0)';
    $columnExists = $db->query('SHOW COLUMNS FROM ' . NV_USERS_GLOBALTABLE . '_info LIKE \'phone\'')->fetchColumn();
    if (!$columnExists) {
        $sql_create_module[] = 'ALTER TABLE ' . NV_USERS_GLOBALTABLE . '_info ADD phone varchar(255) NOT NULL DEFAULT \'\'';
    }
}
