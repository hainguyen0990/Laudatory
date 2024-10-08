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

$adminThemes = [''];
$adminThemes = array_merge($adminThemes, nv_scandir(NV_ROOTDIR . '/themes', $global_config['check_theme_admin']));
unset($adminThemes[0]);

$closed_site_Modes = [];
$closed_site_Modes[0] = $nv_Lang->getModule('closed_site_0');
if (defined('NV_IS_GODADMIN')) {
    $closed_site_Modes[1] = $nv_Lang->getModule('closed_site_1');
}
$closed_site_Modes[2] = $nv_Lang->getModule('closed_site_2');
$closed_site_Modes[3] = $nv_Lang->getModule('closed_site_3');

// Thay đổi chế độ site
if (defined('NV_IS_GODADMIN')) {
    if ($nv_Request->isset_request('site_mode', 'post')) {
        $array_config_global = [];
        $array_config_global['closed_site'] = $nv_Request->get_int('closed_site', 'post', $global_config['closed_site']);
        if (!isset($closed_site_Modes[$array_config_global['closed_site']])) {
            $array_config_global['closed_site'] = $global_config['closed_site'];
        }

        $reopening_date = !empty($array_config_global['closed_site']) ? $nv_Request->get_title('reopening_date', 'post', '') : '';
        if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $reopening_date, $m)) {
            $reopening_hour = $nv_Request->get_int('reopening_hour', 'post', 0);
            $reopening_min = $nv_Request->get_int('reopening_min', 'post', 0);
            $array_config_global['site_reopening_time'] = mktime($reopening_hour, $reopening_min, 0, $m[2], $m[1], $m[3]);
        } else {
            $array_config_global['site_reopening_time'] = 0;
        }

        $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
        foreach ($array_config_global as $config_name => $config_value) {
            $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
            $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }

        nv_save_file_config_global();

        nv_jsonOutput([
            'status' => 'OK',
            'refresh' => true
        ]);
    }
}

$allow_sitelangs = [];
foreach ($global_config['allow_sitelangs'] as $lang_i) {
    if (file_exists(NV_ROOTDIR . '/includes/language/' . $lang_i . '/global.php')) {
        $allow_sitelangs[] = $lang_i;
    }
}

$timezone_array = array_keys($nv_parse_ini_timezone);
$array_config_define = [];

