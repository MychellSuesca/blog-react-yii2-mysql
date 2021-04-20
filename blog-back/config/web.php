<?php

$config = require __DIR__ . '/config.php';
$urlRules = require __DIR__ . '/urlRules.php';
$modules = require __DIR__ . '/modules.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],

    // set target language to be Russian
    'language' => 'es-ES',

    // set source language to be English
    'sourceLanguage' => 'es-ES',
    //
    'timeZone' => 'America/Bogota',
    //
    'charset' => 'UTF-8',

    'components' => [
        'aes256'=>[
            'class' => 'app\extensions\Aes256',
            'privatekey_32bits_hexadecimal'=> '0123456789012345678901234567890123456789012345678901234567890123', // be sure that this parameter uses EXACTLY 64 chars of hexa (a-f, 0-9)
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'lo_pOqqm7UwCQBo7YcSfwRYBUfCPpz5e',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            // ...
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                    // ...
                ],

            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\modules\auth\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'class' => app\exceptions\Handler::class,
        ],
        'session' => [
            'name' => 'PHPBACKSESSID',
            'savePath' => __DIR__ . '/../../storage/yii',
            'cookieParams' => [
                'secure' => true,
                'SameSite' => 'strict',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info', 'trace', 'profile'],
                    'categories' => [
                        'yii\db\*',
                        'yii\web\HttpException:*',
                        'yii\base\InvalidConfigException',
                        'yii\swiftmailer\Logger::add',
                    ],
                    'except' => [
                        'yii\web\HttpException:404',
                        'yii\web\HttpException:400',
                    ],
                ],
            ],
        ],
        'db' => $config['db'],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => $urlRules,
        ],
        'jwt' => [
            'class' => \sizeg\jwt\Jwt::class,
            'key' => '8xR1Lm6N4ZtMJEgH6uPnyHQEQBg1jcBhsFHRdO1DjC21O2deaBkYuAh5spt4UB7jKonectakey',
            // You have to configure ValidationData informing all claims you want to validate the token.
            'jwtValidationData' => \app\components\JwtValidationData::class,
        ],
    ],

    // modules
    'modules' => $modules,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
