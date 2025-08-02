<?php

namespace Hexlet\Code;

use Carbon\Carbon;

class UrlRepository
{
    public function __construct(private \PDO $conn)
    {
    }

    public function getEntities(): array
    {
        $urls = [];

        $sql = "SELECT * FROM urls";
        $stmt = $this->conn->query($sql);

        if ($stmt === false) {
            return $urls;
        }

        while ($row = $stmt->fetch()) {
            $url = Url::create($row['name']);
            $url->setId($row['id']);
            $url->setCreatedAt(Carbon::parse($row['created_at']));
            $urls[] = $url;
        }

        return $urls;
    }

    public function find(int $id): ?Url
    {
        $sql = "SELECT * FROM urls WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        if ($row = $stmt->fetch()) {
            $url = Url::create($row['name']);
            $id = (int) $row['id'];
            $createdAt = Carbon::parse($row['created_at']);
            $url->setId($id);
            $url->setCreatedAt($createdAt);
            return $url;
        }

        return null;
    }

    public function save(Url $url): void
    {
        if (!$this->exists($url)) {
            $this->create($url);
        }
    }

    public function exists(Url $url): bool
    {
        $sql = "SELECT * FROM urls WHERE name = :name";
        $stmt = $this->conn->prepare($sql);

        $name = $url->getName();
        $stmt->bindValue(':name', $name);

        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            $id = (int) $result['id'];
            $createdAt = Carbon::parse($result['created_at']);
            $url->setId($id);
            $url->setCreatedAt($createdAt);
            return true;
        }

        return false;
    }

    private function create(Url $url): void
    {
        $sql = "INSERT INTO urls (name, created_at) VALUES (:name, :created_at)";
        $stmt = $this->conn->prepare($sql);

        $name = $url->getName() ?? '';
        $createdAt = Carbon::now();

        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':created_at', $createdAt);
        $stmt->execute();

        $id = (int) $this->conn->lastInsertId();
        $url->setId($id);
        $url->setCreatedAt($createdAt);
    }
}
