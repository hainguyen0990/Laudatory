<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SETTINGS')) {
    exit('Stop!!!');
}

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
if ($checkss == $nv_Request->get_string('checkss', 'post')) {
    $config_key = $nv_Request->get_typed_array('config_key', 'post', 'title', []);
    $config_value = $nv_Request->get_typed_array('config_value', 'post', 'title', []);
    $config_description = $nv_Request->get_typed_array('config_description', 'post', 'title', []);

    $custom_configs = [];
    if (!empty($config_key)) {
        foreach ($config_key as $i => $key) {
            if (preg_match('/^[a-zA-Z][a-zA-Z0-9\_]*$/', $key) and !empty($config_value[$i])) {
                $custom_configs[$key] = [$config_value[$i], $config_description[$i]];
            }
        }
    }
    $custom_configs = !empty($custom_configs) ? json_encode($custom_configs) : '';
    if (empty($global_config['custom_configs'])) {
        if (!empty($custom_configs)) {
            $sth = $db->prepare('INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . NV_LANG_DATA . "', 'global', 'custom_configs', :config_value)");
            $sth->bindParam(':config_value', $custom_configs, PDO::PARAM_STR);
            $sth->execute();
        }
    } else {
        if (!empty($custom_configs)) {
            $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value= :config_value WHERE config_name = 'custom_configs' AND lang = '" . NV_LANG_DATA . "' AND module='global'");
            $sth->bindParam(':config_value', $custom_configs, PDO::PARAM_STR);
            $sth->execute();
        } else {
            $db->query('DELETE FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE config_name = 'custom_configs' AND lang = '" . NV_LANG_DATA . "' AND module='global'");
        }
    }

    $nv_Cache->delAll();

    nv_jsonOutput([
        'status' => 'OK',
        'refresh' => true
    ]);
}

$custom_configs = $db->query('SELECT config_value FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE config_name = 'custom_configs' AND lang='" . NV_LANG_DATA . "' AND module='global'")->fetchColumn();
$custom_configs = !empty($custom_configs) ? json_decode($custom_configs, true) : ['' => ['', '']];

$xtpl = new XTemplate('custom.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('CHECKSS', $checkss);

foreach ($custom_configs as $key => $vals) {
    $custom = [
        'key' => $key,
        'value' => is_array($vals) ? $vals[0] : $vals,
        'description' => is_array($vals) ? $vals[1] : ''
    ];
    $xtpl->assign('CUSTOM', $custom);
    $xtpl->parse('main.loop');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $nv_Lang->getModule('custom_configs', $language_array[NV_LANG_DATA]['name']);

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
