<?php

namespace Tests;

use Hexlet\Code\Url;
use Hexlet\Code\UrlRepository;

class UrlRepositoryTest extends BaseRepositoryTestCase
{
    protected UrlRepository $urlRepo;

    public function setUp(): void
    {
        parent::setUp();
        $this->urlRepo = new UrlRepository($this->connect);
    }

    public function testSave(): void
    {
        $url = Url::fromArray(['name' => $this->faker->url()]);
        $this->urlRepo->save($url);
        $found = $this->urlRepo->find($url->getId());

        $this->assertEquals($url->getName(), $found->getName());
        $this->assertEquals($url->getCreatedAt(), $found->getCreatedAt());
    }

    public function testExists(): void
    {
        $url = Url::fromArray(['name' => $this->faker->url()]);
        $this->urlRepo->save($url);
        $this->assertTrue($this->urlRepo->exists($url));
    }

    public function testGetUrls(): void
    {
        $url1 = Url::fromArray(['name' => $this->faker->url()]);
        $url2 = Url::fromArray(['name' => $this->faker->url()]);

        $this->urlRepo->save($url1);
        $this->urlRepo->save($url2);

        $urls = $this->urlRepo->getUrls();

        $this->assertIsArray($urls);
        $this->assertNotEmpty($urls);

        foreach ($urls as $url) {
            $this->assertInstanceOf(Url::class, $url);
            $this->assertNotNull($url->getName());
            $this->assertNotNull($url->getId());
            $this->assertNotNull($url->getCreatedAt());
        }
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
