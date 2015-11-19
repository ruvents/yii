<?php
date_default_timezone_set('Europe/Moscow');
defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
defined('YII_DEBUG') or define('YII_DEBUG', true);

$dirname = dirname(__FILE__);
$config = "$dirname/../config/console.php";

require_once "$dirname/../vendor/autoload.php";
require_once "$dirname/Yii.php";

$app = Yii::createConsoleApplication($config);
$app->commandRunner->addCommands(YII_PATH . '/cli/commands');

\Yii::setPathOfAlias('webroot', "$dirname/../www/");
$env = @getenv('YII_CONSOLE_COMMANDS');
if (!empty($env))
    $app->commandRunner->addCommands($env);

$app->run();