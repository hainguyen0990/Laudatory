<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_THEMES')) {
    exit('Stop!!!');
}

$contents = '';

$file_name = $nv_Request->get_string('file_name', 'get');

if (!empty($file_name)) {
    $module = $nv_Request->get_string('module', 'get', '');
    $selectthemes = $nv_Request->get_string('selectthemes', 'get', '');

    // Xac dinh ton tai cua block
    $path_file_php = $path_file_ini = $block_type = $block_dir = '';

    if ($module == 'theme' and (preg_match($global_config['check_theme'], $selectthemes, $mtheme) or preg_match($global_config['check_theme_mobile'], $selectthemes, $mtheme)) and preg_match($global_config['check_block_theme'], $file_name, $matches) and file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/blocks/' . $file_name)) {
        if (file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini')) {
            $path_file_php = NV_ROOTDIR . '/themes/' . $selectthemes . '/blocks/' . $file_name;
            $path_file_ini = NV_ROOTDIR . '/themes/' . $selectthemes . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini';
            $block_type = 'theme';
            $block_dir = $selectthemes;
        }
    } elseif (isset($site_mods[$module]) and preg_match($global_config['check_block_module'], $file_name, $matches)) {
        $module_file = $site_mods[$module]['module_file'];

        if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $file_name) and file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini')) {
            $path_file_php = NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $file_name;
            $path_file_ini = NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini';
            $block_type = 'module';
            $block_dir = $module_file;
        }
    } else {
        exit();
    }

    if (!empty($path_file_php) and !empty($path_file_ini)) {
        // Neu ton tai file config cua block
        $xml = simplexml_load_file($path_file_ini);

        if ($xml !== false) {
            $function_name = trim($xml->datafunction);

            if (!empty($function_name)) {
                // neu ton tai function de xay dung cau truc cau hinh block
                include_once $path_file_php;

                if (nv_function_exists($function_name)) {
                    //load cau hinh mac dinh cua block
                    $xmlconfig = $xml->xpath('config');
                    $config = (array) $xmlconfig[0];
                    $array_config = [];

                    foreach ($config as $key => $value) {
                        $array_config[$key] = trim($value);
                    }

                    $data_block = $array_config;
                    // Cau hinh cua block
                    $bid = $nv_Request->get_int('bid', 'get,post', 0);

                    if ($bid > 0) {
                        $row_config = $db->query('SELECT module, file_name, config FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $bid)->fetch();
                        if ($row_config['file_name'] == $file_name and $row_config['module'] == $module) {
                            $data_block = unserialize($row_config['config']);
                        }
                    }

                    if ($block_type == 'module' or $block_type == 'theme') {
                        if ($block_type == 'module') {
                            $nv_Lang->loadModule($block_dir, false, true);
                        } else {
                            $nv_Lang->loadTheme($block_dir, true);
                        }

                        $xmllanguage = $xml->xpath('language');
                        if (!empty($xmllanguage)) {
                            $language = (array) $xmllanguage[0];
                            if (isset($language[NV_LANG_INTERFACE])) {
                                $lang_block = (array) $language[NV_LANG_INTERFACE];
                            } elseif (isset($language['en'])) {
                                $lang_block = (array) $language['en'];
                            } else {
                                $key = array_keys($array_config);
                                $lang_block = array_combine($key, $key);
                            }
                            $nv_Lang->setModule($lang_block, '', true);
                        }

                        // Goi ham xu ly hien thi block
                        $contents = call_user_func($function_name, $module, $data_block);

                        // Xóa lang tạm giải phóng bộ nhớ
                        $nv_Lang->changeLang();
                    }
                }
            }
        }
    }
}

nv_htmlOutput($contents);
