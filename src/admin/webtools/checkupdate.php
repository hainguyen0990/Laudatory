<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_WEBTOOLS')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('checkupdate');
$contents = '';

$xtpl = new XTemplate('checkupdate.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);

if ($nv_Request->isset_request('i', 'get')) {
    $i = $nv_Request->get_string('i', 'get');

    if ($i == 'sysUpd' or $i == 'sysUpdRef') {
        $values = [];
        $values['userVersion'] = $global_config['version'];
        $new_version = ($i == 'sysUpd') ? nv_geVersion(28800) : nv_geVersion(120);

        $error = '';
        if ($new_version === false) {
            $error = $nv_Lang->getModule('error_unknow');
        } elseif (is_string($new_version)) {
            $error = $new_version;
        }

        if (!empty($error)) {
            $xtpl->assign('ERROR', $nv_Lang->getModule('checkSystem') . ': ' . $error);

            $xtpl->parse('error');
            echo $xtpl->text('error');
        } else {
            $values['onlineVersion'] = $nv_Lang->getModule('newVersion_detail', (string) $new_version->version, (string) $new_version->name, nv_datetime_format(strtotime((string) $new_version->date)));
            $xtpl->assign('VALUE', $values);

            if (nv_version_compare($global_config['version'], (string) $new_version->version) < 0) {
                $xtpl->assign('VERSION_INFO', (string) $new_version->message);

                // Allow auto update to newest version
                if ((string) $new_version->version == (string) $new_version->updateable) {
                    $xtpl->assign('VERSION_LINK', $nv_Lang->getModule('newVersion_info1', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=getupdate&amp;version=' . ((string) $new_version->updateable) . '&amp;package=' . ((string) $new_version->updatepackage) . '&amp;checksess=' . md5(((string) $new_version->updateable) . ((string) $new_version->updatepackage) . NV_CHECK_SESSION)));
                } elseif (((string) $new_version->updateable) != '') {
                    $xtpl->assign('VERSION_LINK', $nv_Lang->getModule('newVersion_info2', ((string) $new_version->updateable), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=getupdate&amp;version=' . ((string) $new_version->updateable) . '&amp;package=' . ((string) $new_version->updatepackage) . '&amp;checksess=' . md5(((string) $new_version->updateable) . ((string) $new_version->updatepackage) . NV_CHECK_SESSION)));
                } else {
                    $xtpl->assign('VERSION_LINK', $nv_Lang->getModule('newVersion_info3', (string) $new_version->link));
                }

                $xtpl->parse('sysUpd.inf');
            }

            clearstatcache();
            $sysUpdDate = filemtime(NV_ROOTDIR . '/' . NV_CACHEDIR . '/nukeviet.version.' . NV_LANG_INTERFACE . '.xml');
            $xtpl->assign('SYSUPDDATE', nv_datetime_format($sysUpdDate));

            $xtpl->parse('sysUpd');
            echo $xtpl->text('sysUpd');
        }
    } elseif ($i == 'extUpd' or $i == 'extUpdRef') {
        $exts = ($i == 'extUpd') ? nv_getExtVersion(28800) : nv_getExtVersion(120);

        $error = '';
        if ($exts === false) {
            $error = $nv_Lang->getModule('error_unknow');
        } elseif (is_string($exts)) {
            $error = $exts;
        }

        if (!empty($error)) {
            $xtpl->assign('ERROR', $nv_Lang->getModule('checkExtensions') . ': ' . $error);

            $xtpl->parse('error');
            echo $xtpl->text('error');
        } else {
            clearstatcache();
            $extUpdDate = filemtime(NV_ROOTDIR . '/' . NV_CACHEDIR . '/extensions.version.' . NV_LANG_INTERFACE . '.xml');
            $exts = $exts->xpath('extension');
            $static_exts = (isset($global_config['static_exts']) and is_array($global_config['static_exts'])) ? $global_config['static_exts'] : [];
            $a = 1;

            foreach ($exts as $extname => $values) {
                $ext_type = (string) $values->type;
                $ext_name = (string) $values->name;

                if (!isset($static_exts[$ext_type]) or !in_array($ext_name, $static_exts[$ext_type], true)) {
                    $value = [
                        'id' => (int) $values->id,
                        'type' => $ext_type,
                        'name' => $ext_name,
                        'version' => (string) $values->version,
                        'date' => (string) $values->date,
                        'new_version' => (string) $values->new_version,
                        'new_date' => (string) $values->new_date,
                        'author' => (string) $values->author,
                        'license' => (string) $values->license,
                        'mode' => (string) $values->mode,
                        'message' => (string) $values->message,
                        'link' => (string) $values->link,
                        'support' => (string) $values->support,
                        'updateable' => [],
                        'origin' => ((string) $values->origin) == 'true' ? true : false,
                    ];

                    // Xu ly update
                    $updateables = $values->xpath('updateable/upds/upd');

                    if (!empty($updateables)) {
                        foreach ($updateables as $updateable) {
                            $value['updateable'][] = [
                                'fid' => (string) $updateable->upd_fid,
                                'old' => explode(',', (string) $updateable->upd_old),
                                'new' => (string) $updateable->upd_new,
                            ];
                        }
                    }
                    unset($updateables, $updateable);

                    $info = $nv_Lang->getModule('userVersion') . ': ';
                    $info .= !empty($value['version']) ? $value['version'] : 'n/a';
                    $info .= '; ' . $nv_Lang->getModule('onlineVersion') . ': ';
                    $info .= !empty($value['new_version']) ? $value['new_version'] : ((!empty($value['version']) and $value['origin']) ? $value['version'] : 'n/a');

                    $tooltip = [];
                    $tooltip[] = ['title' => $nv_Lang->getModule('userVersion'), 'content' => (!empty($value['version']) ? $value['version'] : 'n/a') . (!empty($value['date']) ? ' (' . nv_datetime_format(strtotime($value['date'])) . ')' : '')];
                    $tooltip[] = ['title' => $nv_Lang->getModule('onlineVersion'), 'content' => (!empty($value['new_version']) ? $value['new_version'] : ((!empty($value['version']) and $value['origin']) ? $value['version'] : 'n/a')) . (!empty($value['new_date']) ? ' (' . nv_datetime_format(strtotime($value['new_date'])) . ')' : '')];

                    if (!empty($value['author'])) {
                        $tooltip[] = ['title' => $nv_Lang->getModule('extAuthor'), 'content' => $value['author']];
                    }

                    if (!empty($value['license'])) {
                        $tooltip[] = ['title' => $nv_Lang->getModule('extLicense'), 'content' => $value['license']];
                    }

                    if (!empty($value['mode'])) {
                        $tooltip[] = ['title' => $nv_Lang->getModule('extMode'), 'content' => $value['mode'] == 'sys' ? $nv_Lang->getModule('extModeSys') : $nv_Lang->getModule('extModeOther')];
                    }

                    if (!empty($value['link'])) {
                        $tooltip[] = ['title' => $nv_Lang->getModule('extLink'), 'content' => '<a href="' . $value['link'] . '">' . $value['link'] . '</a>'];
                    }

                    if (!empty($value['support'])) {
                        $tooltip[] = ['title' => $nv_Lang->getModule('extSupport'), 'content' => '<a href="' . $value['support'] . '">' . $value['support'] . '</a>'];
                    }

                    $xtpl->assign('EXTNAME', $value['name']);
                    $xtpl->assign('EXTTYPE', $nv_Lang->existsModule('extType_' . $value['type']) ? $nv_Lang->getModule('extType_' . $value['type']) : $value['type']);
                    $xtpl->assign('EXTINFO', $info);

                    foreach ($tooltip as $t) {
                        $xtpl->assign('EXTTOOLTIP', $t);
                        $xtpl->parse('extUpd.loop.li');
                    }

                    // Thong bao ung dung khong co phien ban (Khong hop le)
                    if (!isset($value['version'])) {
                        $xtpl->parse('extUpd.loop.note1');
                    }

                    // Thong tin cap nhat
                    if (!empty($value['new_version']) and nv_version_compare($value['version'], $value['new_version']) < 0) {
                        $note = $nv_Lang->getModule('extNote4');
                        $icon = 'fa-bolt text-warning';

                        $updateVersion = [];

                        foreach ($value['updateable'] as $updateable) {
                            if (in_array($value['version'], $updateable['old'], true)) {
                                if (empty($updateVersion) or nv_version_compare($updateVersion['new'], $updateable['new']) < 0) {
                                    $updateVersion = $updateable;
                                }
                            }
                        }

                        if (empty($updateVersion)) {
                            $xtpl->assign('UPDNOTE', $nv_Lang->getModule('extUpdNote1', $value['link']));
                            $xtpl->parse('extUpd.loop.updateNotSuport');
                        } elseif ($updateVersion['new'] != $value['new_version']) {
                            $xtpl->assign('UPDNOTE', $nv_Lang->getModule('extUpdNote2', $updateVersion['new'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=extensions&amp;' . NV_OP_VARIABLE . '=update&amp;eid=' . $value['id'] . '&amp;fid=' . $updateVersion['fid'] . '&amp;checksess=' . md5($value['id'] . $updateVersion['fid'] . NV_CHECK_SESSION)));
                            $xtpl->parse('extUpd.loop.updateNotLastest');
                        } else {
                            $xtpl->assign('UPDNOTE', $nv_Lang->getModule('extUpdNote3', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=extensions&amp;' . NV_OP_VARIABLE . '=update&amp;eid=' . $value['id'] . '&amp;fid=' . $updateVersion['fid'] . '&amp;checksess=' . md5($value['id'] . $updateVersion['fid'] . NV_CHECK_SESSION)));
                            $xtpl->parse('extUpd.loop.updateLastest');
                        }
                    } elseif (!$value['origin']) {
                        $note = $nv_Lang->getModule('extNote1');
                        $icon = 'fa-exclamation-triangle text-danger';

                        $xtpl->parse('extUpd.loop.note2');
                    } else {
                        $note = $nv_Lang->getModule('extNote5');
                        $icon = 'fa-check text-success';
                    }

                    $xtpl->assign('EXTNOTE', $note);
                    $xtpl->assign('EXTICON', $icon);
                    $xtpl->parse('extUpd.loop');
                    ++$a;
                }
            }

            $xtpl->assign('EXTUPDDATE', nv_datetime_format($extUpdDate));
            $xtpl->assign('LINKNEWEXT', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=extensions&amp;' . NV_OP_VARIABLE . '=newest');

            $xtpl->parse('extUpd');
            echo $xtpl->text('extUpd');
        }
    }
    exit();
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
