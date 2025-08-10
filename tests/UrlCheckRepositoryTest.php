<?php

namespace Tests;

use Hexlet\Code\Url;
use Hexlet\Code\UrlCheck;
use Hexlet\Code\UrlCheckRepository;

class UrlCheckRepositoryTest extends UrlRepositoryTest
{
    private UrlCheckRepository $checkRepo;
    private Url $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->url = Url::fromArray(['name' => $this->faker->url()]);
        $this->checkRepo = new UrlCheckRepository($this->connect);
    }

    public function testSave(): void
    {
        $this->urlRepo->save($this->url);
        $check = UrlCheck::fromArray(['url_id' => $this->url->getId()]);
        $this->checkRepo->save($check);
        $found = $this->checkRepo->getLastCheck($check->getUrlId());

        $this->assertNotNull($found);
        $this->assertEquals($check->getUrlId(), $found->getUrlId());
        $this->assertEquals($check->getCreatedAt(), $found->getCreatedAt());
    }

    public function testGetChecks(): void
    {
        $this->urlRepo->save($this->url);
        $check1 = UrlCheck::fromArray(['url_id' => $this->url->getId()]);
        $check2 = UrlCheck::fromArray(['url_id' => $this->url->getId()]);
        $check3 = UrlCheck::fromArray(['url_id' => $this->url->getId()]);
        $this->checkRepo->save($check1);
        $this->checkRepo->save($check2);
        $this->checkRepo->save($check3);

        $checks = $this->checkRepo->getChecks($this->url->getId());

        $this->assertIsArray($checks);
        $this->assertCount(3, $checks);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
