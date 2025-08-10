<?php

namespace Tests;

use Hexlet\Code\UrlValidator;
use PHPUnit\Framework\TestCase;

class UrlValidatorTest extends TestCase
{
    private $validator;

    public function setUp(): void
    {
        $this->validator = new UrlValidator();
    }

    public function testValidate(): void
    {
        $data = [
            'url' => ['name' => 'https://example.com']
        ];
        $errors = $this->validator->validateUrl($data);
        $this->assertEmpty($errors);
    }

    public function testInvalidUrl(): void
    {
        $data = [
            'url' => ['name' => 'example.com']
        ];
        $expectedMessage = 'Некорректный URL';
        $errors = $this->validator->validateUrl($data);
        $actualMessage = $errors['url'];
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    public function testEmptyUrl(): void
    {
        $data = ['url' => ['name' => '']];
        $expectedMessage = 'URL не должен быть пустым';
        $errors = $this->validator->validateUrl($data);
        $actualMessage = $errors['url'];
        $this->assertEquals($expectedMessage, $actualMessage);
    }
}
