<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    //'webRoot' => dir(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'),
	'name'=>'活动系统',

        'language'=>'zh_cn',
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.models.tables.*',
		'application.models.forms.*',
		'application.components.*',
		'application.helpers.*',
		'application.behaviors.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
		),
		*/
		'manager'=>array('class'=>'application.modules.manager.ManagerModule'),
		'cms'=>array('class'=>'application.modules.cms.CmsModule'),
		'common'=>array('class'=>'application.modules.common.CommonModule'),
				
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'loginUrl'=> array( '/manager/site/login' )
		),
		'cache'=>array(
            'class'=>'CMemCache',
            'servers'=>array(
                array(
                    'host'=>'127.0.0.1',
                    'port'=>11211,
                ),
            ),
        ),
		//'request'=>array(
        //    'enableCsrfValidation' => true,
        //),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'common'=>'common/',
				'common/<controller:\w+>/<id:\d+>'=>'common/<controller>/view',
				'common/<controller:\w+>/<action:\w+>/<id:\d+>'=>'common/<controller>/<action>',
				'common/<controller:\w+>/<action:\w+>'=>'common/<controller>/<action>',
				'manager'=>'manager/',
				'manager/<controller:\w+>/<id:\d+>'=>'manager/<controller>/view',
				'manager/<controller:\w+>/<action:\w+>/<id:\d+>'=>'manager/<controller>/<action>',
				'manager/<controller:\w+>/<action:\w+>'=>'manager/<controller>/<action>',
			    'cms'=>'cms/',
				'cms/<controller:\w+>/<id:\d+>'=>'cms/<controller>/view',
				'cms/<controller:\w+>/<action:\w+>/<id:\d+>'=>'cms/<controller>/<action>',
				'cms/<controller:\w+>/<action:\w+>'=>'cms/<controller>/<action>',
				'<id:\w+>'=>'/site/activity/',
				'<id:\w+>/<page:\w+>'=>'/site/activityresource/',
			),
			'showScriptName'=>false,
		),
		/*'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=192.168.20.81;dbname=mzone_act',
			'emulatePrepare' => true,
			'username' => '#########',
			'password' => '#########',
			'charset' => 'utf8',
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
					'levels'=>'error',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require_once(dirname(__FILE__).'/params.php'),
);
