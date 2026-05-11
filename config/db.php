<?php

$dbname=env('DB_DATAFAM_NAME');
$dbHost=env('DB_DATAFAM_HOST');

return [
    'class' => 'yii\db\Connection',
    'dsn' => "mysql:host=$dbHost;dbname=$dbname",
    'username' => env('DB_DATAFAM_USERNAME'),
    'password' => env('DB_DATAFAM_PASSWORD'),
    'charset' => 'utf8',
];
