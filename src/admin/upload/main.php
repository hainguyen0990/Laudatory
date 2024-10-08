<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('upload_manager');
$contents = '';

$path = (defined('NV_IS_SPADMIN')) ? '' : NV_UPLOADS_DIR;
$path = nv_check_path_upload($nv_Request->get_string('path', 'get', $path));
$currentpath = nv_check_path_upload($nv_Request->get_string('currentpath', 'get', $path));
$type = $nv_Request->get_string('type', 'get');
$popup = $nv_Request->get_int('popup', 'get', 0);
$area = htmlspecialchars(trim($nv_Request->get_string('area', 'get')), ENT_QUOTES);
$alt = htmlspecialchars(trim($nv_Request->get_string('alt', 'get')), ENT_QUOTES);
$currentfile = $nv_Request->get_string('currentfile', 'get', '');

$selectfile = '';
if (!empty($currentfile)) {
    $selectfile = nv_string_to_filename(pathinfo($currentfile, PATHINFO_BASENAME));
    $currentfilepath = nv_check_path_upload(pathinfo($currentfile, PATHINFO_DIRNAME));
    if (!empty($currentfilepath) and !empty($selectfile)) {
        $currentpath = $currentfilepath;
    }
}
if (empty($currentpath)) {
    $currentpath = NV_UPLOADS_DIR;
}

if ($type != 'image') {
    $type = 'file';
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

if ($popup) {
    $nv_Lang->setModule('browse_file', $nv_Lang->getGlobal('browse_file'));
    $sys_max_size = $sys_max_size_local = min($global_config['nv_max_size'], nv_converttoBytes(ini_get('upload_max_filesize')), nv_converttoBytes(ini_get('post_max_size')));
    if ($global_config['nv_overflow_size'] > $sys_max_size and $global_config['upload_chunk_size'] > 0) {
        $sys_max_size_local = $global_config['nv_overflow_size'];
    }

    $xtpl->assign('ADMIN_THEME', $global_config['module_theme']);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('NV_MAX_SIZE_REMOTE', nv_convertfromBytes($sys_max_size));
    $xtpl->assign('NV_MAX_SIZE_LOCAL', nv_convertfromBytes($sys_max_size_local));
    $xtpl->assign('NV_MAX_SIZE_BYTES', $sys_max_size_local);
    $xtpl->assign('NV_MIN_WIDTH', 10);
    $xtpl->assign('NV_MIN_HEIGHT', 10);
    $xtpl->assign('CURRENTPATH', $currentpath);
    $xtpl->assign('PATH', $path);
    $xtpl->assign('TYPE', $type);
    $xtpl->assign('AREA', $area);
    $xtpl->assign('ALT', $alt);
    $xtpl->assign('FUNNUM', $nv_Request->get_int('CKEditorFuncNum', 'get', 0));
    $xtpl->assign('EDITOR_ID', $nv_Request->get_title('editor_id', 'get', ''));
    $xtpl->assign('NV_CHUNK_SIZE', $global_config['upload_chunk_size']);
    $xtpl->assign('SELFILE', $selectfile);
    $xtpl->assign('COMPRESS_IMAGE_ACTIVE', (class_exists('Tinify\Tinify') and !empty($global_config['tinify_active']) and !empty($global_config['tinify_api'])) ? 'true' : 'false');

    $sfile = ($type == 'file') ? ' selected="selected"' : '';
    $simage = ($type == 'image') ? ' selected="selected"' : '';

    $xtpl->assign('SIMAGE', $simage);
    $xtpl->assign('SFILE', $sfile);

    // Find logo config
    $upload_logo = $upload_logo_config = '';
    if (!empty($global_config['upload_logo']) and file_exists(NV_ROOTDIR . '/' . $global_config['upload_logo'])) {
        $upload_logo = NV_BASE_SITEURL . $global_config['upload_logo'];
        $logo_size = getimagesize(NV_ROOTDIR . '/' . $global_config['upload_logo']);
        $upload_logo_config = $logo_size[0] . '|' . $logo_size[1] . '|' . $global_config['autologosize1'] . '|' . $global_config['autologosize2'] . '|' . $global_config['autologosize3'];
    }

    $xtpl->assign('UPLOAD_LOGO', $upload_logo);
    $xtpl->assign('UPLOAD_LOGO_CONFIG', $upload_logo_config);

    // Check upload allow file types
    if ($type == 'image' and in_array('images', $admin_info['allow_files_type'], true)) {
        $allow_files_type = ['images'];
    } else {
        $allow_files_type = $admin_info['allow_files_type'];
    }

    $xtpl->assign('UPLOAD_ALT_REQUIRE', !empty($global_config['upload_alt_require']) ? 'true' : 'false');
    $xtpl->assign('UPLOAD_AUTO_ALT', !empty($global_config['upload_auto_alt']) ? 'true' : 'false');

    if (!empty($global_config['upload_alt_require'])) {
        $xtpl->parse('main.alt_remote');
    }

    if (!empty($global_config['upload_auto_alt'])) {
        $xtpl->parse('main.auto_alt');
    }

    if (!$global_config['nv_auto_resize']) {
        $xtpl->parse('main.no_auto_resize');
    }

    if (!empty($admin_info['editor']) and file_exists(NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . $admin_info['editor'] . '/nv.callback.js')) {
        $xtpl->assign('EDITOR', $admin_info['editor']);
        $xtpl->parse('main.custom_callback');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    $head_site = 0;
} else {
    $xtpl->assign('IFRAME_SRC', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;popup=1');
    $xtpl->parse('uploadPage');
    $contents = $xtpl->text('uploadPage');
    $head_site = 1;
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents, $head_site);
include NV_ROOTDIR . '/includes/footer.php';
