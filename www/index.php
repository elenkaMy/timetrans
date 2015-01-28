<?php
require_once(__DIR__.'/protected/utils.php');

$config = dirname(__FILE__).'/protected/config/main-local.php';

require_once(__DIR__.'/protected/config/env-local.php');

defined('IS_CONSOLE') or define('IS_CONSOLE', false);
defined('YII_LOAD_FILE') or define('YII_LOAD_FILE', dirname(__FILE__).'/../framework/yii.php');

require_once(YII_LOAD_FILE);
Yii::createWebApplication($config)->run();