// Lưu Cấu hình chung
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
if ($checkss == $nv_Request->get_string('checkss', 'post')) {
    $errormess = '';
    $array_config_site = [];

    $admin_theme = $nv_Request->get_string('admin_theme', 'post');
    $array_config_site['admin_theme'] = (!empty($admin_theme) and in_array($admin_theme, $adminThemes, true)) ? $admin_theme : '';

    $site_email = nv_substr($nv_Request->get_title('site_email', 'post', '', 1), 0, 255);
    $check = nv_check_valid_email($site_email, true);
    if ($check[0] == '') {
        $array_config_site['site_email'] = $check[1];
    } else {
        $array_config_site['site_email'] = '';
    }

    $array_config_site['site_phone'] = nv_substr($nv_Request->get_string('site_phone', 'post', '', false, false), 0, 50);
    if (preg_match('/\[(.+)\]$/', $array_config_site['site_phone'])) {
        $array_config_site['site_phone'] = preg_replace_callback('/\[(.+)\]/', function ($matches) {
            return preg_replace('/[^0-9\.\,\;\+\-\*\#\[\]]+/', '', $matches[0]);
        }, $array_config_site['site_phone']);
    }

    $array_config_site['searchEngineUniqueID'] = $nv_Request->get_title('searchEngineUniqueID', 'post', '');
    if (preg_match('/[^a-zA-Z0-9\:\-\_\.]/', $array_config_site['searchEngineUniqueID'])) {
        $array_config_site['searchEngineUniqueID'] = '';
    }

    $array_config_site['ssl_https'] = $nv_Request->get_int('ssl_https', 'post');
    if ($array_config_site['ssl_https'] < 0 or $array_config_site['ssl_https'] > 2) {
        $array_config_site['ssl_https'] = 0;
    }

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config_site as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    if (defined('NV_IS_GODADMIN')) {
        $array_config_global = [];
        $site_timezone = $nv_Request->get_title('site_timezone', 'post', '', 0);
        if (empty($site_timezone) or (!empty($site_timezone) and (in_array($site_timezone, $timezone_array, true) or $site_timezone == 'byCountry'))) {
            $array_config_global['site_timezone'] = $site_timezone;
        }
        $my_domains = $nv_Request->get_title('my_domains', 'post', '');
        if (!empty($my_domains)) {
            $my_domains = array_map('trim', explode(',', $my_domains));
            $sizeof = sizeof($my_domains);
            for ($i = 0; $i < $sizeof; ++$i) {
                $dm = preg_replace('/^(http|https)\:\/\//', '', $my_domains[$i]);
                $dm = preg_replace('/^([^\/]+)\/*(.*)$/', '\\1', $dm);
                $_p = '';
                $m = [];
                if (preg_match('/(.*)(\:[0-9]+)$/', $dm, $m)) {
                    $dm = $m[1];
                    $_p = $m[2];
                }
                $dm = nv_check_domain(nv_strtolower($dm));
                if (!empty($dm)) {
                    $my_domains[$i] = $dm . $_p;
                } else {
                    unset($my_domains[$i]);
                }
            }
        } else {
            $my_domains = [];
        }
        array_unshift($my_domains, NV_SERVER_NAME);
        $my_domains = array_unique($my_domains);
        $my_domains = array_values($my_domains);

        $array_config_global['my_domains'] = implode(',', $my_domains);

        $array_config_global['gzip_method'] = $nv_Request->get_int('gzip_method', 'post');
        $array_config_global['resource_preload'] = $nv_Request->get_int('resource_preload', 'post');
        $array_config_global['lang_multi'] = $nv_Request->get_int('lang_multi', 'post');

        $array_config_global['notification_active'] = $nv_Request->get_int('notification_active', 'post');
        $array_config_global['notification_autodel'] = $nv_Request->get_int('notification_autodel', 'post', 15);
        if ($array_config_global['notification_active'] != $global_config['notification_active']) {
            $db->query('UPDATE ' . $db_config['dbsystem'] . '.' . NV_CRONJOBS_GLOBALTABLE . ' SET act=' . $array_config_global['notification_active'] . ', last_time=' . NV_CURRENTTIME . ', last_result=0 WHERE run_func="cron_notification_autodel"');
        }

        $site_lang = $nv_Request->get_title('site_lang', 'post', '', 1);
        if (!empty($site_lang) and in_array($site_lang, $allow_sitelangs, true)) {
            $array_config_global['site_lang'] = $site_lang;
        }

        $array_config_global['rewrite_enable'] = $nv_Request->get_int('rewrite_enable', 'post', 0);
        if ($array_config_global['lang_multi'] == 0) {
            if ($array_config_global['rewrite_enable']) {
                $array_config_global['rewrite_optional'] = $nv_Request->get_int('rewrite_optional', 'post', 0);
            } else {
                $array_config_global['rewrite_optional'] = 0;
            }
            $array_config_global['lang_geo'] = 0;
            $array_config_global['rewrite_op_mod'] = $nv_Request->get_title('rewrite_op_mod', 'post');
            if (!isset($site_mods[$array_config_global['rewrite_op_mod']]) or $array_config_global['rewrite_optional'] == 0) {
                $array_config_global['rewrite_op_mod'] = '';
            }
        } else {
            $array_config_global['rewrite_optional'] = 0;
            $array_config_global['lang_geo'] = $nv_Request->get_int('lang_geo', 'post', 0);
            $array_config_global['rewrite_op_mod'] = '';
        }

        $array_config_global['error_set_logs'] = $nv_Request->get_int('error_set_logs', 'post', 0);
        $array_config_global['error_separate_file'] = $nv_Request->get_int('error_separate_file', 'post', 0);
        $error_send_email = nv_substr($nv_Request->get_title('error_send_email', 'post', '', 1), 0, 255);
        $check = nv_check_valid_email($error_send_email, true);
        if ($check[0] == '') {
            $array_config_global['error_send_email'] = $check[1];
        } else {
            $array_config_global['error_send_email'] = '';
        }

        $array_config_global['static_noquerystring'] = (int) $nv_Request->get_bool('static_noquerystring', 'post', false);

        $array_config_global['unsign_vietwords'] = (int) $nv_Request->get_bool('unsign_vietwords', 'post', false);

        $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
        foreach ($array_config_global as $config_name => $config_value) {
            $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
            $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }

        // Cấu hình ghi ra hằng
        $array_config_define['nv_debug'] = (int) $nv_Request->get_bool('nv_debug', 'post');

        $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'define' AND config_name = :config_name");
        foreach ($array_config_define as $config_name => $config_value) {
            $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
            $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }

        nv_save_file_config_global();

        $array_config_rewrite = [
            'rewrite_enable' => $array_config_global['rewrite_enable'],
            'rewrite_optional' => $array_config_global['rewrite_optional'],
            'rewrite_endurl' => $global_config['rewrite_endurl'],
            'rewrite_exturl' => $global_config['rewrite_exturl'],
            'rewrite_op_mod' => $array_config_global['rewrite_op_mod'],
        ];
        $rewrite = nv_rewrite_change($array_config_rewrite);
        if (empty($rewrite[0])) {
            $errormess .= $nv_Lang->getModule('err_writable', $rewrite[1]);
        }

        $diff1 = array_diff($my_domains, $global_config['my_domains']);
        $diff2 = array_diff($global_config['my_domains'], $my_domains);
        if (!empty($diff1) or !empty($diff2)) {
            $save_config = nv_server_config_change($my_domains);
            if ($save_config[0] !== true) {
                $errormess .= (!empty($errormess) ? '<br/>' : '') . $nv_Lang->getModule('err_save_sysconfig', $save_config[1]);
            }
        }
    } else {
        $nv_Cache->delAll(false);
    }

    if (!empty($errormess)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $errormess
        ]);
    } else {
        nv_jsonOutput([
            'status' => 'OK'
        ]);
    }
}

