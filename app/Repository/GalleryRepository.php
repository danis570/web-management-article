<?php

namespace app\Repository;

use app\App\Database;
use app\Domain\Gallery;
use PDO;

class GalleryRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Gallery $gallery): Gallery
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO galleries (
                user_id,
                caption,
                slug
            ) VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $gallery->userId,
            $gallery->caption,
            $gallery->slug
        ]);

        $gallery->id = (int) $this->pdo->lastInsertId();

        return $gallery;
    }

    public function findById(int $id): Gallery|false
    {
        $stmt = $this->pdo->prepare("
            SELECT
                id,
                user_id,
                caption,
                slug,
                created_at,
                updated_at,
                deleted_at
            FROM galleries
            WHERE id = ?
              AND deleted_at IS NULL
            LIMIT 1
        ");

        $stmt->execute([$id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return false;
        }

        return $this->mapToDomain($data);
    }

    public function getBySlug(string $slug): Gallery|false
    {
        $stmt = $this->pdo->prepare("
            SELECT
                id,
                user_id,
                caption,
                slug,
                created_at,
                updated_at,
                deleted_at
            FROM galleries
            WHERE slug = ?
              AND deleted_at IS NULL
            LIMIT 1
        ");

        $stmt->execute([$slug]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return false;
        }

        return $this->mapToDomain($data);
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->prepare("
        SELECT
            id,
            user_id,
            caption,
            slug,
            created_at,
            updated_at,
            deleted_at
        FROM galleries
        WHERE deleted_at IS NULL
        ORDER BY created_at DESC
    ");

        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn(array $row) => $this->mapToDomain($row),
            $rows
        );
    }

    public function getByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("
        SELECT
            id,
            user_id,
            caption,
            slug,
            created_at,
            updated_at,
            deleted_at
        FROM galleries
        WHERE user_id = ?
          AND deleted_at IS NULL
        ORDER BY created_at DESC
    ");

        $stmt->execute([$userId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn(array $row) => $this->mapToDomain($row),
            $rows
        );
    }

    public function update(Gallery $gallery): Gallery
    {
        $stmt = $this->pdo->prepare("
            UPDATE galleries
            SET
                caption = ?,
                slug = ?
            WHERE id = ?
              AND deleted_at IS NULL
        ");

        $stmt->execute([
            $gallery->caption,
            $gallery->slug,
            $gallery->id
        ]);

        return $gallery;
    }

    public function deleteById(int $id): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE galleries
            SET deleted_at = CURRENT_TIMESTAMP
            WHERE id = ?
              AND deleted_at IS NULL
        ");

        $stmt->execute([$id]);
    }

    private function mapToDomain(array $data): Gallery
    {
        $gallery = new Gallery();

        $gallery->id = (int) $data['id'];
        $gallery->userId = (int) $data['user_id'];
        $gallery->caption = $data['caption'];
        $gallery->slug = $data['slug'];
        $gallery->createdAt = $data['created_at'];
        $gallery->updatedAt = $data['updated_at'];
        $gallery->deletedAt = $data['deleted_at'];

        return $gallery;
    }
}