<?php
require_once(__DIR__.'/protected/utils.php');

/**
 * This is the bootstrap file for test application.
 * This file should be removed when the application is deployed for production.
 */

if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1'))
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/yii.php';
$config=dirname(__FILE__).'/protected/config/test.php';

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('IS_CONSOLE') or define('IS_CONSOLE', false);

require_once($yii);
Yii::createWebApplication($config)->run();