$page_title = $nv_Lang->getModule('global_config');

$array_config_define['nv_debug'] = NV_DEBUG;
$global_config['checkss'] = $checkss;
$global_config['reopening_date'] = '';
$global_config['reopening_hour'] = 0;
$global_config['reopening_min'] = 0;
if (!empty($global_config['site_reopening_time'])) {
    $tdate = date('d/m/Y|H|i', $global_config['site_reopening_time']);
    [$global_config['reopening_date'], $global_config['reopening_hour'], $global_config['reopening_min']] = explode('|', $tdate);
}

if (!empty($global_config['site_phone']) and !empty($global_config['site_int_phone'])) {
    $global_config['site_phone'] .= '[' . $global_config['site_int_phone'] . ']';
}

$xtpl = new XTemplate('system.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('DATA', $global_config);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

for ($i = 0; $i <= 2; ++$i) {
    $ssl_https = [
        'key' => $i,
        'title' => $nv_Lang->getModule('ssl_https_' . $i),
        'selected' => $i == $global_config['ssl_https'] ? ' selected="selected"' : ''
    ];

    $xtpl->assign('SSL_HTTPS', $ssl_https);
    $xtpl->parse('main.ssl_https');
}

if (defined('NV_IS_GODADMIN')) {
    $result = $db->query('SELECT config_name, config_value FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE lang='sys' AND module='global'");
    while ([$c_config_name, $c_config_value] = $result->fetch(3)) {
        $array_config_global[$c_config_name] = $c_config_value;
    }

    $lang_multi = $array_config_global['lang_multi'];
    $array_config_define['nv_debug'] = empty($array_config_define['nv_debug']) ? '' : ' checked="checked"';

    $xtpl->assign('CHECKED_GZIP_METHOD', ($array_config_global['gzip_method']) ? ' checked="checked"' : '');
    $xtpl->assign('CHECKED_LANG_MULTI', ($array_config_global['lang_multi']) ? ' checked="checked"' : '');
    $xtpl->assign('CHECKED_NOTIFI_ACTIVE', ($array_config_global['notification_active']) ? ' checked="checked"' : '');
    $xtpl->assign('CHECKED_ERROR_SET_LOGS', ($array_config_global['error_set_logs']) ? ' checked="checked"' : '');
    $xtpl->assign('CHECKED_ERROR_SEPARATE_FILE', ($array_config_global['error_separate_file']) ? ' checked="checked"' : '');
    $xtpl->assign('CHECKED_REWRITE_ENABLE', ($array_config_global['rewrite_enable'] == 1) ? ' checked ' : '');
    $xtpl->assign('CHECKED_REWRITE_OPTIONAL', ($array_config_global['rewrite_optional'] == 1) ? ' checked ' : '');
    $xtpl->assign('STATIC_NOQUERYSTRING_CHECKED', ($array_config_global['static_noquerystring'] == 1) ? ' checked ' : '');
    $xtpl->assign('CHECKED_UNSIGN_VIETWORDS', !empty($array_config_global['unsign_vietwords']) ? ' checked="checked"' : '');
    $xtpl->assign('CFG_DEFINE', $array_config_define);
    $xtpl->assign('SITE_STATUS', [
        'panel' => empty($global_config['closed_site']) ? 'success' : 'danger',
        'icon' => empty($global_config['closed_site']) ? 'fa-check' : 'fa-ban',
        'title' => $closed_site_Modes[$global_config['closed_site']],
        'reopening' => !empty($global_config['site_reopening_time']) ? $nv_Lang->getModule('closed_site_reopening_time') . ': ' . nv_datetime_format($global_config['site_reopening_time']) : ''
    ]);

    $xtpl->assign('MY_DOMAINS', $array_config_global['my_domains']);
    $xtpl->parse('main.my_domains');

    foreach ($closed_site_Modes as $value => $name) {
        $xtpl->assign('MODE_VALUE', $value);
        $xtpl->assign('MODE_NAME', $name);
        $xtpl->assign('MODE_SELECTED', ($value == $global_config['closed_site'] ? ' selected="selected"' : ''));
        $xtpl->parse('main.closed_site.closed_site_mode');
    }

    if (empty($global_config['closed_site'])) {
        $xtpl->parse('main.closed_site.reopening_time');
    }

    for ($i = 0; $i <= 23; ++$i) {
        $xtpl->assign('RHOUR', [
            'num' => $i,
            'sel' => $i == $global_config['reopening_hour'] ? ' selected="selected"' : '',
            'title' => str_pad($i, 2, 0, STR_PAD_LEFT)
        ]);
        $xtpl->parse('main.closed_site.reopening_hour');
    }

    for ($i = 0; $i <= 59; ++$i) {
        $xtpl->assign('RMIN', [
            'num' => $i,
            'sel' => $i == $global_config['reopening_min'] ? ' selected="selected"' : '',
            'title' => str_pad($i, 2, 0, STR_PAD_LEFT)
        ]);
        $xtpl->parse('main.closed_site.reopening_min');
    }

    if (!empty($global_config['site_reopening_time'])) {
        $xtpl->parse('main.closed_site.reopening');
    }

    $xtpl->parse('main.closed_site');

    $xtpl->assign('CURRENT_TIME', $nv_Lang->getModule('current_time', nv_datetime_format(NV_CURRENTTIME, 1, 0)));
    $xtpl->assign('TIMEZONEOP', 'byCountry');
    $xtpl->assign('TIMEZONESELECTED', ($array_config_global['site_timezone'] == 'byCountry') ? "selected='selected'" : '');
    $xtpl->assign('TIMEZONELANGVALUE', $nv_Lang->getModule('timezoneByCountry'));
    $xtpl->parse('main.timesettings.opsite_timezone');

    sort($timezone_array);
    foreach ($timezone_array as $site_timezone_i) {
        $xtpl->assign('TIMEZONEOP', $site_timezone_i);
        $xtpl->assign('TIMEZONESELECTED', ($site_timezone_i == $array_config_global['site_timezone']) ? "selected='selected'" : '');
        $xtpl->assign('TIMEZONELANGVALUE', $site_timezone_i);
        $xtpl->parse('main.timesettings.opsite_timezone');
    }
    $xtpl->parse('main.timesettings');

    foreach ($site_mods as $mod => $row) {
        $xtpl->assign('MODE_VALUE', $mod);
        $xtpl->assign('MODE_SELECTED', ($mod == $array_config_global['rewrite_op_mod']) ? "selected='selected'" : '');
        $xtpl->assign('MODE_NAME', $row['custom_title']);
        $xtpl->parse('main.lang_rewrite.rewrite_op_mod');
    }

    $xtpl->assign('SHOW_REWRITE_OPTIONAL', (empty($lang_multi) and $array_config_global['rewrite_enable']) ? '' : ' style="display:none"');
    $xtpl->assign('SHOW_REWRITE_OP_MOD', ($array_config_global['rewrite_optional'] == 1) ? '' : ' style="display:none"');

    if (sizeof($global_config['allow_sitelangs']) > 1) {
        foreach ($allow_sitelangs as $lang_i) {
            $xtpl->assign('LANGOP', $lang_i);
            $xtpl->assign('SELECTED', ($lang_i == $array_config_global['site_lang']) ? "selected='selected'" : '');
            $xtpl->assign('LANGVALUE', $language_array[$lang_i]['name']);
            $xtpl->parse('main.lang_rewrite.lang_multi.site_lang_option');
        }
        $xtpl->assign('CONFIG_LANG_GEO', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=language&' . NV_OP_VARIABLE . '=countries');
        $xtpl->assign('CHECKED_LANG_GEO', ($array_config_global['lang_geo'] == 1) ? ' checked ' : '');
        if (empty($lang_multi)) {
            $xtpl->parse('main.lang_rewrite.lang_multi.hide_geo');
        }
        $xtpl->parse('main.lang_rewrite.lang_multi');
    }

    $xtpl->parse('main.lang_rewrite');

    $xtpl->parse('main.error_handling');

    $preload_opts = [$nv_Lang->getModule('resource_preload_not'), $nv_Lang->getModule('resource_preload_headers'), $nv_Lang->getModule('resource_preload_html')];
    foreach ($preload_opts as $k => $name) {
        $xtpl->assign('PRELOAD', [
            'val' => $k,
            'sel' => $k == $array_config_global['resource_preload'] ? ' selected="selected"' : '',
            'name' => $name
        ]);
        $xtpl->parse('main.optimization_settings.resource_preload');
    }

    $xtpl->parse('main.optimization_settings');

    $xtpl->parse('main.unsign_vietwords');
}

foreach ($adminThemes as $name) {
    $xtpl->assign('THEME_NAME', $name);
    $xtpl->assign('THEME_SELECTED', ($name == $global_config['admin_theme'] ? ' selected="selected"' : ''));
    $xtpl->parse('main.admin_theme');
}

$xtpl->parse('main');
$content = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($content);
include NV_ROOTDIR . '/includes/footer.php';
