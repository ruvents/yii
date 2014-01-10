<?php
$_SERVER['REQUEST_URI'] = '';
$_SERVER['SERVER_NAME'] = 'runet-id.com';
$mainAppConfig = require (dirname(__FILE__).'/main.php');
return array(
  'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'protected',
  'name'=>'RUNET-ID',
  'sourceLanguage' => 'ru',
  'language' => 'ru',

  // autoloading model and component classes
  'import'=>array(
    'application.components.Utils',
    'application.helpers.*'
   ),
    
  'behaviors'=>array(
    'templater'=>'\application\components\console\ConsoleApplicationTemplater',
  ),
    
  // application components
  'components'=>array(
    'db' => $mainAppConfig['components']['db'],
    'urlManager' => $mainAppConfig['components']['urlManager'],
    'image' => $mainAppConfig['components']['image']
  ),
    
    
  'params' => $mainAppConfig['params'], 
  'modules' => $mainAppConfig['modules']
);