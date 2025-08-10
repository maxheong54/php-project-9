<?php

namespace Tests;

use Hexlet\Code\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends BaseTestCase
{
    public function testFromArray(): void
    {
        $id = $this->faker->numberBetween();
        $name = $this->faker->url();
        $createdAt = $this->faker->dateTime();

        $url = Url::fromArray([
            'id' => $id,
            'name' => $name,
            'created_at' => $createdAt
        ]);

        $this->assertInstanceOf(Url::class, $url);
        $this->assertEquals($id, $url->getId());
        $this->assertEquals($name, $url->getName());
        $this->assertEquals($createdAt, $url->getCreatedAt());
    }

    public function testFromEmptyArray(): void
    {

        $url = Url::fromArray([]);

        $this->assertInstanceOf(Url::class, $url);
        $this->assertNull($url->getId());
        $this->assertNull($url->getName());
        $this->assertNull($url->getCreatedAt());
    }
}
