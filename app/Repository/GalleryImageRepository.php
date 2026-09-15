<?php

namespace app\Repository;

use app\Domain\GalleryImage;
use PDO;

class GalleryImageRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(GalleryImage $galleryImage): GalleryImage
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO gallery_images (
                gallery_id,
                image,
                caption
            ) VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $galleryImage->galleryId,
            $galleryImage->image,
            $galleryImage->caption
        ]);

        $galleryImage->id = (int) $this->pdo->lastInsertId();

        return $galleryImage;
    }

    public function findById(int $id): GalleryImage|false
    {
        $stmt = $this->pdo->prepare("
            SELECT
                id,
                gallery_id,
                image,
                caption,
                created_at
            FROM gallery_images
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return false;
        }

        return $this->mapToDomain($data);
    }

    public function getByGalleryId(int $galleryId): array
    {
        $stmt = $this->pdo->prepare("
        SELECT
            id,
            gallery_id,
            image,
            caption,
            created_at
        FROM gallery_images
        WHERE gallery_id = ?
        ORDER BY id ASC
    ");

        $stmt->execute([
            $galleryId
        ]);

        $rows =
            $stmt->fetchAll(
                PDO::FETCH_ASSOC
            );

        return array_map(
            fn(array $row) =>
                $this->mapToDomain($row),
            $rows
        );
    }

    public function update(GalleryImage $galleryImage): GalleryImage
    {
        $stmt = $this->pdo->prepare("
            UPDATE gallery_images
            SET
                image = ?,
                caption = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $galleryImage->image,
            $galleryImage->caption,
            $galleryImage->id
        ]);

        return $galleryImage;
    }

    public function deleteById(int $id): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM gallery_images
            WHERE id = ?
        ");

        $stmt->execute([$id]);
    }

    private function mapToDomain(array $data): GalleryImage
    {
        $galleryImage = new GalleryImage();

        $galleryImage->id = (int) $data['id'];
        $galleryImage->galleryId = (int) $data['gallery_id'];
        $galleryImage->image = $data['image'];
        $galleryImage->caption = $data['caption'];
        $galleryImage->createdAt = $data['created_at'];

        return $galleryImage;
    }
}