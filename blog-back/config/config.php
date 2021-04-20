<?php
// This is the database connection and global configuration.

// database settings
$DATABASE='blog';
$HOSTNAME='127.0.0.1';
$PORT=3306;
$USERNAME='root';
$PASSWORD='';

return [
    'db' => [
        'class'=>'\yii\db\Connection',
        'dsn' => "mysql:host=$HOSTNAME;dbname=$DATABASE",
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
    ],
];
