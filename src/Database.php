<?php
namespace Bookshelf;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    public static function getPDO(): PDO
    {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO(
                    sprintf(
                        'mysql:host=%s;dbname=%s;charset=utf8mb4',
                        $_ENV['DB_HOST'],
                        $_ENV['DB_NAME']
                    ),
                    $_ENV['DB_USER'],
                    $_ENV['DB_PASS']
                );
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new \Exception(json_encode([
                    "status" => "error",
                    "message" => "Erro ao conectar ao banco de dados",
                    "error" => $e->getMessage()
                ]));
            }
        }
        return self::$pdo;
    }
}
