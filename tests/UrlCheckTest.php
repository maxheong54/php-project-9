<?php

namespace Tests;

use Hexlet\Code\UrlCheck;

class UrlCheckTest extends BaseTestCase
{
    public function testFromArray(): void
    {
        $id = $this->faker->numberBetween();
        $urlId = $this->faker->numberBetween();
        $statusCode = $this->faker->numberBetween(100, 599);
        $h1 = $this->faker->text();
        $title = $this->faker->text();
        $description = $this->faker->text();
        $createdAt = $this->faker->dateTime();

        $urlCheck = UrlCheck::fromArray([
            'id' => $id,
            'url_id' => $urlId,
            'status_code' => $statusCode,
            'h1' => $h1,
            'title' => $title,
            'description' => $description,
            'created_at' => $createdAt
        ]);

        $this->assertInstanceOf(UrlCheck::class, $urlCheck);
        $this->assertEquals($id, $urlCheck->getId());
        $this->assertEquals($urlId, $urlCheck->getUrlId());
        $this->assertEquals($statusCode, $urlCheck->getStatusCode());
        $this->assertEquals($h1, $urlCheck->getH1());
        $this->assertEquals($title, $urlCheck->getTitle());
        $this->assertEquals($description, $urlCheck->getDescription());
        $this->assertEquals($createdAt, $urlCheck->getCreatedAt());
    }

    public function testFromEmptyArray(): void
    {
        $urlCheck = UrlCheck::fromArray([]);

        $this->assertInstanceOf(UrlCheck::class, $urlCheck);
        $this->assertNull($urlCheck->getId());
        $this->assertNull($urlCheck->getUrlId());
        $this->assertNull($urlCheck->getStatusCode());
        $this->assertNull($urlCheck->getH1());
        $this->assertNull($urlCheck->getTitle());
        $this->assertNull($urlCheck->getDescription());
        $this->assertNull($urlCheck->getCreatedAt());
    }
}