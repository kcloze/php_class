<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',
   // autoloading model and component classes
	'import'=>array(
		'application.models.*',
        'application.commands.*',
		'application.models.tables.*',
		'application.models.forms.*',
		'application.components.*',
		'application.helpers.*',
		'application.behaviors.*',
	),
	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		
		'manager'=>array('class'=>'application.modules.manager.ManagerModule'),
	    'cms'=>array(
	            'class'=>'application.modules.cms.CmsModule',
	            //'languages' => array('en', 'de'),
	            //'layout' =>'/layouts/column2',
	           ),
	),

	// application components
	'components'=>array(
		
		'cache'=>array(
            'class'=>'system.caching.CMemCache',
            'servers'=>array(
                array(
                    'host'=>'127.0.0.1',
                    'port'=>11211,
                ),
            ),
        ),
        
        
                'db'=>array(
                        'connectionString' => 'mysql:host=192.168.20.81;dbname=mzone_act',
                        'emulatePrepare' => true,
                        'username' => '#########',
                        'password' => '#########',
                        'charset' => 'utf8',
                ),

               
     ),

);