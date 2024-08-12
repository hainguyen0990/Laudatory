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

$submenu['department'] = $nv_Lang->getModule('departments');
if (defined('NV_IS_SPADMIN')) {
    $submenu['supporter'] = $nv_Lang->getModule('supporter');
}
$submenu['send'] = $nv_Lang->getModule('compose_mail');
if (defined('NV_IS_SPADMIN')) {
    $submenu['config'] = $nv_Lang->getModule('config');
}
