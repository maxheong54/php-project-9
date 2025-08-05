<?php

namespace Hexlet\Code;

use Carbon\Carbon;

class Url
{
    private ?int $id = null;
    private ?Carbon $createdAt = null;
    private ?string $name = null;

    public static function fromArray(array $data): self
    {
        $url = new self();

        if (isset($data['id'])) {
            $url->setId((int) $data['id']);
        }

        if (isset($data['name'])) {
            $url->setName($data['name']);
        }

        if (isset($data['created_at'])) {
            $url->setCreatedAt(Carbon::parse($data['created_at']));
        }

        return $url;
    }

    // public function __construct(
    //     private ?string $name = null
    // ) {
    // }

    // public static function create(string $name): self
    // {
    //     return new self($name);
    // }
    public function setName(string $name): void
    {
        $this->name = $name;
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
