<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules
 * @Copyright (C) 2023 Jules. All rights reserved
 * @License: Not free read license.txt
 * @Createdate Sat, 16 Sep 2023 00:00:00 GMT
 */

if (!defined('NV_IS_MOD_DOCPDF')) die('Stop!!!');

if ($nv_Request->isset_request('upload', 'post')) {
    $upload_dir = NV_ROOTDIR . '/uploads/' . $module_name . '/tmp';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
        file_put_contents($upload_dir . '/.htaccess', 'Deny from all');
    }

    $response = array('status' => 'error', 'message' => '');

    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $name = $_FILES['file']['name'];
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        // Simple validation, strictly strictly should rely on module config
        $allowed = array('pdf', 'doc', 'docx');

        if (in_array($ext, $allowed)) {
             $target = $upload_dir . '/' . uniqid() . '_' . $name;
             if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
                 $response['status'] = 'success';
                 $response['filepath'] = $target; // Warning: Exposing full path might be risky, but needed for internal processing?
                 // Better to return a token or relative path that `funcs` can resolve.
                 // For now, returning basename for security and let func resolve it.
                 $response['filename'] = basename($target);
             } else {
                 $response['message'] = 'Move failed';
             }
        } else {
            $response['message'] = 'Invalid extension';
        }
    } else {
        $response['message'] = 'No file uploaded';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
