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

$page_title = $lang_module['split'];

$error = '';
$result_link = '';

// Step 2: Process split
if ($nv_Request->isset_request('submit_split', 'post')) {
    $file_path = $nv_Request->get_string('file_path', 'post', '');
    $ranges = $nv_Request->get_string('ranges', 'post', '');
    $split_mode = $nv_Request->get_string('split_mode', 'post', 'all');

    // Verify file path (security check to ensure it's in tmp)
    $upload_dir = NV_ROOTDIR . '/uploads/' . $module_name . '/tmp';
    $real_path = realpath($file_path);
    if ($real_path && strpos($real_path, realpath($upload_dir)) === 0 && file_exists($real_path)) {

        try {
            $manipulator = new LocalPdfManipulator($upload_dir);
            $pageCount = $manipulator->getPageCount($real_path);

            $oneFilePerPage = false;

            if ($split_mode == 'all') {
                $ranges = "1-" . $pageCount;
                $oneFilePerPage = true; // "Extract all" usually implies separate files
            }

            $outputFiles = $manipulator->split($real_path, $ranges, $oneFilePerPage);

            if (empty($outputFiles)) {
                throw new Exception("No pages extracted.");
            }

            $finalOutput = '';

            if (count($outputFiles) == 1) {
                $finalOutput = $outputFiles[0];
            } else {
                // Zip them
                $zipFile = $upload_dir . '/' . uniqid() . '_split_files.zip';
                $zip = new ZipArchive();
                if ($zip->open($zipFile, ZipArchive::CREATE) !== TRUE) {
                    throw new Exception("Cannot create zip.");
                }
                foreach ($outputFiles as $f) {
                    $zip->addFile($f, basename($f));
                }
                $zip->close();

                // Cleanup individual pdfs
                foreach ($outputFiles as $f) {
                    @unlink($f);
                }

                $finalOutput = $zipFile;
            }

             // Log
            $db->query("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_logs (userid, action, input_file, output_file, file_size, created_at, status, ip) VALUES (
                " . $user_info['userid'] . ",
                'split',
                '" . basename($real_path) . "',
                '" . basename($finalOutput) . "',
                " . filesize($finalOutput) . ",
                " . NV_CURRENTTIME . ",
                1,
                '" . $client_info['ip'] . "'
            )");

            $result_link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=download&file=' . basename($finalOutput);

             // Clean up input
            @unlink($real_path);

        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } else {
        $error = "Invalid file.";
    }
}

// Step 1: Upload analysis
$uploaded_file = '';
$total_pages = 0;

if ($nv_Request->isset_request('upload', 'post') && !isset($result_link)) {
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
                 $uploaded_file = $target;
                 try {
                     $manipulator = new LocalPdfManipulator($upload_dir);
                     $total_pages = $manipulator->getPageCount($target);
                 } catch (Exception $e) {
                     $error = "Could not read PDF: " . $e->getMessage();
                     @unlink($target);
                     $uploaded_file = '';
                 }
             }
        } else {
            $error = $lang_module['error_file_type'];
        }
    }
}

$xtpl = new XTemplate('split.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', 'split');
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

if ($error) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

if ($result_link) {
    $xtpl->assign('RESULT_LINK', $result_link);
    $xtpl->parse('main.result');
} elseif ($uploaded_file) {
    $xtpl->assign('FILE_PATH', $uploaded_file);
    $xtpl->assign('TOTAL_PAGES', $total_pages);
    $xtpl->parse('main.options');
} else {
    $xtpl->parse('main.upload');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
