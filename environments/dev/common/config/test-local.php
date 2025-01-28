<?php

return [
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=localhost;dbname='.DB_DATABASE_TEST,
            'username' => DB_USERNAME,
            'password' => DB_PASSWORD,
        ],
    ],
];
