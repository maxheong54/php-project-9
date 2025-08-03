<?php

namespace Hexlet\Code;

use Carbon\Carbon;
use PDO;

class UrlCheckRepository
{
    public function __construct(private PDO $conn)
    {
    }

    public function getChecks(int $urlId): array
    {
        $checks = [];

        $sql = "SELECT * FROM url_checks
            WHERE url_id = :url_id
            ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$urlId]);

        if ($stmt === false) {
            return $checks;
        }

        while ($row = $stmt->fetch()) {
            $check = UrlCheck::create((int) $row['url_id']);
            $check->setId((int) $row['id']);
            $check->setCreatedAt(Carbon::parse($row['created_at']));
            $checks[] = $check;
        }

        return $checks;
    }

    public function getLastCheck(int $urlId): string
    {
        $check = '';

        $sql = "SELECT * FROM url_checks
            WHERE url_id = :url_id
            ORDER BY created_at DESC
            LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$urlId]);

        if ($stmt === false) {
            return $check;
        }

        $row = $stmt->fetch();
        $check = $row['created_at'] ?? '';

        return $check;
    }
    public function save(UrlCheck $urlCheck): void
    {
        $sql = "INSERT INTO url_checks (url_id, created_at)
            VALUES (:url_id, :created_at)";
        $stmt = $this->conn->prepare($sql);

        $url_id = $urlCheck->getUrlId();
        $createdAt = Carbon::now();

        $stmt->bindValue(':url_id', $url_id);
        $stmt->bindValue('created_at', $createdAt->toDateTimeString());
        $stmt->execute();

        $id = (int) $this->conn->lastInsertId();
        $urlCheck->setId($id);
        $urlCheck->setCreatedAt($createdAt);
    }
}
