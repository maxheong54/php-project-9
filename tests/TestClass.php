<?php

namespace Hexlet\Code;

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class TestClass extends TestCase
{
    public function testGetTest(): void
    {
        $test = new Test();
        $expected = 'Mbeumo to MUFC here we go!';
        $this->assertEquals($expected, $test->getTest());
    }
}