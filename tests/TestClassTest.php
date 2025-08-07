<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Hexlet\Code\Test;

class TestClassTest extends TestCase
{
    public function testGetTest(): void
    {
        $test = new Test();
        $expected = 'Mbeumo to MUFC here we go!';
        $this->assertEquals($expected, $test->getTest());
    }
}
