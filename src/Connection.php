<?php

namespace Hexlet\Code;

use Exception;
use PDO;
use PDOException;
use RuntimeException;

final class Connection
{
    private const DEFAULT_DATABASE_PORT = '5432';

    public static function create(string $dataBaseUrl): PDO
    {
        $params = parse_url($dataBaseUrl);

        if ($params === false) {
            throw new RuntimeException("Error reading database url.");
        }

        $port = $params['port'] ?? self::DEFAULT_DATABASE_PORT;
        $host = $params['host'] ?? '';
        $user = $params['user'] ?? '';
        $password = $params['pass'] ?? '';
        $name = ltrim($params['path'] ?? '', '/');

        $dsn = "pgsql:host={$host};port={$port};dbname={$name}";

        try {
            $pdo = new PDO(
                $dsn,
                $user,
                $password,
                [PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION]
            );
        } catch (Exception | PDOException $e) {
            throw new RuntimeException("Error database connection: " . $e->getMessage());
        }

        return $pdo;
    }
}
