<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$db2 = require __DIR__ . '/db2.php';

$config = [
    'timeZone' => 'America/Argentina/Buenos_Aires',
    'id' => 'basic',
    'language' => 'es',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    /* 'on beforeRequest' => function ($event) {
        if (
            !Yii::$app->request->isSecureConnection
            && !str_contains(Yii::$app->request->absoluteUrl, 'localhost')
        ) {
            $url = Yii::$app->request->getAbsoluteUrl();
            $url = str_replace('http:', 'https:', $url);
            Yii::$app->getResponse()->redirect($url);
            Yii::$app->end();
        }
    }, */
    'modules' => [
        'gridview' => ['class' => 'kartik\grid\Module'],
        'api' => [
            'class' => 'app\modules\api\Module',
        ]
    ],

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'defaultRoute' => 'site/login',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'ue9rDQ_JwDneqyK0KA7-5szBA40oo2lC',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'session' => array(
            'timeout' => 31557600,
        ),
        'user' => [
            'identityClass' => 'app\models\Usuarios',
            'enableAutoLogin' => false,
            'autoRenewCookie' => true,
            'authTimeout' => 31557600,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            //'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => env('MAILER_HOST'),
                'username' => env('MAILER_USERNAME'),
                'password' => env('MAILER_PASSWORD'),
                'port' => env('MAILER_PORT'),
                'encryption' => env('MAILER_ENCRYPTION'),
            ],
            'useFileTransport' => false,
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'assetManager' => [
            'bundles' => [
                /*  
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => []
                ],            
                 'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],  
     */],
        ],
        'db2' => $db2,
        'assetManager' => [
            'bundles' => [
                /*  
               'yii\bootstrap\BootstrapPluginAsset' => [
                   'js' => []
               ],            
                'yii\bootstrap\BootstrapAsset' => [
                   'css' => [],
               ],  
    */],
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    /* $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];*/

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
