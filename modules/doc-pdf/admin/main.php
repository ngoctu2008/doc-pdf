<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules
 * @Copyright (C) 2023 Jules. All rights reserved
 * @License: Not free read license.txt
 * @Createdate Sat, 16 Sep 2023 00:00:00 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$page_title = $lang_module['main'];

// Simple stats
$stats = array();
$stats['pdf2word'] = $db->query("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_logs WHERE action='pdf2word'")->fetchColumn();
$stats['word2pdf'] = $db->query("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_logs WHERE action='word2pdf'")->fetchColumn();
$stats['merge'] = $db->query("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_logs WHERE action='merge'")->fetchColumn();
$stats['split'] = $db->query("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_logs WHERE action='split'")->fetchColumn();

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('STATS', $stats);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
