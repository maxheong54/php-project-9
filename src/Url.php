<?php

namespace Hexlet\Code;

use Carbon\Carbon;

class Url
{
    private ?int $id = null;
    private ?Carbon $createdAt = null;

    public function __construct(
        private ?string $name = null
        // private ?Carbon $createdAt = null,
    ) {
    }

    public static function create(string $name): self
    {
        return new self($name);
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setCreatedAt(Carbon $time): void
    {
        $this->createdAt = $time;
    }

    public function getCreatedAt(): ?Carbon
    {
            return $this->createdAt;
    }

    public function getName(): ?string
    {
            return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
