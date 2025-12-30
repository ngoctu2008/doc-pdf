<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules
 * @Copyright (C) 2023 Jules. All rights reserved
 * @License: Not free read license.txt
 * @Createdate Sat, 16 Sep 2023 00:00:00 GMT
 */

if (!defined('NV_IS_MOD_DOCPDF')) die('Stop!!!');

$filename = $nv_Request->get_string('file', 'get', '');

// Security check: Filename must be basename only (no paths)
if (preg_match('/^[\w\-. ]+$/', $filename)) {
    $file_path = NV_ROOTDIR . '/uploads/' . $module_name . '/tmp/' . $filename;

    if (file_exists($file_path)) {
        require_once NV_ROOTDIR . '/includes/class/download.class.php';
        $download = new nv_download($file_path, $filename);
        $download->download_file();
        exit();
    }
}

die("File not found or access denied.");
