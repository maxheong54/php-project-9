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
        $sql = "INSERT INTO url_checks (url_id, created_at, status_code)
            VALUES (:url_id, :created_at, :status_code)";
        $stmt = $this->conn->prepare($sql);

        $url_id = $urlCheck->getUrlId();
        $createdAt = Carbon::now();
        $statusCode = $urlCheck->getStatusCode();

        $stmt->bindValue(':url_id', $url_id);
        $stmt->bindValue(':created_at', $createdAt->toDateTimeString());
        $stmt->bindValue(':status_code', $statusCode);
        $stmt->execute();

        $id = (int) $this->conn->lastInsertId();
        $urlCheck->setId($id);
        $urlCheck->setCreatedAt($createdAt);
    }
}
