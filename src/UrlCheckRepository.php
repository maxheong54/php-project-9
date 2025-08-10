<?php

namespace Hexlet\Code;

use Carbon\Carbon;
use Illuminate\Support\Arr;
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

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $checks = Arr::map($rows, function ($row) {
            return UrlCheck::fromArray($row);
        });

        return $checks;
    }

    public function getLastCheck(int $urlId): ?UrlCheck
    {
        $sql = "SELECT * FROM url_checks
            WHERE url_id = :url_id
            ORDER BY created_at DESC
            LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$urlId]);
        $row = $stmt->fetch();

        if ($row !== false) {
            return UrlCheck::fromArray($row);
        }

        return null;
    }

    public function save(UrlCheck $urlCheck): void
    {
        $sql = "INSERT INTO url_checks (url_id, created_at, status_code, h1, title, description)
            VALUES (:url_id, :created_at, :status_code, :h1, :title, :description)";
        $stmt = $this->conn->prepare($sql);

        $createdAt = Carbon::now();

        $stmt->bindValue(':url_id', $urlCheck->getUrlId());
        $stmt->bindValue(':created_at', $createdAt->format('Y-m-d H:i:s.u'));
        $stmt->bindValue(':status_code', $urlCheck->getStatusCode());
        $stmt->bindValue(':h1', $urlCheck->getH1());
        $stmt->bindValue(':title', $urlCheck->getTitle());
        $stmt->bindValue(':description', $urlCheck->getDescription());

        $stmt->execute();

        $id = (int) $this->conn->lastInsertId();
        $urlCheck->setId($id);
        $urlCheck->setCreatedAt($createdAt);
    }
}
