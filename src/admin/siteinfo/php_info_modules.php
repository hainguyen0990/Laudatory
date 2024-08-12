<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('extensions_php');

require_once NV_ROOTDIR . '/includes/core/phpinfo.php';

$array = phpinfo_array(8, 1);
unset($array['Apache Environment']['HTTP_COOKIE'], $array['HTTP Headers Information']['Cookie'], $array['HTTP Headers Information']['Set-Cookie']);

if (!empty($array)) {
    $template = get_tpl_dir([$global_config['module_theme'], $global_config['admin_theme']], 'admin_default', '/modules/' . $module_file . '/extensions_php.tpl');
    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $template . '/modules/' . $module_file);
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('ARRAY', $array);

    $contents = $tpl->fetch('extensions_php.tpl');
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
