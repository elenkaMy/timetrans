<?php

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
isset($webroot) || ($webroot = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'.DIRECTORY_SEPARATOR));
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'YiiBooster');

return array(
    'basePath'  =>  $basePath = dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'      =>  'Gelikon',
    'language'  =>  'ru',
    'timeZone'  =>  'Europe/Minsk',

    // preloading 'log' component
    'preload'   =>  array(
        'log',
        'response',
    ),

    // autoloading model and component classes
    'import'    =>  array(
        'application.models.*',
        'application.components.*',
        'application.components.db.*',
        'application.components.web.*',
        'application.components.user.*',
        'application.components.helpers.*',
        'application.components.validators.*',
        'bootstrap.helpers.*',
        'application.components.menu-items.*',
    ),
    'aliases' => array(
        'xupload' => 'ext.xupload',
    ),
    'modules'   =>  array(
        'page'      =>  array(
            'class'     =>  'application.modules.page.PageModule',
        ),
        'admin'     =>  array(
            'class'     =>  'application.modules.admin.AdminModule',
        ),
        'product'   =>  array(
            'class' =>  'application.modules.product.ProductModule',
        ),
    ),
    // application components
    'components'    =>  array(
        'request' => array(
            'class'         =>  'application.components.web.WebHttpRequest'
        ),
        'securityManager'   =>  array(
            'behaviors' =>  array(
                'passwordGenerator' =>  'ext.PasswordGenerator.BPasswordGenerator',
            ),
        ),
        'user'  =>  array(
            'class'             =>  'application.components.user.WebUser',
            // enable cookie-based authentication
            'allowAutoLogin'    =>  true,
            'loginUrl'          =>  array('page/auth/login'),
        ),
        'authManager'   =>  array(
            'class'         =>  'CPhpAuthManager',
            'showErrors'    =>  YII_DEBUG,
            'defaultRoles'  =>  array(
                'admin',
                'authenticated',
                'guest',
            ),
        ),
        'userHelper'    =>  array(
            'class' =>  'application.components.helpers.UserHelper',
        ),
        'fileHelper'    =>  array(
            'class'     =>  'application.components.file-upload.FileHelper',
            'dir'       =>  $basePath.'/../upload/files/',
            'contexts'  =>  array(
                'default'   =>  array(
                    'class'             =>  'application.components.file-upload.file-contexts.UploadFileContext',
                    'validatorParams'   =>  array('maxSize' => 1024*1024*10),
                ),
                'product'   => array(
                    'class'             =>  'application.components.file-upload.file-contexts.UploadImageContext',
                    'resizeParams'      =>  array(
                        'admin'     =>  array('width' => 100, 'height' => null),
                        'small'     =>  array('width' => 201, 'height' => 174),
                        'origin'    =>  array(),
                        'enough'    =>  array('width' => 233, 'height' => 198),
                    ),
                    'validatorParams'   => array('maxSize' => 10*1024*1024),
                ),
                'productCategory'   => array(
                    'class'             =>  'application.components.file-upload.file-contexts.UploadImageContext',
                    'resizeParams'      =>  array(
                        'admin'        =>  array('width' => 100, 'height' => null),
                        'small'        =>  array('width' => 150, 'height' => 150),
                        'enough'       =>  array('width' => 231, 'height' => 196),
                        'origin'       =>  array(),
                    ),
                    'validatorParams'   => array('maxSize' => 10*1024*1024),
                ),
            ),
        ),
        'breadcrumbsHelper'    =>  array(
            'class' =>  'application.components.helpers.BreadcrumbsHelper',
        ),
        'menuItemHelper'    =>  array(
            'class' =>  'application.components.menu-items.MenuItemHelper',
            'types' => array(
                array(
                    'class' => 'application.components.menu-items.types.UrlMenuItemType',
                ),
                array(
                    'class' => 'application.components.menu-items.types.PageMenuItemType',
                ),
                array(
                    'class' => 'application.components.menu-items.types.ProductMenuItemType',
                ),
                array(
                    'class' => 'application.components.menu-items.types.ProductCategoryMenuItemType',
                ),
                array(
                    'class' => 'application.components.menu-items.types.TextMenuItemType',
                ),
            ),
        ),
        'session'       =>  array(
            'class'     =>  'application.components.web.HttpSession',
        ),
        'urlManager'    =>  require(__DIR__.DIRECTORY_SEPARATOR.'routing.php'),
        'cache' =>  array(
            'class' =>  'CDummyCache',
        ),
        'previewCache' => array(
            'class' => 'CFileCache',
        ),
        'db'    =>  array(
            'class'             =>  'application.components.db.DbConnection',
            'emulatePrepare'    =>  true,
            'charset'           =>  'utf8',
            'charsetVarsValue'  =>  true,
            'initSQLs'          =>  array(
                "SET time_zone = 'Europe/Minsk';",
            ),
        ),
        'response'      =>  array(
            'class' =>  'application.components.web.Response',
        ),
        'errorHandler'  =>  array(
            // use 'site/error' action to display errors
            //'errorAction'   =>  'site/error',
        ),
        'log'   =>  array(
            'class'     =>  'CLogRouter',
            'routes'    =>  array(
                array(
                    'class'     =>  'CFileLogRoute',
                    'levels'    =>  'error, warning',
                ),
            ),
        ),
        'messages'      =>  array(
            'class'     =>  'CGettextMessageSource',
            'useMoFile' =>  !YII_DEBUG,
        ),
        'clientScript'  =>  require(__DIR__.DIRECTORY_SEPARATOR.'assets.php'),
        'mailer'        =>  array(
            'class'         =>  'application.extensions.mailer.PHPMailer',
            'Host'          =>  'localhost',
            'Mailer'        =>  'smtp',
            'Port'          =>  25,
            'SMTPAuth'      =>  false,
            'CharSet'       =>  'Utf-8',
            'FromName'      =>  'No-Reply',
            'FromAddress'   =>  'no-reply@localhost',
        ),
        'bootstrap'     => array(
            'class'         =>  'bootstrap.components.Bootstrap',
        ),
        'favourites'    =>  array(
            'class' =>  'application.components.favourites.Favourites',
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'    =>  require(__DIR__.DIRECTORY_SEPARATOR.'params.php'),
);
