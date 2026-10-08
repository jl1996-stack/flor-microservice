<?php
declare(strict_types=1);

namespace App\Config;

use PDO;

final class Database
{
    public static function connection(): PDO
    {
        $path = getenv('DB_PATH') ?: __DIR__ . '/../../tessa.db';
        $pdo = new PDO('sqlite:' . $path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }
}
