<?php

use yii\helpers\ArrayHelper;
use yii\db\Connection;

$localConfig = __DIR__ . DIRECTORY_SEPARATOR . 'config-local.php';
$dbType = getenv('DB_TYPE') ?: 'pgsql';
$host = getenv('DB_HOST') ?: 'localhost';
$name = getenv("DB_NAME") ?: 'userdevice';
$port = getenv("DB_PORT") ?: '5432';
$dsn = "{$dbType}:host={$host};dbname={$name};port={$port}";
$config = [
    'id' => 'yii2-user-device',
    'basePath' => dirname(__DIR__),
    'components' => [
        'db' => [
            'class' => Connection::class,
            'dsn' => $dsn,
            'username' => getenv("DB_USERNAME") ?: 'postgres',
            'password' => getenv("DB_PASSWORD") ?: null,
        ],
    ],
];

return ArrayHelper::merge(
    $config,
    is_file($localConfig) ? require $localConfig : []
);
