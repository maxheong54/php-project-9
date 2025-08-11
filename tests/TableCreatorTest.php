<?php

namespace Tests;

use Hexlet\Code\TableCreator;

class TableCreatorTest extends BaseRepositoryTestCase
{
    public function testRun(): void
    {
        $this->connect->query("DROP TABLE IF EXISTS url_checks");
        $this->connect->query("DROP TABLE IF EXISTS urls");

        $tableCreator = new TableCreator();
        $tableCreator->run($this->connect);

        $sqlTable = $this->connect
            ->query("SELECT to_regclass('public.urls')")
            ->fetchColumn();
        $urlChecksTable = $this->connect
            ->query("SELECT to_regclass('public.url_checks')")
            ->fetchColumn();

        $this->assertNotNull($sqlTable);
        $this->assertNotNull($urlChecksTable);
    }
}
