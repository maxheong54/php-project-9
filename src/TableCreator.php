<?php

namespace Hexlet\Code;

use PDO;
use RuntimeException;

class TableCreator
{
    public function run(PDO $conn): void
    {
        $initFilePath = dirname(__DIR__) . '/database.sql';

        $sql = file_get_contents($initFilePath);

        if ($sql === false) {
            throw new RuntimeException('Error reading init database file');
        }

        $conn->exec($sql);
    }
}
