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
require_once NV_ROOTDIR . '/modules/' . $module_file . '/includes/GoogleDriveDriver.php';

use NukeViet\Module\DocPdf\GoogleDriveDriver;

$page_title = $lang_module['pdf2word'];

$error = '';
$result_link = '';

if ($nv_Request->isset_request('submit', 'post')) {
    $upload_dir = NV_ROOTDIR . '/uploads/' . $module_name . '/tmp';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
        file_put_contents($upload_dir . '/.htaccess', 'Deny from all');
    }

    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
        $name = $_FILES['pdf_file']['name'];
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if ($ext == 'pdf') {
             $target = $upload_dir . '/' . uniqid() . '_' . $name;
             if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $target)) {

                 // Get Google Config
                 $sql = "SELECT config_value FROM " . NV_PREFIXLANG . "_" . $module_data . "_config WHERE config_name='google_service_account_json'";
                 $row = $db->query($sql)->fetch();
                 $jsonAuth = $row['config_value'];

                 if ($jsonAuth) {
                     try {
                         $driver = new GoogleDriveDriver($jsonAuth, $upload_dir);
                         $output = $driver->convertToWord($target);

                         // Log
                        $db->query("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_logs (userid, action, input_file, output_file, file_size, created_at, status, ip) VALUES (
                            " . $user_info['userid'] . ",
                            'pdf2word',
                            '" . $name . "',
                            '" . basename($output) . "',
                            " . filesize($output) . ",
                            " . NV_CURRENTTIME . ",
                            1,
                            '" . $client_info['ip'] . "'
                        )");

                         $result_link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=download&file=' . basename($output);

                         @unlink($target);
                     } catch (Exception $e) {
                         $error = "Conversion failed: " . $e->getMessage();
                         // Log Error
                         $db->query("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_logs (userid, action, input_file, output_file, file_size, created_at, status, ip) VALUES (
                            " . $user_info['userid'] . ",
                            'pdf2word',
                            '" . $name . "',
                            'Failed',
                            0,
                            " . NV_CURRENTTIME . ",
                            0,
                            '" . $client_info['ip'] . "'
                        )");
                     }
                 } else {
                     $error = "System configuration error (Missing Google API Key).";
                 }

             } else {
                 $error = $lang_module['error_upload'];
             }
        } else {
            $error = $lang_module['error_file_type'];
        }
    }
}

$xtpl = new XTemplate('pdf2word.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', 'pdf2word');
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
