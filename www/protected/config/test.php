<?php


// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return CMap::mergeArray(
    require(dirname(__FILE__).'/main-local.php'),
    array(
        'components'    =>  array(
            'fixture'   =>  array(
                'class' =>  'system.test.CDbFixtureManager',
            ),
            /* uncomment the following to provide test database connection
            'db'    =>  array(
                'connectionString'  =>  'DSN for test database',
            ),
            */
        ),
    )
);
