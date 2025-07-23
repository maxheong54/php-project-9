<?php

namespace Hexlet\Code;

class Test
{
    private string $test;

    public function __construct()
    {
        $this->test = 'Mbeumo to MUFC here we go!';
    }
    public function getTest(): string
    {
        return $this->test;
    }
}
