<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'My Web Application',

    // preloading 'log' component
    'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.components.*',
        'ext.yii-mail.YiiMailMessage',
        'application.modules.user.*',
        'application.modules.user.models.*',
        'application.modules.user.components.*',
    ),
    'defaultController' => 'site/login',
    'modules'=>array(
        // uncomment the following to enable the Gii tool

        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'qwerty',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
           //'ipFilters'=>array('127.0.0.1','192.168.1.2','www.wearesputnik.com'),
        ),
        'user' => array(
                'tableUsers' => 'tbl_users',
//                'tableProfiles' => 'tbl_profiles',
//                'tableProfileFields' => 'tbl_profiles_fields',
        ),

    ),

    // application components
    'components'=>array(
        'user'=>array(
            // enable cookie-based authentication
            'allowAutoLogin'=>true,
            'loginUrl' => array('/site/login'),
        ),
        'email'=>array(
            'class'=>'application.extensions.email.Email',
            'delivery'=>'php', //Will use the php mailing function.  
            //May also be set to 'debug' to instead dump the contents of the email into the view
        ),
        // uncomment the following to enable URLs in path-format

        'urlManager'=>array(
            'urlFormat'=>'path',
            'rules'=>array(
                //array('api/:\w+>', 'pattern'=>'api/<a_:proxy>/:\w+>', 'verb'=>'POST'),
                '<_c:(api)>/<method:\w+>' => '<_c>/proxy',
                array('api/:\w+>', 'pattern'=>'api/proxy/<method:\w+>', 'verb'=>'POST'),
                //'<controller:\w+>/<id:\d+>'=>'<controller>/view',
                //'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                //'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ),
        ),

        'db'=>array(
            'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
        ),
        // uncomment the following to use a MySQL database

        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=pm:kolibri.mobi',
            'emulatePrepare' => true,
            'username' => 'andrey',
            'password' => '1qaz2wsx',
            'charset' => 'utf8',
            'enableParamLogging' => true
        ),

        'errorHandler'=>array(
            // use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error,warning,trace,log',
                    'categories' => 'system.db.CDbCommand',
                    'logFile' => 'db.log',
                ),
                // uncomment the following to show log messages on web pages
                /*
                array(
                    'class'=>'CWebLogRoute',
                ),
                */
            ),
        ),

        'mail' => array(
             'class' => 'ext.yii-mail.YiiMail',
             'transportType' => 'php',
             'viewPath' => 'application.views.mail',
             'logging' => true,
             'dryRun' => false
         ),
        //clear Request from XSS, SQL Injection
        /*  'request'=>array(
                'enableCsrfValidation'=>true,
        ),  */
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
        // this is used in contact page
        'adminEmail'=>'Graf_nfs@ngs.ru',
    ),
);
