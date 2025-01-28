<?php
include __DIR__ . "/../../../../inc/westmarchesgame.inc";
return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host='.DB_SERVER.';dbname='.DB_DATABASE,
            'username' => DB_USERNAME,
            'password' => DB_PASSWORD,
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'scheme' => MAIL_SCHEME,
                'host' => MAIL_HOST,
                'username' => MAIL_USERNAME,
                'password' => MAIL_PASSWORD,
                'port' => MAIL_PORT,
                'encryption' => MAIL_ENCRYPTION,
            ],
        ],
    ],
];
