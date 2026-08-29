<?php

namespace app\App;

use PDO;
use PDOException;

class Database
{
    public static ?PDO $pdo = null;

    public static function getConnection(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        require_once __DIR__ . '/../../config/database.php';
        $config = getDatabaseConfig();

        try {
            self::$pdo = new PDO($config['dsn'], $config['username'], $config['password']);
            return self::$pdo;
        } catch (PDOException $e) {
            return throw new PDOException("Failed connect to database: {$e->getMessage()}");
        }
    }
}