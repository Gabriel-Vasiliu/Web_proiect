<?php

return [
    'userClass' => \App\Models\User::class,
    'database' => [
        'name' => 'phpdb',
        'port' => '3308',
        'username' => 'root',
        'password' => 'pkk990',
        'connection' => '127.0.0.1',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
        ]
    ]
];