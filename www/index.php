<?php
// отключаем отладку
define('YII_DEBUG', true);
define('YII_TRACE_LEVEL',3);

if (!extension_loaded('pdo') and !dl('pdo.so')) die("NO pdo HERE!");
if (!extension_loaded('pdo_mysql') and !dl('pdo_mysql.so')) die("NO pdo_mysql HERE!");

require_once '../protected/FrameworkRouter.php';

$yii=dirname(__FILE__).'/../protected/Yii.php';
try
{
  if (FrameworkRouter::Instance()->IsOnlyYiiFramework())
  {
    $config=dirname(__FILE__).'/../config/main.php';

    require_once($yii);
    Yii::createWebApplication($config)->run();
  }
  else
  {
    require_once '../library/AutoLoader.php';
    AutoLoader::Init();
    $config=dirname(__FILE__).'/../config/yiiconfig.php';
    require_once($yii);
    Yii::createWebApplication($config);
    AutoLoader::Import('library.view.*');
    AutoLoader::Import('library.widgets.*');
    AutoLoader::Import('library.hooks.*');
    AutoLoader::Import('library.rocid.search.*');
    require_once 'bootstrap.php';
    require_once 'lang/default.php';
    FrontController::GetInstance()->Run();
  }
}
catch (Exception $e)
{
  processException($e);
}




/**
 * @param Exception $e
 */
function processException($e)
{
  Yii::log('Message: ' . $e->getMessage() . "\n\n" . 'Trace string: ' . "\n" .
    $e->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');

  $logger = Yii::GetLogger();
  $logs = $logger->getLogs(CLogger::LEVEL_ERROR);//('', 'system.db.CDbCommand');
  ob_start();
  echo '<pre>';
  print_r($logs);
  print_r($_REQUEST);
  $logs = $logger->getProfilingResults();
  print_r($logs);
  echo '</pre>';
  $log = ob_get_clean();

  if (stristr($_SERVER['HTTP_HOST'], 'beta.rocid') !== false || stristr($_SERVER['HTTP_HOST'], 'pay.beta.rocid') !== false)
  {
    echo $log;
  }
  else
  {
    AutoLoader::Import('library.mail.*');

    $mail = new PHPMailer(false);
    $mail->AddAddress('nikitin@internetmediaholding.com');
    $mail->SetFrom('error@rocid.ru', 'rocID', false);
    $mail->CharSet = 'utf-8';
    $subject = 'Error! ' . $_SERVER['REQUEST_URI'] . date('d.m.Y');
    $mail->Subject = '=?UTF-8?B?'. base64_encode($subject) .'?=';
    $mail->AltBody = 'Для просмотра этого сообщения необходимо использовать клиент, поддерживающий HTML';
    $mail->MsgHTML($log);
    $mail->Send();

    Lib::Redirect('/error/404/');
  }
}