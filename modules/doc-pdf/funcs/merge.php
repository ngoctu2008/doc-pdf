<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules
 * @Copyright (C) 2023 Jules. All rights reserved
 * @License: Not free read license.txt
 * @Createdate Sat, 16 Sep 2023 00:00:00 GMT
 */

if (!defined('NV_IS_MOD_DOCPDF')) die('Stop!!!');

$autoload_path = NV_ROOTDIR . '/modules/' . $module_file . '/vendor/autoload.php';
if (!file_exists($autoload_path)) {
    $contents = '<div class="alert alert-danger">
        <strong>Module Error:</strong> Missing dependencies.<br>
        Please run <code>composer install</code> in <code>modules/' . $module_file . '</code> directory to install required libraries (Google API Client, FPDI).
    </div>';
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
    die();
}
require_once $autoload_path;
require_once NV_ROOTDIR . '/modules/' . $module_file . '/includes/LocalPdfManipulator.php';

use NukeViet\Module\DocPdf\LocalPdfManipulator;

$page_title = $lang_module['merge'];

$error = '';
$result_link = '';

if ($nv_Request->isset_request('submit', 'post')) {
    // Handle files
    // Since users might upload multiple files via AJAX or standard form, let's assume standard form for simplicity first,
    // or handle the 'sorted' list of files if we implemented the UI.
    // However, keeping it simple: Standard multiple file upload.

    $uploaded_files = [];
    $upload_dir = NV_ROOTDIR . '/uploads/' . $module_name . '/tmp';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
        file_put_contents($upload_dir . '/.htaccess', 'Deny from all');
    }

    // Check if files are uploaded
    if (isset($_FILES['pdf_files']) && count($_FILES['pdf_files']['name']) > 0) {
        $count = count($_FILES['pdf_files']['name']);

        // Loop through files
        for ($i = 0; $i < $count; $i++) {
            if ($_FILES['pdf_files']['error'][$i] == 0) {
                 $tmp_name = $_FILES['pdf_files']['tmp_name'][$i];
                 $name = $_FILES['pdf_files']['name'][$i];
                 $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                 if ($ext == 'pdf') {
                     $target = $upload_dir . '/' . uniqid() . '_' . $name;
                     if (move_uploaded_file($tmp_name, $target)) {
                         $uploaded_files[] = $target;
                     }
                 }
            }
        }
    }

    if (count($uploaded_files) >= 2) {
        try {
            $manipulator = new LocalPdfManipulator($upload_dir);
            $output = $manipulator->merge($uploaded_files);

            // Log
            $db->query("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_logs (userid, action, input_file, output_file, file_size, created_at, status, ip) VALUES (
                " . $user_info['userid'] . ",
                'merge',
                '" . count($uploaded_files) . " files',
                '" . basename($output) . "',
                " . filesize($output) . ",
                " . NV_CURRENTTIME . ",
                1,
                '" . $client_info['ip'] . "'
            )");

            $result_link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=download&file=' . basename($output);

            // Clean up input files
            foreach ($uploaded_files as $f) {
                @unlink($f);
            }

        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } else {
        $error = "Please upload at least 2 PDF files.";
    }
}

$xtpl = new XTemplate('merge.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', 'merge');
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

if ($error) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

if ($result_link) {
    $xtpl->assign('RESULT_LINK', $result_link);
    $xtpl->parse('main.result');
} else {
    $xtpl->parse('main.form');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
