<?php
$dbname=env('DB_TELEFONIA_NAME');
$dbHost=env('DB_TELEFONIA_HOST');
return [
    'class' => 'yii\db\Connection',
    'dsn' => "mysql:host=$dbHost;dbname=$dbname",
    'username' => env('DB_TELEFONIA_USERNAME'),
    'password' => env('DB_TELEFONIA_PASSWORD'),
    'charset' => 'utf8',
];
