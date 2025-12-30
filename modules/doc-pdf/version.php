<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YOUR NAME (email@domain.com)
 * @Copyright (C) 2023 YOUR NAME. All rights reserved
 * @License: Not free read license.txt
 * @Createdate Sat, 16 Sep 2023 00:00:00 GMT
 */

if (!defined('NV_MAINFILE')) die('Stop!!!');

$module_version = array(
    'name' => 'DocPDF',
    'modfuncs' => 'main,pdf2word,word2pdf,merge,split,download',
    'change_alias' => 'main,pdf2word,word2pdf,merge,split,download',
    'submenu' => 'main,pdf2word,word2pdf,merge,split',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '1.0.00',
    'date' => 'Sat, 16 Sep 2023 00:00:00 GMT',
    'author' => 'Jules',
    'uploads_dir' => array($module_name, $module_name . '/tmp'),
    'note' => 'Module convert and manipulate PDF files'
);
