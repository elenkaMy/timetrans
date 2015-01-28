<?php

/* @var string $webroot path to index script */
/* @var string $basePath path to protected directory */

// routing
return array(
    'urlFormat'         =>  'path',
    'showScriptName'    =>  false,
    'useStrictParsing'  =>  !YII_DEBUG, // for gii in debug mode
    'urlSuffix'         =>  '/',
    'rules'             =>  array_merge(array(
        // page routes
        

        // auth routes
//        array('page/auth/login',        'pattern' => 'login'),
//        array('page/auth/logout',       'pattern' => 'logout'),

        // db urls
        array(
            'class'             =>  'application.components.web.DbUrlRule',
            'route'             =>  'page/page/index',
            'prefix'            =>  '',
            'model'             =>  'Page',
            'parentRelation'    =>  'parentPage',
        ),

        array(
            'class'             =>  'application.components.web.DbUrlRule',
            'route'             =>  $categoryRoute = 'product/category/index',
            'prefix'            =>  $productsPrefix = 'products/',
            'model'             =>  'ProductCategory',
            'parentRelation'    =>  'parentCategory',
        ),

        array(
            'class'             =>  'application.components.web.routing.DbProductUrlRule',
            'route'             =>  'product/product/index',
            'prefix'            =>  $productsPrefix,
            'categoryRoute'     =>  $categoryRoute,
        ),
        
        array('page/default/index',     'pattern' => ''),
        
        array('product/product/send',    'pattern' => 'ajax/products/send'),
        
        //favourites routes
        array('product/favourites/add',     'pattern' => 'favourites/product/<product_id:\d+>/add'),
        array('product/favourites/view',    'pattern' => 'favourites'),
        array('product/favourites/clear',   'pattern' => 'favourites/clear'),
        array('product/favourites/delete',  'pattern' => 'favourites/product/<product_id:\d+>/delete'),
        array('product/favourites/change',  'pattern' => 'favourites/product/<product_id:\d+>/change'),
        
        // admin default pages
        array('admin/default/index',    'pattern' => 'admin'),

        array('admin/auth/login',       'pattern' => 'admin/login'),
        array('admin/auth/logout',      'pattern' => 'admin/logout'),

        // admin users pages
        array('admin/user/index',       'pattern' => 'admin/users'),
        array('admin/user/view',        'pattern' => 'admin/users/<user_id:\d+>/view'),
        array('admin/user/create',      'pattern' => 'admin/users/create'),
        array('admin/user/update',      'pattern' => 'admin/users/<user_id:\d+>/update'),
        array('admin/user/delete',      'pattern' => 'admin/users/<user_id:\d+>/delete'),

        // admin pages pages
        array('admin/adminPage/index',              'pattern' => 'admin/pages'),
        array('admin/adminPage/view',               'pattern' => 'admin/pages/<page_id:\d+>/view'),
        array('admin/adminPage/create',             'pattern' => 'admin/pages/create'),
        array('admin/adminPage/update',             'pattern' => 'admin/pages/<page_id:\d+>/update'),
        array('admin/adminPage/delete',             'pattern' => 'admin/pages/<page_id:\d+>/delete'),
        array('admin/adminPage/changePosition',     'pattern' => 'ajax/admin/page/<page_id:\d+>/changePosition'),
        array('admin/adminPage/previewOnCreate',    'pattern' => 'ajax/admin/page/preview'),
        array('admin/adminPage/previewOnUpdate',    'pattern' => 'ajax/admin/page/<page_id:\d+>/preview'),
        array('admin/adminPage/viewPreview',        'pattern' => 'admin/page/hash/<hash:\w+>/preview/'),

        // admin settings pages
        array('admin/setting/index',    'pattern' => 'admin/settings'),
        array('admin/setting/view',     'pattern' => 'admin/settings/<setting_id:\d+>/view'),
        array('admin/setting/update',   'pattern' => 'admin/settings/<setting_id:\d+>/update'),

        // admin menu pages
        array('admin/menu/index',       'pattern' => 'admin/menu'),

        // admin menu items pages
        array('admin/menuItem/index',       'pattern' => 'admin/menu/<menu_id:\d+>/items'),
        array('admin/menuItem/create',      'pattern' => 'admin/menu/<menu_id:\d+>/items/create'),
        array('admin/menuItem/view',        'pattern' => 'admin/menu_items/<menu_item_id:\d+>/view'),
        array('admin/menuItem/update',      'pattern' => 'admin/menu_items/<menu_item_id:\d+>/update'),
        array('admin/menuItem/delete',      'pattern' => 'admin/menu_items/<menu_item_id:\d+>/delete'),
        // admin ajax menu items pages
        array('admin/menuItem/changePosition',  'pattern' => 'admin/ajax/menu_items/<menu_item_id:\d+>/changePosition'),

        //admin files pages
        array('admin/file/upload',          'pattern' => 'admin/ajax/file/model/<model:\w+>/attribute/<attribute:\w+>/hash/<hash:\w+>/upload'),
        array('admin/file/deleteFiles',     'pattern' => 'admin/ajax/file/model/<model:\w+>/attribute/<attribute:\w+>/hash/<hash:\w+>/delete'),

        //admin product category pages
        array('admin/adminProductCategory/index',           'pattern' => 'admin/product-categories'),
        array('admin/adminProductCategory/view',            'pattern' => 'admin/product-categories/<product_category_id:\d+>/view'),
        array('admin/adminProductCategory/create',          'pattern' => 'admin/product-categories/create'),
        array('admin/adminProductCategory/update',          'pattern' => 'admin/product-categories/<product_category_id:\d+>/update'),
        array('admin/adminProductCategory/delete',          'pattern' => 'admin/product-categories/<product_category_id:\d+>/delete'),
        array('admin/adminProductCategory/changePosition',  'pattern' => 'ajax/admin/product-categories/<product_category_id:\d+>/change-position'),
        array('admin/adminProductCategory/previewOnCreate', 'pattern' => 'ajax/admin/product-categories/preview'),
        array('admin/adminProductCategory/previewOnUpdate', 'pattern' => 'ajax/admin/product-categories/<product_category_id:\d+>/preview'),
        array('admin/adminProductCategory/viewPreview',     'pattern' => 'admin/product-categories/hash/<hash:\w+>/preview/'),

        //admin product pages
        array('admin/adminProduct/index',                   'pattern' => 'admin/products'),
        array('admin/adminProduct/view',                    'pattern' => 'admin/products/<product_id:\d+>/view'),
        array('admin/adminProduct/create',                  'pattern' => 'admin/products/create'),
        array('admin/adminProduct/update',                  'pattern' => 'admin/products/<product_id:\d+>/update'),
        array('admin/adminProduct/delete',                  'pattern' => 'admin/products/<product_id:\d+>/delete'),
        array('admin/adminProduct/changePosition',          'pattern' => 'ajax/admin/products/<product_id:\d+>/change-position'),
        array('admin/adminProduct/previewOnCreate',         'pattern' => 'ajax/admin/products/preview'),
        array('admin/adminProduct/previewOnUpdate',         'pattern' => 'ajax/admin/products/<product_id:\d+>/preview'),
        array('admin/adminProduct/viewPreview',             'pattern' => 'admin/products/hash/<hash:\w+>/preview/'),
        
        array('product/product/add',                        'pattern' => 'ajax/product/<product_id:\d+>/add'),
        array('product/product/delete',                     'pattern' => 'ajax/product/<product_id:\d+>/delete'),

    ), strcasecmp(substr(PHP_OS, 0, 3), 'WIN') === 0 ? array(
        /**
         * Route for uploaded files.
         * On win platforms detected problem with utf8 file names.
         * Thus file names converted by urlencode before saving.
         * @see BaseFileHelper::normalizeFSName()
         */
        array('page/uploadedFile/send',     'pattern' => 'upload/files/<dir>/<filename>'),
        array('page/uploadedFile/send',     'pattern' => 'upload/files/<dir>/<subdir>/<filename>'),
    ) : array()),
);
