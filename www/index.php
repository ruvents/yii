<?php
// отключаем отладку
$debug = $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '82.142.129.35' || $_SERVER['REMOTE_ADDR'] == '178.140.224.229' || $_SERVER['REMOTE_ADDR'] == '10.10.4.1' || $_SERVER['REMOTE_ADDR'] == '::1';

define('YII_DEBUG', $debug);
define('YII_TRACE_LEVEL',3);

$yii=dirname(__FILE__).'/../protected/Yii.php';

date_default_timezone_set('Europe/Moscow');

$config=dirname(__FILE__).'/../config/main.php';

require_once($yii);
$vendor=dirname(__FILE__).'/../vendor/autoload.php';
require_once($vendor);

$app = Yii::createWebApplication($config);
$app->run();