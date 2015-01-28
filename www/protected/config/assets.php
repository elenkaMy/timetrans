<?php

/* @var string $webroot path to index script */
/* @var string $basePath path to protected directory */

return array(
    'class'         =>  'ext.ExtendedClientScript.ExtendedClientScript',
    'combineCss'    =>  !YII_DEBUG,
    'compressCss'   =>  !YII_DEBUG,
    'combineJs'     =>  !YII_DEBUG,
    'compressJs'    =>  !YII_DEBUG,
    'replacePaths'  =>  !YII_DEBUG,
    'excludeFiles'  =>  array(
        '/ckeditor\.js$/',
    ),
    'scriptMap' =>  array(
        'jquery.js'     =>  rtrim($webroot, '/') . '/js/vendor/jquery/jquery-1.9.1' . (YII_DEBUG && !IS_CONSOLE ? '' : '.min') . '.js',
        'jquery.min.js' =>  rtrim($webroot, '/') . '/js/vendor/jquery/jquery-1.9.1' . (YII_DEBUG && !IS_CONSOLE ? '' : '.min') . '.js',
    ),
    'excludeFiles'  =>  array(
        '/ckeditor\.js$/',
    ),
    'packages'  =>  array(
        'modernizr'     =>  array(
            'baseUrl'   =>  $webroot,
            'js'        =>  array(
                'js/vendor/modernizr-2.6.2' . (YII_DEBUG && !IS_CONSOLE ? '' : '.min') . '.js',
            ),
        ),
//        'jquery.ui'     =>  array(
//            'baseUrl'   =>  $webroot,
//            'js'        =>  array('js/vendor/jquery-ui/js/jquery-ui-1.10.3.custom' . (YII_DEBUG && !IS_CONSOLE ? '' : '.min') . '.js'),
//            'css'       =>  array('js/vendor/jquery-ui/css/smoothness/jquery-ui-1.10.3.custom' . (YII_DEBUG && !IS_CONSOLE ? '' : '.min') . '.css'),
//            'depends'   =>  array('jquery'),
//        ),
        'app_core'      =>  array(
            'baseUrl'   =>  $webroot,
            'js'        =>  array(
                'js/page/core.js',
            ),
            'css'       =>  array(
                'css/normalize.css',
                'css/style.css',
            ),
            'depends'   =>  array(
                'modernizr',
                'jquery',
            ),
        ),
        'translit'      =>  array(
            'baseUrl'   =>  $webroot,
            'js'        =>  array(
                'js/vendor/translit/jquery.liTranslit.js',
            ),
            'depends'   =>  array(
                'jquery',
            ),
        ),
        'fancybox'      =>  array(
            'baseUrl'   =>  $webroot,
            'js'        =>  array(
                'js/fancybox/jquery.fancybox.pack.js',
            ),
            'css'       =>  array(
                'js/fancybox/jquery.fancybox.css',
            ),
            'depends'   =>  array(
                'jquery',
            ),
        ),
    ),
);
