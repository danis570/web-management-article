<?php

function getDatabaseConfig(): array
{
    return [
        'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=blog_app_db',
        'username' => 'root',
        'password' => ''
    ];
}