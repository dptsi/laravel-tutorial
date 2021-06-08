<?php

return [
    'driver' => 'mysql',
    'url' => env('POST_DATABASE_URL'),
    'host' => env('POST_DB_HOST', '127.0.0.1'),
    'port' => env('POST_DB_PORT', '3306'),
    'database' => env('POST_DB_DATABASE', 'forge'),
    'username' => env('POST_DB_USERNAME', 'forge'),
    'password' => env('POST_DB_PASSWORD', ''),
    'unix_socket' => env('POST_DB_SOCKET', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'prefix_indexes' => true,
    'strict' => true,
    'engine' => null,
    'options' => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
    ]) : [],
];