<?php

date_default_timezone_set('Europe/Moscow');

define('YII_DEBUG', substr($_SERVER['HTTP_HOST'], -4) === '.dev');
define('YII_TRACE_LEVEL', 3);
define('YII_DEBUG_DISABLE_CHACHE', YII_DEBUG);
define('RUNETID_HOST', YII_DEBUG ? 'runet-id.dev' : 'runet-id.com');
