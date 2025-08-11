<?php

namespace Tests;

use Dotenv\Dotenv;
use Hexlet\Code\Connection;
use InvalidArgumentException;
use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    public function testConnection(): void
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->safeLoad();
        $dotenv->required('DATABASE_URL')->notEmpty();
        $connection = Connection::create($_ENV['DATABASE_URL']);

        $this->assertInstanceOf(PDO::class, $connection);
    }
    public function testInvalidUrl(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Error reading database url.');

        Connection::create('http:///bad_url');
    }

    public function testFieldConnection(): void
    {
        $this->expectException(PDOException::class);
        $this->expectExceptionMessageMatches('/^Error database connection: /');

        Connection::create('');
    }
}
