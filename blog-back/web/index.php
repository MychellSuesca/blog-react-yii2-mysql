<?php

error_reporting(E_ALL);

// comment out the following two lines when deployed to production
if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') {
	defined('YII_DEBUG') || define('YII_DEBUG', true);
	defined('YII_ENV_DEV') || define('YII_ENV_DEV', true);
	defined('YII_ENV') || define('YII_ENV', 'dev');
}

defined('YII_ENV') || define('YII_ENV', 'pro');
defined('YII_ENV_DEV') || define('YII_ENV_DEV', false);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
