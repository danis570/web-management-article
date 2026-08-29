<?php

function getConnection(): PDO
{
    $dsn = 'mysql:host=127.0.0.1;port=3306;dbname=blog_app_db';
    $username = 'root';
    $password = '';

    try {
        $conn = new PDO($dsn, $username, $password);
        return $conn;
    } catch (PDOException $e) {
        die('database connection failed');
    }
}