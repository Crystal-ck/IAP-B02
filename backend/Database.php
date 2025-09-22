<?php

namespace App;

use PDO;

class Database {
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO {
        if (self::$pdo === null) {
            $host = getenv("DB_HOST") ?: "127.0.0.1";
            $db   = getenv("DB_NAME") ?: "shopkeeper";
            $user = getenv("DB_USER") ?: "root";
            $pass = getenv("DB_PASS") ?: "";
            $dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";
            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }
        return self::$pdo;
    }
}
