<?php

namespace Hexlet\Code;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use PDO;

class UrlRepository
{
    public function __construct(private PDO $conn)
    {
    }

    public function getUrls(): array
    {
        $sql = "SELECT * FROM urls";
        $stmt = $this->conn->query($sql);

        if ($stmt === false) {
            return [];
        }

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return Arr::map($rows, fn($row) => Url::fromArray($row));
    }

    public function find(int $id): ?Url
    {
        $sql = "SELECT * FROM urls WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        if ($row = $stmt->fetch()) {
            return Url::fromArray($row);
        }

        return null;
    }

    public function save(Url $url): void
    {
        if (!$this->exists($url)) {
            $sql = "INSERT INTO urls (name, created_at) VALUES (:name, :created_at)";
            $stmt = $this->conn->prepare($sql);

            $name = $url->getName() ?? '';
            $createdAt = Carbon::now();

            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':created_at', $createdAt->format('Y-m-d H:i:s.u'));
            $stmt->execute();

            $id = (int) $this->conn->lastInsertId();
            $url->setId($id);
            $url->setCreatedAt($createdAt);
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
}
