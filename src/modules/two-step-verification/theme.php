<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MOD_2STEP_VERIFICATION')) {
    exit('Stop!!!');
}

/**
 * nv_theme_info_2step()
 *
 * @param array $backupcodes
 * @param bool  $autoshowcode
 * @return string
 */
function nv_theme_info_2step($backupcodes, $autoshowcode)
{
    global $module_info, $nv_Lang, $user_info, $module_name;

    $xtpl = new XTemplate('main.tpl', get_module_tpl_dir('main.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);

    if (empty($user_info['active2step'])) {
        $xtpl->assign('LINK_TURNON', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setup');
        $xtpl->parse('main.turnon');
    } else {
        $xtpl->parse('main.turnoff');
    }

    if (empty($user_info['active2step'])) {
        $xtpl->parse('main.off');
    } else {
        $code_unused = 0;
        foreach ($backupcodes as $code) {
            $code_unused += (!$code['is_used']);
            $xtpl->assign('CODE', $code);

            if ($code['is_used']) {
                $xtpl->parse('main.backupcodeModal.code.used');
            } else {
                $xtpl->parse('main.backupcodeModal.code.unuse');
            }

            $xtpl->parse('main.backupcodeModal.code');
        }
        $xtpl->parse('main.backupcodeModal');

        $xtpl->assign('NUM_CODE', $nv_Lang->getModule('backupcode_2step', $code_unused));

        if ($autoshowcode) {
            $xtpl->parse('main.backupcode.autoshowcode');
        }

        $xtpl->parse('main.backupcode');
        $xtpl->parse('main.on');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_theme_config_2step()
 *
 * @param string $secretkey
 * @param string $nv_redirect
 * @return string
 */
function nv_theme_config_2step($secretkey, $nv_redirect)
{
    global $module_info, $module_name, $op, $global_config;

    $xtpl = new XTemplate('config.tpl', get_module_tpl_dir('config.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);
    $xtpl->assign('NV_REDIRECT', $nv_redirect);

    $xtpl->assign('SECRETKEY', strtolower($secretkey));
    $xtpl->assign('QR_SRC', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/qr-image/' . nv_genpass());
    $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

    if (!empty($global_config['allowuserloginmulti'])) {
        $xtpl->parse('main.forcedrelogin');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_theme_confirm_password()
 *
 * @param bool $is_pass_valid
 * @return string
 */
function nv_theme_confirm_password($is_pass_valid)
{
    global $module_info, $nv_Lang, $op, $module_name;

    $xtpl = new XTemplate('confirm_password.tpl', get_module_tpl_dir('confirm_password.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    if ($is_pass_valid) {
        $xtpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);

        $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

        $xtpl->parse('main');

        return $xtpl->text('main');
    }
    $xtpl->assign('CHANGE_2STEP_NOTVALID', $nv_Lang->getModule('change_2step_notvalid', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=editinfo/password'));
    $xtpl->parse('pass_empty');

    return $xtpl->text('pass_empty');
}
