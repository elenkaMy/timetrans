<?php

$webroot = '/';
/* @var $basePath string path to protected directory */

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
$mainConfig = require(dirname(__FILE__).DIRECTORY_SEPARATOR.'main-local.php');

return array(
    'basePath'      =>  $basePath,
    'name'          =>  'My Console Application',
    'language'      =>  'en',
    'timeZone'      =>  $mainConfig['timeZone'],

    // preloading some components
    'preload'       =>  array(
        'log',
        'response',
    ),
    'import'    =>  array(
        'application.components.*',
        'application.components.console.*',
        'application.components.db.*',
        'application.components.web.*',
        'application.components.user.*',
        'application.components.helpers.*',
        'application.components.validators.*',
    ),

    'commandMap'    =>  array(
        'migrate'       =>  array(
            'class'             =>  'system.cli.commands.MigrateCommand',
            'migrationTable'    =>  '{{migration}}',
            'templateFile'      =>  'application.migrations.template',
//            'migrationPath'     =>  'application.migrations',
//            'connectionID'      =>  'db',
        ),
        'assets'        =>  array(
            'class'                     =>  'application.commands.AssetsCommand',
            'defaultProcessPackages'    =>  array(
                'app_core',
            ),
        ),
        'update'        =>  array_merge(array(
            'class'             =>  'application.commands.UpdateCommand',
        ), isset($mainConfig['params']['update-command']) && is_array($mainConfig['params']['update-command'])
            ? $mainConfig['params']['update-command']
            : array()
        ),
    ),
    // application components
    'components'    =>  array(
        'response'      =>  array(
            'class'     =>  'application.components.console.ConsoleResponse',
        ),
        'clientScript'      =>  array_merge($mainConfig['components']['clientScript'], array(
            'combineCss'    =>  true,
            'compressCss'   =>  true,
            'combineJs'     =>  true,
            'compressJs'    =>  true,
            'replacePaths'  =>  true,
            'basePath'      =>  realpath($basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR),
        )),
        'assetManager'      =>  array_merge(array(
            'class'         =>  'CAssetManager',
        ), (array)@$mainConfig['components']['assetManager'], array(
            'baseUrl'       =>  $webroot.'assets',
            'basePath'      =>  realpath($basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'assets'),
        )),
        'securityManager'   =>  $mainConfig['components']['securityManager'],
        'db'            =>  $mainConfig['components']['db'],
        'cache'         =>  $mainConfig['components']['cache'],
        'authManager'   =>  $mainConfig['components']['authManager'],
        'log'           =>  array(
            'class'     =>  'CLogRouter',
            'routes'    =>  array(
                array(
                    'class'     =>  'CFileLogRoute',
                    'levels'    =>  'error, warning',
                ),
            ),
        ),
        'messages'      =>  $mainConfig['components']['messages'],
        'menuItemHelper'   =>  $mainConfig['components']['menuItemHelper'],
    ),
    'params'        =>  $mainConfig['params'],
);
