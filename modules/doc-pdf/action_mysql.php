<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules
 * @Copyright (C) 2023 Jules. All rights reserved
 * @License: Not free read license.txt
 * @Createdate Sat, 16 Sep 2023 00:00:00 GMT
 */

if (!defined('NV_IS_FILE_MODULES')) die('Stop!!!');

$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_logs";

$sql_create_module = $sql_drop_module;

// Table config
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config (
  config_name varchar(30) NOT NULL,
  config_value mediumtext NOT NULL,
  UNIQUE KEY config_name (config_name)
) ENGINE=MyISAM";

// Table logs
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_logs (
  id int(11) NOT NULL AUTO_INCREMENT,
  userid int(11) NOT NULL DEFAULT '0',
  action varchar(20) NOT NULL,
  input_file varchar(255) NOT NULL,
  output_file varchar(255) NOT NULL,
  file_size int(11) NOT NULL DEFAULT '0',
  created_at int(11) NOT NULL DEFAULT '0',
  status tinyint(1) NOT NULL DEFAULT '0',
  ip varchar(45) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM";

// Insert default config
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config (config_name, config_value) VALUES
('google_service_account_json', ''),
('upload_max_filesize', '10485760'),
('allowed_extensions', 'pdf,doc,docx'),
('tmp_retention_time', '3600')";
