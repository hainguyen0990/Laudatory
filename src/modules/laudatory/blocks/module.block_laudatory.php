<?php

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_block_laudatory')) {

    function nv_block_laudatory($block_config)
    {
        global $nv_Cache, $site_mods, $db, $module_info, $module_name, $global_config, $module_data;
        $module = $block_config['module'];
        if (isset($site_mods[$module])) {
            $module_data = $site_mods[$module]['module_data'];
            $block_theme = $site_mods[$module]['module_file'];
            $xtpl = new XTemplate('block.laudatory.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $block_theme);
            $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
            $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
            $xtpl->assign('TEMPLATE', $block_theme);
            $xtpl->assign('MODULE', $module);

            $db->sqlreset()
                ->select('id_employed, COUNT(*) as reward_count')
                ->from(NV_PREFIXLANG . '_' . $module_data . '_proposed_reward')
                ->group('id_employed')
                ->order('reward_count DESC')
                ->limit(3);
            $sql = $db->sql();
            $result = $db->query($sql);
            $top_employees = $result->fetchAll();
            $employed_id = [];
            foreach ($top_employees as $employee) {
                $employed_id[] = $employee['id_employed'];
            }

            $name_employee = [];
            if (!empty($employed_id) && is_array($employed_id)) {
                $db->sqlreset()
                    ->select('userid, first_name, last_name, username')
                    ->from(NV_USERS_GLOBALTABLE)
                    ->where('userid IN (' . implode(',', array_map('intval', $employed_id)) . ')');
                $result = $db->query($db->sql());
                while ($row = $result->fetch()) {
                    $name_employee[$row['userid']] = [
                        'userid' => $row['userid'],
                        'full_name' => nv_show_name_user($row['first_name'], $row['last_name']),
                    ];
                }
            }

            $employee_images = [];
            if (!empty($employed_id) && is_array($employed_id)) {
                $db->sqlreset()
                    ->select('userid, photo')
                    ->from(NV_USERS_GLOBALTABLE)
                    ->where('userid IN (' . implode(',', array_map('intval', $employed_id)) . ')');
                $result = $db->query($db->sql());
                while ($row = $result->fetch()) {
                    $employee_images[$row['userid']] = $row['photo'];
                }
            }

            foreach ($top_employees as $employee) {
                $employee['employee_name'] = isset($name_employee[$employee['id_employed']]['full_name']) ? $name_employee[$employee['id_employed']]['full_name'] : '';
                $employee['employee_photo'] = isset($employee_images[$employee['id_employed']]) ? $employee_images[$employee['id_employed']] : '';
                $xtpl->assign('EMPLOYEE', $employee);
                $xtpl->parse('main.employee');
            }

            $xtpl->parse('main');
            return $xtpl->text('main');
        }
        return '';
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_laudatory($block_config);
}
