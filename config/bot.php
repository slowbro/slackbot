<?php

Yii::setAlias('@slackbot', dirname(__DIR__));
$db = require(__DIR__ . '/db.php');

return [
    'id' => 'slackbot',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'slackbot\commands',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
];
