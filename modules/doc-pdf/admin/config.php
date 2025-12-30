<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules
 * @Copyright (C) 2023 Jules. All rights reserved
 * @License: Not free read license.txt
 * @Createdate Sat, 16 Sep 2023 00:00:00 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$page_title = $lang_module['config'];

if ($nv_Request->isset_request('save', 'post')) {
    $config_data = array();
    $config_data['google_service_account_json'] = $nv_Request->get_string('google_service_account_json', 'post', '');
    $config_data['upload_max_filesize'] = $nv_Request->get_int('upload_max_filesize', 'post', 10485760);
    $config_data['allowed_extensions'] = $nv_Request->get_string('allowed_extensions', 'post', 'pdf,doc,docx');
    $config_data['tmp_retention_time'] = $nv_Request->get_int('tmp_retention_time', 'post', 3600);

    foreach ($config_data as $config_name => $config_value) {
        $db->query("REPLACE INTO " . NV_PREFIXLANG . "_config (config_name, config_value) VALUES (" . $db->quote($config_name) . ", " . $db->quote($config_value) . ")");
    }

    $nv_Cache->delMod($module_name);
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=config');
    die();
}

// Fetch current config
$sql = "SELECT config_name, config_value FROM " . NV_PREFIXLANG . "_config";
$result = $db->query($sql);
$array_config = array();
while ($row = $result->fetch()) {
    $array_config[$row['config_name']] = $row['config_value'];
}

$xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', 'config');

$xtpl->assign('DATA', $array_config);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
