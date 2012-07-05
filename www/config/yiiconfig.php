<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
  'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'yiibase',
  'name'=>'Rocid',
  'sourceLanguage' => 'ru_ru',
  'language' => 'ru',

  // preloading 'log' component
  'preload'=>array('log'),

  // autoloading model and component classes
  /*'import'=>array(
     'application.models.*',
     'application.components.*',
   ),*/

  /*'defaultController'=>'post',*/

  // application components
  'components'=>array(
    'user'=>array(
      // enable cookie-based authentication
      'allowAutoLogin'=>true,
      //'autoRenewCookie' => true,
      'identityCookie' => array('domain' => '.'.ROCID_HOST),
    ),

    'partner'=>array(
      // enable cookie-based authentication
      //'allowAutoLogin'=>true,
      'class'=>'CWebUser',
      //'loginUrl'=>array('jobseeker/j_logins'),
      'stateKeyPrefix'=>'partner',
      // 'returnUrl'=>array('jobseeker/jsarea'),
      'identityCookie' => array('domain' => '.'.ROCID_HOST),
      'authTimeout' => 8*60*60
    ),


    'cache'=>array(
      'class'=>'CXCache',
      //'class'=>'CDummyCache'
    ),
    // uncomment the following to use a MySQL database
    'db'=>array(
      'connectionString' => 'mysql:host=localhost;dbname=rocidbeta',
      'emulatePrepare' => true,
      'username' => 'root',
      'password' => '123456',
      'charset' => 'utf8',
      'enableProfiling' => true,
      'enableParamLogging'=>true,
      'schemaCachingDuration'=>3600,
    ),
    'session' => array(
      'class' => 'CDbHttpSession',
      'connectionID' => 'db',
      'autoCreateSessionTable' => false, //!!!
      'sessionName' => 'sessid',
      'timeout' => 180 * 24 * 60 * 60,
      'cookieParams' => array(
        'lifetime' => 180 * 24 * 60 * 60,
        'domain' => '.'.ROCID_HOST
      ),
    ),
    /*'errorHandler'=>array(
      // use 'site/error' action to display errors
      'errorAction'=>'site/error',
    ),*/
    /*'urlManager'=>array(
      'urlFormat'=>'path',
      'rules'=>array(
        'post/<id:\d+>/<title:.*?>'=>'post/view',
        'posts/<tag:.*?>'=>'post/index',
        '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
      ),
    ),*/
    'log'=>array(
      'class'=>'CLogRouter',
      'routes'=>array(
        array(
          'class'=>'CFileLogRoute',
          'levels'=>'error, warning',
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
  /*'params'=>require(dirname(__FILE__).'/params.php'),*/
);