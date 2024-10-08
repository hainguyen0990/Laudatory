<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SEOTOOLS')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('robots');

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
$cache_file = NV_ROOTDIR . '/' . NV_DATADIR . '/robots.php';
if ($checkss == $nv_Request->get_string('checkss', 'post')) {
    $robots_data = $nv_Request->get_array('filename', 'post');
    $fileother = $nv_Request->get_array('fileother', 'post');
    $optionother = $nv_Request->get_array('optionother', 'post');
    $robots_other = [];
    foreach ($fileother as $key => $value) {
        if (!empty($value)) {
            $robots_other[$value] = (int) ($optionother[$key]);
        }
    }

    $content_config = "<?php\n\n";
    $content_config .= NV_FILEHEAD . "\n\n";
    $content_config .= "if (!defined('NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n";
    $content_config .= "\$cache = '" . serialize($robots_data) . "';\n\n";
    $content_config .= "\$cache_other = '" . serialize($robots_other) . "';\n";

    file_put_contents($cache_file, $content_config, LOCK_EX);

    $redirect = false;
    if (!$global_config['check_rewrite_file'] or !$global_config['rewrite_enable']) {
        $rbcontents = [];
        $rbcontents[] = 'User-agent: *';

        foreach ($robots_data as $key => $value) {
            if ($value == 0) {
                $rbcontents[] = 'Disallow: ' . $key;
            } elseif ($value == 2) {
                $rbcontents[] = 'Allow: ' . $key;
            }
        }

        $rbcontents[] = 'Sitemap: ' . $global_config['site_url'] . '/index.php?' . NV_NAME_VARIABLE . '=SitemapIndex' . $global_config['rewrite_endurl'];

        $rbcontents = implode("\n", $rbcontents);

        if (is_writable(NV_ROOTDIR . '/robots.txt')) {
            file_put_contents(NV_ROOTDIR . '/robots.txt', $rbcontents, LOCK_EX);
            $redirect = true;
        } else {
            $xtpl = new XTemplate('robots.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
            $xtpl->assign('TITLE', $nv_Lang->getModule('robots_error_writable'));
            $xtpl->assign('CONTENT', str_replace([
                "\n",
                "\t"
            ], [
                '<br />',
                '&nbsp;&nbsp;&nbsp;&nbsp;'
            ], nv_htmlspecialchars($rbcontents)));
            $xtpl->parse('main.nowrite');
            $xtpl->parse('main');
            $contents = $xtpl->text('main');

            include NV_ROOTDIR . '/includes/header.php';
            echo nv_admin_theme($contents);
            include NV_ROOTDIR . '/includes/footer.php';
        }
    }
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

$xtpl = new XTemplate('robots.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);
$xtpl->assign('CHECKSS', $checkss);

$robots_data = [];
$robots_other = [];

if (file_exists($cache_file)) {
    include $cache_file;
    $robots_data = unserialize($cache);
    $robots_other = unserialize($cache_other);
} else {
    $robots_data['/' . NV_DATADIR . '/'] = 0;
    $robots_data['/includes/'] = 0;
    $robots_data['/install/'] = 0;
    $robots_data['/modules/'] = 0;
    $robots_data['/robots.php'] = 0;
    $robots_data['/web.config'] = 0;
}

if ($global_config['rewrite_enable']) {
    foreach ($site_mods as $key => $value) {
        if ($value['module_file'] == 'users' or $value['module_file'] == 'statistics') {
            $_url = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $key, true);
            if (!isset($robots_other[$_url])) {
                $robots_other[$_url] = 0;
            }
        }
    }
}
$files = scandir(NV_ROOTDIR, true);
sort($files);
$contents = [];
$contents[] = 'User-agent: *';
$number = 0;
foreach ($files as $file) {
    if (!preg_match('/^\.(.*)$/', $file)) {
        if (is_dir(NV_ROOTDIR . '/' . $file)) {
            $file = '/' . $file . '/';
        } else {
            $file = '/' . $file;
        }

        $data = [
            'number' => ++$number,
            'filename' => $file
        ];

        $type = $robots_data[$file] ?? 1;

        for ($i = 0; $i <= 2; ++$i) {
            $option = [
                'value' => $i,
                'title' => $nv_Lang->getModule('robots_type_' . $i),
                'selected' => ($type == $i) ? ' selected="selected"' : ''
            ];

            $xtpl->assign('OPTION', $option);
            $xtpl->parse('main.loop.option');
        }

        $xtpl->assign('DATA', $data);
        $xtpl->parse('main.loop');
    }
}
foreach ($robots_other as $file => $value) {
    $data = [
        'number' => ++$number,
        'filename' => $file
    ];
    $xtpl->assign('DATA', $data);

    for ($i = 0; $i <= 2; ++$i) {
        $option = [
            'value' => $i,
            'title' => $nv_Lang->getModule('robots_type_' . $i),
            'selected' => ($value == $i) ? ' selected="selected"' : ''
        ];

        $xtpl->assign('OPTION', $option);
        $xtpl->parse('main.other.option');
    }
    $xtpl->parse('main.other');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
