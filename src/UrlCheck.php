<?php

namespace Hexlet\Code;

use Carbon\Carbon;

class UrlCheck
{
    private ?int $id = null;
    private ?int $statusCode = null;
    private ?string $h1 = null;
    private ?string $title = null;
    private ?string $description = null;
    private ?Carbon $createdAt = null;

    public function __construct(
        private ?int $urlId = null
    ) {
    }

    public static function create(int $urlId): self
    {
        return new self($urlId);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getH1(): ?string
    {
        return $this->h1;
    }

    public function setH1(string $h1): void
    {
        $this->h1 = $h1;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(Carbon $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUrlId(): ?int
    {
            return $this->urlId;
    }

    public function setUrlId(int $urlId): void
    {
            $this->urlId = $urlId;
    }
}
