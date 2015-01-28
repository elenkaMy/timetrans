<?php
require_once(__DIR__.'/utils.php');

defined('IS_CONSOLE') or define('IS_CONSOLE', true);

// change the following paths if necessary
$yiic=dirname(__FILE__).'/../../framework/yiic.php';
$config=dirname(__FILE__).'/config/console.php';

require_once($yiic);
