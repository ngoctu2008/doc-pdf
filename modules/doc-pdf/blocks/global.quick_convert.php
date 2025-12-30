<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules
 * @Copyright (C) 2023 Jules. All rights reserved
 * @License: Not free read license.txt
 * @Createdate Sat, 16 Sep 2023 00:00:00 GMT
 */

if (!defined('NV_MAINFILE')) die('Stop!!!');

if (!function_exists('nv_docpdf_quick_convert')) {
    function nv_docpdf_quick_convert($block_config)
    {
        global $module_name, $lang_module, $module_info, $site_mods;

        $module = $block_config['module'];
        $content = "
        <ul class='list-group'>
            <li class='list-group-item'><a href='" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&" . NV_OP_VARIABLE . "=pdf2word'>PDF to Word</a></li>
            <li class='list-group-item'><a href='" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&" . NV_OP_VARIABLE . "=word2pdf'>Word to PDF</a></li>
            <li class='list-group-item'><a href='" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&" . NV_OP_VARIABLE . "=merge'>Merge PDF</a></li>
        </ul>";

        return $content;
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_docpdf_quick_convert($block_config);
}
