<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

$allow_func = ['main', 'edit', '2step'];

if (empty($global_config['spadmin_add_admin']) and $global_config['idsite'] > 0) {
    // Fix add admin for subsite
    $global_config['spadmin_add_admin'] = 1;
}

if (defined('NV_IS_GODADMIN') or (defined('NV_IS_SPADMIN') and $global_config['spadmin_add_admin'] == 1)) {
    $allow_func[] = 'add';
    $allow_func[] = 'suspend';
    $allow_func[] = 'del';
    $allow_func[] = 'users';
    $submenu['add'] = $nv_Lang->getModule('menuadd');
    $submenu['users'] = $nv_Lang->getModule('users');
}

if (defined('NV_IS_GODADMIN')) {
    $submenu['module'] = $nv_Lang->getModule('module_admin');
    $submenu['config'] = $nv_Lang->getModule('config');
    $allow_func[] = 'module';
    $allow_func[] = 'config';
}
