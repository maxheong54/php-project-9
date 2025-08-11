<?php

namespace Tests;

use Dotenv\Dotenv;
use Hexlet\Code\Connection;
use PDO;

class BaseRepositoryTestCase extends BaseTestCase
{
    protected PDO $connect;

    public function setUp(): void
    {
        parent::setUp();

        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->safeLoad();
        $dotenv->required('DATABASE_URL')->notEmpty();
        $this->connect = Connection::create($_ENV['DATABASE_URL']);
        $this->connect->beginTransaction();
    }

    public function tearDown(): void
    {
        $this->connect->rollBack();
    }
}
